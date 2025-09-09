<?php

namespace App\Model\adopcion;

use App\Model\Conexion;
use PDO;
use PDOException;
use Exception;

class Adopcion
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    public function crearSolicitudAdopcion(
        $id_usuario,
        $id_mascota,
        $nit_fundacion,
        $estado_civil,
        $tipo_documento,
        $numero_documento,
        $ocupacion,
        $tipo_vivienda,
        $tiene_patio,
        $seguridad_ventanas,
        $personas_hogar,
        $ninos_adultos,
        $horas_fuera_casa,
        $viajes_frecuentes,
        $experiencia_previas,
        $otras_mascotas,
        $mascotas_vacunadas,
        $compromiso_gastos,
        $situacion_economica,
        $motivacion,
        $expectativas
    ) {
        try {
            $query = "CALL sp_crear_solicitud_adopcion(
            :id_usuario, :id_mascota, :nit_fundacion, :estado_civil,
            :tipo_documento, :numero_documento, :ocupacion, :tipo_vivienda,
            :tiene_patio, :seguridad_ventanas, :personas_hogar, :ninos_adultos,
            :horas_fuera_casa, :viajes_frecuentes, :experiencia_previas, :otras_mascotas,
            :mascotas_vacunadas, :compromiso_gastos, :situacion_economica,
            :motivacion, :expectativas
        )";

            $stmt = $this->db->prepare($query);

            // Bind de parámetros con validación de tipos
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(":id_mascota", $id_mascota, PDO::PARAM_INT);
            $stmt->bindParam(":nit_fundacion", $nit_fundacion, PDO::PARAM_INT);
            $stmt->bindParam(":estado_civil", $estado_civil, PDO::PARAM_STR);
            $stmt->bindParam(":tipo_documento", $tipo_documento, PDO::PARAM_STR);
            $stmt->bindParam(":numero_documento", $numero_documento, PDO::PARAM_STR);
            $stmt->bindParam(":ocupacion", $ocupacion, PDO::PARAM_STR);
            $stmt->bindParam(":tipo_vivienda", $tipo_vivienda, PDO::PARAM_STR);
            $stmt->bindParam(":tiene_patio", $tiene_patio, PDO::PARAM_BOOL);
            $stmt->bindParam(":seguridad_ventanas", $seguridad_ventanas, PDO::PARAM_BOOL);
            $stmt->bindParam(":personas_hogar", $personas_hogar, PDO::PARAM_INT);
            $stmt->bindParam(":ninos_adultos", $ninos_adultos, PDO::PARAM_STR);
            $stmt->bindParam(":horas_fuera_casa", $horas_fuera_casa, PDO::PARAM_STR);
            $stmt->bindParam(":viajes_frecuentes", $viajes_frecuentes, PDO::PARAM_STR);
            $stmt->bindParam(":experiencia_previas", $experiencia_previas, PDO::PARAM_STR);
            $stmt->bindParam(":otras_mascotas", $otras_mascotas, PDO::PARAM_STR);
            $stmt->bindParam(":mascotas_vacunadas", $mascotas_vacunadas, PDO::PARAM_BOOL);
            $stmt->bindParam(":compromiso_gastos", $compromiso_gastos, PDO::PARAM_BOOL);
            $stmt->bindParam(":situacion_economica", $situacion_economica, PDO::PARAM_STR);
            $stmt->bindParam(":motivacion", $motivacion, PDO::PARAM_STR);
            $stmt->bindParam(":expectativas", $expectativas, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result && isset($result['formulario_id']) && isset($result['proceso_id'])) {
                    return $result;
                } else if ($result && isset($result['status']) && $result['status'] === 'success') {
                    return $result;
                } else {
                    return true;
                }
            } else {
                $errorInfo = $stmt->errorInfo();
                return false;
            }
        } catch (PDOException $e) {

            return false;
        } catch (Exception $e) {
            return false;
        }
    }


    public function obtenerNitFundacionPorMascota($id_mascota)
    {
        $sql = "SELECT nit_fundacion FROM t_mascota WHERE id_mascota = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(1, $id_mascota, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila = $resultado) {
            return $fila['nit_fundacion'];
        }
        return null;
    }

    /* Obtener procesos de adopción filtrados por usuario en sesión */
    public function getProcesosAdopcion($nit_fundacion = null, $id_usuario = null, $tipo_usuario = null)
    {
        try {
            $query = "
        SELECT 
            p.id_proceso,
            p.id_formulario,
            p.id_estado,
            p.fecha_inicio,
            p.fecha_actualizada,
            
            -- Datos del usuario solicitante
            u.nombre as nombre_usuario,
            u.apellido as apellido_usuario,
            u.email as email_usuario,
            
            -- Datos de la mascota
            m.nombre as nombre_mascota,
            m.imagen as imagen_mascota,
            m.id_mascota,
            
            -- Estado de adopción
            e.tipo_estado,
            
            -- Datos de la fundación
            f.nombre as nombre_fundacion,
            f.nit_fundacion
            
        FROM t_proceso_adopcion p
        INNER JOIN t_formulario_adopcion fa ON p.id_formulario = fa.id_formulario
        INNER JOIN t_usuario u ON fa.id_usuario = u.id_usuario
        INNER JOIN t_mascota m ON fa.id_mascota = m.id_mascota
        INNER JOIN t_estado_adopcion e ON p.id_estado = e.id_estado_adopcion
        INNER JOIN t_fundacion f ON fa.nit_fundacion = f.nit_fundacion
        WHERE 1=1
        ";

            $params = [];

            // Filtrar según el tipo de usuario
            if ($tipo_usuario === 'FUNDACION' && $nit_fundacion !== null) {

                // Para fundaciones: mostrar procesos de su fundación
                $query .= " AND f.nit_fundacion = :nit_fundacion";
                $params[':nit_fundacion'] = $nit_fundacion;
            } elseif ($tipo_usuario === 'GUARDIAN' && $id_usuario !== null) {

                // Para guardianes: mostrar solo sus propias solicitudes
                $query .= " AND fa.id_usuario = :id_usuario";
                $params[':id_usuario'] = $id_usuario;
            } elseif ($tipo_usuario === 'ADMIN') {

                // Para admin: mostrar todos (no agregar filtros adicionales)
                if ($nit_fundacion !== null) {
                    $query .= " AND f.nit_fundacion = :nit_fundacion";
                    $params[':nit_fundacion'] = $nit_fundacion;
                }
            }

            $query .= " ORDER BY p.fecha_inicio DESC";

            $stmt = $this->db->prepare($query);

            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /*Actualizar estado de proceso de adopción y mascota asociada*/
    public function actualizarEstadoProceso($id_proceso, $nuevo_estado, $nuevo_estado_mascota = null)
    {
        try {
            $this->db->beginTransaction();

            // Primero verificar que el proceso existe y obtener el id_mascota
            $queryVerificar = "
    SELECT p.id_proceso, fa.id_mascota
    FROM t_proceso_adopcion p
    INNER JOIN t_formulario_adopcion fa ON p.id_formulario = fa.id_formulario
    WHERE p.id_proceso = :id_proceso
";
            $stmtVerificar = $this->db->prepare($queryVerificar);
            $stmtVerificar->bindParam(':id_proceso', $id_proceso, PDO::PARAM_INT);
            $stmtVerificar->execute();
            $proceso = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

            if (!$proceso) {
                $this->db->rollback();
                return false;
            }

            // Actualizar el estado del proceso
            $queryProceso = "UPDATE t_proceso_adopcion
                        SET id_estado = :nuevo_estado,
                            fecha_actualizada = NOW()
                        WHERE id_proceso = :id_proceso";
            $stmtProceso = $this->db->prepare($queryProceso);
            $stmtProceso->bindParam(':nuevo_estado', $nuevo_estado, PDO::PARAM_INT);
            $stmtProceso->bindParam(':id_proceso', $id_proceso, PDO::PARAM_INT);
            $resultadoProceso = $stmtProceso->execute();

            if (!$resultadoProceso || $stmtProceso->rowCount() === 0) {
                $this->db->rollback();
                return false;
            }

            // Actualizar el estado de la mascota asociada
            if (!empty($proceso['id_mascota']) && $nuevo_estado_mascota !== null) {
                $queryMascota = "UPDATE t_mascota
                            SET id_estado_adopcion = :nuevo_estado_mascota
                            WHERE id_mascota = :id_mascota";
                $stmtMascota = $this->db->prepare($queryMascota);
                $stmtMascota->bindParam(':nuevo_estado_mascota', $nuevo_estado_mascota, PDO::PARAM_INT);
                $stmtMascota->bindParam(':id_mascota', $proceso['id_mascota'], PDO::PARAM_INT);
                $resultadoMascota = $stmtMascota->execute();

                if (!$resultadoMascota) {
                    $this->db->rollback();
                    return false;
                }

                $filasAfectadasMascota = $stmtMascota->rowCount();
            }
            $this->db->commit();

            $filasAfectadasProceso = $stmtProceso->rowCount();

            return true;
        } catch (PDOException $e) {
            $this->db->rollback();
            return false;
        }
    }

    /*Obtener detalles de un proceso específico*/
    public function obtenerProcesoPorId($id_proceso)
    {
        try {
            $query = "
            SELECT 
                p.id_proceso,
                p.id_formulario,
                p.id_estado,
                p.fecha_inicio,
                p.fecha_actualizada,
                
                -- Datos del usuario
                u.nombre as nombre_usuario,
                u.apellido as apellido_usuario,
                u.email as email_usuario,
                
                -- Datos de la mascota
                m.nombre as nombre_mascota,
                m.imagen as imagen_mascota,
                
                -- Estado de adopción
                e.tipo_estado,
                
                -- Datos de la fundación
                f.nombre as nombre_fundacion,
                f.nit_fundacion,
                
                -- Datos del formulario
                fa.*
                
            FROM t_proceso_adopcion p
            INNER JOIN t_formulario_adopcion fa ON p.id_formulario = fa.id_formulario
            INNER JOIN t_usuario u ON fa.id_usuario = u.id_usuario
            INNER JOIN t_mascota m ON fa.id_mascota = m.id_mascota
            INNER JOIN t_estado_adopcion e ON p.id_estado = e.id_estado_adopcion
            INNER JOIN t_fundacion f ON fa.nit_fundacion = f.nit_fundacion
            WHERE p.id_proceso = :id_proceso
        ";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_proceso', $id_proceso, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    /*Eliminar proceso de adopción y su formulario asociado*/
    public function eliminarProceso($id_proceso)
    {
        try {
            $this->db->beginTransaction();

            // Verificar que el proceso existe y obtener el id_formulario
            $queryVerificar = "SELECT id_proceso, id_formulario FROM t_proceso_adopcion WHERE id_proceso = :id_proceso";
            $stmtVerificar = $this->db->prepare($queryVerificar);
            $stmtVerificar->bindParam(':id_proceso', $id_proceso, PDO::PARAM_INT);
            $stmtVerificar->execute();
            $proceso = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

            if (!$proceso) {
                $this->db->rollback();
                return "NOT_FOUND";
            }

            // Eliminar primero el proceso de adopción (hijo en la relación FK)
            $queryProceso = "DELETE FROM t_proceso_adopcion WHERE id_proceso = :id_proceso";
            $stmtProceso = $this->db->prepare($queryProceso);
            $stmtProceso->bindParam(':id_proceso', $id_proceso, PDO::PARAM_INT);

            if (!$stmtProceso->execute()) {
                $this->db->rollback();
                return "ERROR_DELETE_PROCESO";
            }

            // Eliminar el formulario asociado
            if (!empty($proceso['id_formulario'])) {
                $queryFormulario = "DELETE FROM t_formulario_adopcion WHERE id_formulario = :id_formulario";
                $stmtFormulario = $this->db->prepare($queryFormulario);
                $stmtFormulario->bindParam(':id_formulario', $proceso['id_formulario'], PDO::PARAM_INT);

                if (!$stmtFormulario->execute()) {
                    $this->db->rollback();
                    return "ERROR_DELETE_FORMULARIO";
                }
            }

            $this->db->commit();
            return "SUCCESS"; 

        } catch (PDOException $e) {
            // Revertir transacción en caso de error
            $this->db->rollback();

            if ($e->getCode() === "23000") {
                return "FK_CONSTRAINT";
            }
            return "DB_ERROR";
        }
    }

    public function getFormularioAdopcion($id_formulario)
    {
        try {
            // Preparar la consulta SQL
            $sql = "SELECT 
            f.id_formulario,
            f.id_usuario,
            f.id_mascota,
            f.nit_fundacion,
            f.estado_civil,
            f.tipo_documento,
            f.numero_documento,
            f.ocupacion,
            f.tipo_vivienda,
            f.tiene_patio,
            f.seguridad_ventanas,
            f.personas_hogar,
            f.ninos_adultos,
            f.horas_fuera_casa,
            f.viajes_frecuentes,
            f.experiencia_previas,
            f.otras_mascotas,
            f.mascotas_vacunadas,
            f.compromiso_gastos,
            f.situacion_economica,
            f.motivacion,
            f.expectativas,
            f.fecha_respuesta,

            -- Datos de la mascota
            m.nombre AS nombre_mascota,
            m.imagen AS imagen_mascota,

            -- Datos del usuario (contacto)
            u.nombre AS nombre_usuario,
            u.apellido AS apellido_usuario,
            u.email AS email_usuario,
            u.telefono AS telefono_usuario,
            u.direccion AS direccion_usuario

        FROM t_formulario_adopcion f
        INNER JOIN t_mascota m ON f.id_mascota = m.id_mascota
        INNER JOIN t_usuario u ON f.id_usuario = u.id_usuario
        WHERE f.id_formulario = ?";

            // Ejecutar la consulta
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(1, $id_formulario, PDO::PARAM_INT);
            $stmt->execute();

            // Obtener el resultado
            $formulario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($formulario) {
                return $formulario;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
