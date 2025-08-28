<?php

namespace App\Controller\adopcion;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\adopcion\Adopcion;
use Exception;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class AdopcionController
{
    private $modeloAdopcion;

    public function __construct()
    {
        $this->modeloAdopcion = new Adopcion();
    }

    public function registrarSolicitudAdopcion()
    {
        // 1. Capturar datos del formulario (con fallback vacío si no existe)
        $id_usuario        = $_POST['id_usuario']        ?? '';
        $id_mascota        = $_POST['id_mascota']        ?? '';
        $nit_fundacion     = $_POST['nit_fundacion']     ?? '';
        $estado_civil      = $_POST['estado_civil']      ?? '';
        $tipo_documento    = $_POST['tipo_documento']    ?? '';
        $numero_documento  = $_POST['numero_documento']  ?? '';
        $ocupacion         = $_POST['ocupacion']         ?? '';
        $tipo_vivienda     = $_POST['tipo_vivienda']     ?? '';
        $tiene_patio = isset($_POST['patio']) ? ($_POST['patio'] == 'si' ? 1 : 0) : 0;
        $seguridad_ventanas = isset($_POST['seguridad']) ? ($_POST['seguridad'] == 'si' ? 1 : 0) : 0;
        $personas_hogar    = $_POST['personas_hogar']    ?? '';
        $ninos_adultos     = $_POST['ninos_adultos']     ?? '';
        $horas_fuera_casa  = $_POST['horas_fuera_casa']  ?? '';
        $viajes_frecuentes = $_POST['viajes_frecuentes'] ?? '';
        $experiencia_previas = $_POST['experiencia_previas'] ?? '';
        $otras_mascotas    = $_POST['otras_mascotas']    ?? '';
        $mascotas_vacunadas = isset($_POST['vacunas']) ? ($_POST['vacunas'] == 'si' ? 1 : 0) : 0;
        $compromiso_gastos = isset($_POST['compromiso_gastos']) ? 1 : 0;
        $situacion_economica = $_POST['situacion_economica'] ?? '';
        $motivacion        = $_POST['motivacion']        ?? '';
        $expectativas      = $_POST['expectativas']      ?? '';

        $errores = [];

        if (!empty($errores)) {
            echo json_encode([
                'success' => false,
                'message' => "Error: faltan los siguientes datos obligatorios: " . implode(", ", $errores)
            ]);
            return;
        }

        // 3. Llamar al modelo para ejecutar el SP
        $resultado = $this->modeloAdopcion->crearSolicitudAdopcion(
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
        );

        if ($resultado !== false) {
            if (is_array($resultado)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Solicitud de adopción registrada correctamente.'
                ]);
            } else {
                echo json_encode([
                    'success' => true,
                    'message' => 'Solicitud de adopción registrada correctamente.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al registrar la solicitud de adopción. Por favor, inténtalo de nuevo.'
            ]);
        }
    }

    /**
     * ✅ Actualizar estado del proceso de adopción
     */
    public function actualizarEstado()
    {
        try {
            // Validar que lleguen los datos necesarios
            $id_proceso = $_POST['proceso_id'] ?? null;
            $nuevo_estado = $_POST['nuevo_estado'] ?? null;

            // Log para debugging
            error_log("POST recibido: " . print_r($_POST, true));

            // Validaciones básicas
            if (empty($id_proceso)) {
                echo "Error: ID de proceso no proporcionado";
                return;
            }

            if (empty($nuevo_estado)) {
                echo "Error: Nuevo estado no proporcionado";
                return;
            }

            // Validar que el nuevo estado sea válido (ajustar según tus estados reales)
            $estados_validos = [1, 2, 3, 4]; // 1=Adoptado, 2=En Trámite, 3=Aprobado, 4=Rechazado
            if (!in_array((int)$nuevo_estado, $estados_validos)) {
                echo "Error: Estado no válido ($nuevo_estado)";
                return;
            }

            // Llamar al modelo para actualizar
            $resultado = $this->modeloAdopcion->actualizarEstadoProceso($id_proceso, $nuevo_estado);

            if ($resultado) {
                echo "Estado actualizado correctamente";
            } else {
                echo "Error: No se pudo actualizar el estado. Verifica que el proceso existe.";
            }
        } catch (Exception $e) {
            error_log("Error en actualizarEstado: " . $e->getMessage());
            echo "Error interno del servidor: " . $e->getMessage();
        }
    }

    /**
     * ✅ Eliminar proceso de adopción
     */
    public function eliminarProceso()
    {
        try {
            $id_proceso = $_POST['proceso_id'] ?? $_POST['id_proceso'] ?? $_POST['id'] ?? null;

            // ✅ Validación antes de mandar al modelo
            if (empty($id_proceso)) {
                echo "⚠️ Error: ID de proceso no proporcionado.";
                return;
            }

            // ✅ Llamada al modelo
            $resultado = $this->modeloAdopcion->eliminarProceso($id_proceso);

            // ✅ Interpretar el resultado y responder
            switch ($resultado) {
                case "SUCCESS":
                    echo "✅ Proceso de adopción eliminado correctamente.";
                    break;

                case "NOT_FOUND":
                    echo "⚠️ No se encontró ningún proceso con el ID proporcionado ($id_proceso).";
                    break;

                case "FK_CONSTRAINT":
                    echo "⚠️ No se puede eliminar el proceso porque está relacionado con otros registros (ej: solicitudes, historial, etc.).";
                    break;

                case "ERROR_DELETE":
                    echo "❌ Error al intentar eliminar el proceso.";
                    break;

                case "DB_ERROR":
                default:
                    echo "❌ Error interno en la base de datos. Contacta con soporte.";
                    break;
            }
        } catch (Exception $e) {
            error_log("Error general en eliminarProceso: " . $e->getMessage());
            echo "❌ Error interno del servidor.";
        }
    }

    /**
     * ✅ Listar procesos de adopción (para AJAX)
     */
    public function listarProcesos()
    {
        try {
            $nit_fundacion = $_GET['nit_fundacion'] ?? $_POST['nit_fundacion'] ?? null;

            $procesos = $this->modeloAdopcion->getProcesosAdopcion($nit_fundacion);

            echo json_encode([
                'success' => true,
                'data' => $procesos
            ]);
        } catch (Exception $e) {
            error_log("Error en listarProcesos: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error al cargar procesos'
            ]);
        }
    }
}

// ✅ Router de acciones mejorado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? $_POST['action'] ?? '';
    $controller = new AdopcionController();

    switch ($accion) {
        case 'registrar':
            $controller->registrarSolicitudAdopcion();
            break;

        case 'actualizar_estado':
            $controller->actualizarEstado();
            break;

        case 'eliminar':
            $controller->eliminarProceso();
            break;

        default:
            echo "Acción no reconocida";
            break;
    }
}

// ✅ Router para peticiones GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $accion = $_GET['accion'] ?? $_GET['action'] ?? '';
    $controller = new AdopcionController();

    switch ($accion) {
        case 'listar':
            $controller->listarProcesos();
            break;
        default:
            echo "Acción GET no reconocida";
            break;
    }
}
