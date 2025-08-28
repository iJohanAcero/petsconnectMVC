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
            // Log de parámetros para debugging (remover en producción)
            error_log("Parámetros recibidos - Usuario: $id_usuario, Mascota: $id_mascota, Fundación: $nit_fundacion");

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
                error_log("Resultado SP: " . print_r($result, true));

                if ($result && isset($result['formulario_id']) && isset($result['proceso_id'])) {
                    return $result;
                } else if ($result && isset($result['status']) && $result['status'] === 'success') {
                    return $result;
                } else {
                    error_log("Resultado inesperado del SP: " . print_r($result, true));
                    return true;
                }
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("Error en execute(): " . print_r($errorInfo, true));
                return false;
            }
        } catch (PDOException $e) {
            error_log("PDOException en crearSolicitudAdopcion: " . $e->getMessage());
            error_log("Código de error: " . $e->getCode());
            return false;
        } catch (Exception $e) {
            error_log("Exception general en crearSolicitudAdopcion: " . $e->getMessage());
            return false;
        }
    }

    public function getProcesosAdopcion($nit_fundacion = null)
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
        ";

            // Si es una fundación específica, filtrar por su NIT
            if ($nit_fundacion !== null) {
                $query .= " WHERE f.nit_fundacion = :nit_fundacion";
            }

            $query .= " ORDER BY p.fecha_inicio DESC";

            $stmt = $this->db->prepare($query);

            if ($nit_fundacion !== null) {
                $stmt->bindParam(':nit_fundacion', $nit_fundacion, PDO::PARAM_STR);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getProcesosAdopcion: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ✅ Actualizar estado de proceso de adopción
     */
    public function actualizarEstadoProceso($id_proceso, $nuevo_estado)
    {
        try {
            // Log para debugging
            error_log("Actualizando proceso ID: $id_proceso con estado: $nuevo_estado");

            // Actualizar el estado del proceso
            $query = "UPDATE t_proceso_adopcion 
                      SET id_estado = :nuevo_estado, 
                          fecha_actualizada = NOW() 
                      WHERE id_proceso = :id_proceso";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nuevo_estado', $nuevo_estado, PDO::PARAM_INT);
            $stmt->bindParam(':id_proceso', $id_proceso, PDO::PARAM_INT);

            $resultado = $stmt->execute();

            // Verificar si realmente se actualizó alguna fila
            $filasAfectadas = $stmt->rowCount();
            error_log("Filas afectadas: $filasAfectadas");

            if ($resultado && $filasAfectadas > 0) {
                return true;
            } else if ($resultado && $filasAfectadas === 0) {
                error_log("La consulta se ejecutó pero no se actualizó ninguna fila. Verificar si existe el ID: $id_proceso");
                return false;
            } else {
                error_log("Error en la ejecución de la consulta: " . print_r($stmt->errorInfo(), true));
                return false;
            }

        } catch (PDOException $e) {
            error_log("PDOException en actualizarEstadoProceso: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ✅ Obtener detalles de un proceso específico
     */
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
            error_log("Error en obtenerProcesoPorId: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ✅ Eliminar proceso de adopción
     */
    public function eliminarProceso($id_proceso)
    {
        try {
            $this->db->beginTransaction();

            // Primero verificar que el proceso existe
            $queryVerificar = "SELECT id_proceso, id_formulario FROM t_proceso_adopcion WHERE id_proceso = :id_proceso";
            $stmtVerificar = $this->db->prepare($queryVerificar);
            $stmtVerificar->bindParam(':id_proceso', $id_proceso, PDO::PARAM_INT);
            $stmtVerificar->execute();
            
            $proceso = $stmtVerificar->fetch(PDO::FETCH_ASSOC);
            
            if (!$proceso) {
                $this->db->rollBack();
                return false;
            }

            // Eliminar el proceso de adopción
            $queryProceso = "DELETE FROM t_proceso_adopcion WHERE id_proceso = :id_proceso";
            $stmtProceso = $this->db->prepare($queryProceso);
            $stmtProceso->bindParam(':id_proceso', $id_proceso, PDO::PARAM_INT);
            
            if (!$stmtProceso->execute()) {
                $this->db->rollBack();
                return false;
            }

            // Opcional: También eliminar el formulario asociado si no tiene otros procesos
            $queryFormulario = "DELETE FROM t_formulario_adopcion WHERE id_formulario = :id_formulario";
            $stmtFormulario = $this->db->prepare($queryFormulario);
            $stmtFormulario->bindParam(':id_formulario', $proceso['id_formulario'], PDO::PARAM_INT);
            $stmtFormulario->execute(); // No verificamos el resultado porque es opcional

            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Error en eliminarProceso: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ✅ Método mejorado para eliminar (alias del anterior)
     */
    public function delete($id_proceso)
    {
        return $this->eliminarProceso($id_proceso);
    }
}