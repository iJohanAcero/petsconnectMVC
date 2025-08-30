<?php

namespace App\Controller\adopcion;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\adopcion\Adopcion;
use Mpdf\Mpdf;
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
    // OBTENER ID DE USUARIO DE LA SESIÓN CORRECTA
    $id_usuario = $_SESSION['user']['id_usuario'] ?? null;
    
    if (empty($id_usuario)) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: Usuario no autenticado. Por favor, inicia sesión nuevamente.'
        ]);
        return;
    }
    
    error_log("ID usuario obtenido: " . $id_usuario);
    
    // Resto de parámetros del POST
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
    
    // Si el NIT fundación está vacío, obtenerlo de la mascota
    if (empty($nit_fundacion) && !empty($id_mascota)) {
        $nit_fundacion = $this->modeloAdopcion->obtenerNitFundacionPorMascota($id_mascota);
        error_log("NIT fundación obtenido automáticamente: " . $nit_fundacion);
    }
    
    $errores = [];
    
    // Validar campos obligatorios  
    if (empty($id_mascota)) $errores[] = "ID mascota";
    if (empty($nit_fundacion)) $errores[] = "NIT fundación";
    if (empty($estado_civil)) $errores[] = "Estado civil";
    if (empty($tipo_documento)) $errores[] = "Tipo documento";
    if (empty($numero_documento)) $errores[] = "Número documento";
    if (empty($ocupacion)) $errores[] = "Ocupación";
    if (empty($tipo_vivienda)) $errores[] = "Tipo vivienda";
    if (empty($personas_hogar)) $errores[] = "Personas en hogar";
    if (empty($motivacion)) $errores[] = "Motivación";
    
    if (!empty($errores)) {
        echo json_encode([
            'success' => false,
            'message' => "Error: faltan los siguientes datos obligatorios: " . implode(", ", $errores)
        ]);
        return;
    }
    
    try {
        error_log("Llamando al modelo con ID usuario: " . $id_usuario);
        
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
        
        error_log("Resultado del modelo: " . print_r($resultado, true));
        
        if ($resultado !== false) {
            echo json_encode([
                'success' => true,
                'message' => 'Solicitud de adopción registrada correctamente.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al registrar la solicitud de adopción. Por favor, inténtalo de nuevo.'
            ]);
        }
        
    } catch (Exception $e) {
        error_log("Excepción en registrarSolicitudAdopcion: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Error interno del servidor: ' . $e->getMessage()
        ]);
    }
}

    /* Actualizar estado del proceso de adopción*/
    public function actualizarEstado()
    {
        try {

            // Validar que lleguen los datos necesarios
            $id_proceso = $_POST['proceso_id'] ?? null;
            $nuevo_estado = $_POST['nuevo_estado'] ?? null;
            $nuevo_estado_mascota = $_POST['nuevo_estado_mascota'] ?? null;

            // Llamar al modelo para actualizar (IMPORTANTE: ahora con 3 parámetros)
            $resultado = $this->modeloAdopcion->actualizarEstadoProceso(
                $id_proceso,
                $nuevo_estado,
                $nuevo_estado_mascota
            );

            if ($resultado) {
                if ($nuevo_estado_mascota !== null) {
                    echo "Estado del proceso y mascota actualizados correctamente";
                } else {
                    echo "Estado del proceso actualizado correctamente";
                }
            } else {
                echo "Error: No se pudo actualizar el estado. Verifica los logs para más detalles.";
            }
        } catch (Exception $e) {
            echo "Error interno del servidor: " . $e->getMessage();
        }
    }

    /**Eliminar proceso de adopción*/
    public function eliminarProceso()
    {
        try {
            $id_proceso = $_POST['proceso_id'] ?? $_POST['id_proceso'] ?? $_POST['id'] ?? null;

            if (empty($id_proceso)) {
                echo "Error: ID de proceso no proporcionado.";
                return;
            }

            $resultado = $this->modeloAdopcion->eliminarProceso($id_proceso);

            switch ($resultado) {
                case "SUCCESS":
                    echo "Proceso de adopción y formulario eliminados correctamente.";
                    break;
                case "NOT_FOUND":
                    echo "El proceso de adopción no existe.";
                    break;
                case "DB_ERROR":
                default:
                    echo "Error interno en la base de datos. Contacta con soporte.";
                    break;
            }
        } catch (Exception $e) {
            echo "Error interno del servidor.";
        }
    }


    public function listarProcesos()
    {
        try {
            $tipo_usuario = $_SESSION['tipo_usuario'] ?? null;
            $nit_fundacion = $_SESSION['nit_fundacion'] ?? null; // Para fundaciones
            $id_usuario = $_SESSION['id_usuario'] ?? null; // Para guardianes

            $procesos = $this->modeloAdopcion->getProcesosAdopcion($nit_fundacion, $id_usuario, $tipo_usuario);

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

    public function generarFormularioPDF()
    {
        try {
            $id_formulario = $_GET['id_formulario'] ?? null;

            if (empty($id_formulario)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID de formulario no proporcionado'
                ]);
                return;
            }

            $formulario = $this->modeloAdopcion->getFormularioAdopcion($id_formulario);

            if (!$formulario) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Formulario no encontrado'
                ]);
                return;
            }

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 16,
                'margin_bottom' => 16,
            ]);

            $html = $this->crearPlantillaFormulario($formulario);

            $mpdf->WriteHTML($html);

            $nombre_archivo = 'formulario_adopcion_' . $id_formulario . '_' . date('Y-m-d') . '.pdf';

            $mpdf->Output($nombre_archivo, 'D');
        } catch (Exception $e) {
            error_log("Error generando PDF: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error al generar el PDF: ' . $e->getMessage()
            ]);
        }
    }

    private function crearPlantillaFormulario($formulario)
    {
        $tiene_patio = $formulario['tiene_patio'] ? 'Sí' : 'No';
        $seguridad_ventanas = $formulario['seguridad_ventanas'] ? 'Sí' : 'No';
        $mascotas_vacunadas = $formulario['mascotas_vacunadas'] ? 'Sí' : 'No';
        $compromiso_gastos = $formulario['compromiso_gastos'] ? 'Sí' : 'No';

        $fecha_respuesta = date('d/m/Y H:i', strtotime($formulario['fecha_respuesta']));

        $html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin: 15mm;
            margin-top: 20mm;
            margin-bottom: 20mm;
        }
        
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }
        
        .document-header {
            border-bottom: 3px solid #1a1333;
            padding-bottom: 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .logo-container {
            float: left;
            width: 80px;
            height: 80px;
            margin-right: 20px;
            margin-bottom: 10px;
        }
        
        .logo-placeholder {
            width: 78px;
            height: 78px;
            background: #f9f9f9;
            border: 1px dashed #999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        
        .header-content {
            text-align: center;
            margin-top: 20px;
            clear: both;
        }
        
        .document-title {
            font-size: 24px;
            font-weight: bold;
            color: #1a1333;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .document-subtitle {
            font-size: 14px;
            color: #666;
            margin: 0 0 20px 0;
        }
        
        .form-info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border: 1px solid #ddd;
        }
        
        .form-info-table td {
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-align: center;
            font-weight: bold;
        }
        
        .form-info-table .label {
            background: #f5f5f5;
            font-weight: normal;
            font-size: 11px;
            color: #666;
        }
        
        .seccion {
            margin-bottom: 25px;
            border: 1px solid #ddd;
        }
        
        .seccion-titulo {
            background: #1a1333;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
        
        .seccion-contenido {
            padding: 15px;
        }
        
        .campo-tabla {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .campo-tabla tr {
            border-bottom: 1px solid #f0f0f0;
        }
        
        .campo-tabla tr:last-child {
            border-bottom: none;
        }
        
        .campo-label {
            width: 35%;
            padding: 8px 10px;
            font-weight: bold;
            color: #333;
            vertical-align: top;
            border-right: 1px solid #f0f0f0;
        }
        
        .campo-valor {
            width: 65%;
            padding: 8px 10px;
            color: #000;
            vertical-align: top;
        }
        
        .texto-largo {
            background: #fafafa;
            padding: 8px;
            border-left: 3px solid #1a1333;
            margin-top: 3px;
            font-style: italic;
            min-height: 20px;
        }
        
        .boolean-si {
            font-weight: bold;
            color: #1a1333;
        }
        
        .boolean-no {
            color: #666;
        }
        
        .firma-seccion {
            margin-top: 40px;
            border: 2px solid #1a1333;
            padding: 25px;
        }
        
        .firma-titulo {
            text-align: center;
            font-size: 16px;
            color: #1a1333;
            margin-bottom: 30px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .firmas-tabla {
            width: 100%;
            border-collapse: collapse;
        }
        
        .firmas-tabla td {
            width: 50%;
            text-align: center;
            padding: 20px;
            vertical-align: top;
        }
        
        .firma-linea {
            border-bottom: 2px solid #000;
            width: 200px;
            margin: 40px auto 15px auto;
            height: 1px;
        }
        
        .firma-label {
            font-size: 12px;
            color: #333;
            font-weight: bold;
        }
        
        .declaracion {
            margin-top: 25px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            font-size: 10px;
            color: #555;
            text-align: justify;
            line-height: 1.4;
        }
        
        .footer-institucional {
            margin-top: 30px;
            border-top: 2px solid #1a1333;
            padding-top: 15px;
            text-align: center;
        }
        
        .footer-petsconnect {
            font-size: 16px;
            font-weight: bold;
            color: #1a1333;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .footer-descripcion {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .footer-info {
            font-size: 10px;
            color: #888;
        }
        
        .campo-destacado {
            background: #f8f9fa;
            font-weight: bold;
            color: #1a1333;
        }
    </style>
</head>
<body>
    <div class="document-header">
        <div class="logo-container">
            <div class="logo-placeholder">
                <img src="/petsconnectMVC/Public/images/icono.png" alt="Logo" class="img-fluid">
            </div>
        </div>
        
        <div class="header-content">
            <h1 class="document-title">Formulario de Adopción</h1>
            <p class="document-subtitle">Solicitud Oficial de Adopción de Mascota</p>
        </div>
        
        <table class="form-info-table">
            <tr>
                <td class="label">No. Formulario</td>
                <td class="label">Fecha de Solicitud</td>
                <td class="label">Mascota Solicitada</td>
            </tr>
            <tr>
                <td>' . $formulario['id_formulario'] . '</td>
                <td>' . $fecha_respuesta . '</td>
                <td>' . htmlspecialchars($formulario['nombre_mascota']) . ' ID: ' . $formulario['id_mascota'] . '</td>
            </tr>
        </table>
    </div>

    <div class="seccion">
        <div class="seccion-titulo">Información Personal del Solicitante</div>
        <div class="seccion-contenido">
            <table class="campo-tabla">
                <tr>
                    <td class="campo-label">ID Usuario:</td>
                    <td class="campo-valor">' . htmlspecialchars($formulario['id_usuario']) . '</td>
                </tr>
                <tr>
                    <td class="campo-label">Tipo de Documento:</td>
                    <td class="campo-valor campo-destacado">' . htmlspecialchars($formulario['tipo_documento']) . '</td>
                </tr>
                <tr>
                    <td class="campo-label">Número de Documento:</td>
                    <td class="campo-valor campo-destacado">' . htmlspecialchars($formulario['numero_documento']) . '</td>
                </tr>
                <tr>
                    <td class="campo-label">Estado Civil:</td>
                    <td class="campo-valor">' . htmlspecialchars($formulario['estado_civil']) . '</td>
                </tr>
                <tr>
                    <td class="campo-label">Ocupación:</td>
                    <td class="campo-valor">' . htmlspecialchars($formulario['ocupacion']) . '</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="seccion">
        <div class="seccion-titulo">Información de Vivienda</div>
        <div class="seccion-contenido">
            <table class="campo-tabla">
                <tr>
                    <td class="campo-label">Tipo de Vivienda:</td>
                    <td class="campo-valor">' . htmlspecialchars($formulario['tipo_vivienda']) . '</td>
                </tr>
                <tr>
                    <td class="campo-label">¿Cuenta con Patio?:</td>
                    <td class="campo-valor">
                        <span class="' . ($formulario['tiene_patio'] ? 'boolean-si' : 'boolean-no') . '">
                            ' . $tiene_patio . '
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="campo-label">Seguridad en Ventanas:</td>
                    <td class="campo-valor">
                        <span class="' . ($formulario['seguridad_ventanas'] ? 'boolean-si' : 'boolean-no') . '">
                            ' . $seguridad_ventanas . '
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="campo-label">Personas en el Hogar:</td>
                    <td class="campo-valor campo-destacado">' . htmlspecialchars($formulario['personas_hogar']) . '</td>
                </tr>
                <tr>
                    <td class="campo-label">Descripción Niños/Adultos:</td>
                    <td class="campo-valor">
                        <div class="texto-largo">' . htmlspecialchars($formulario['ninos_adultos']) . '</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="seccion">
        <div class="seccion-titulo">Estilo de Vida</div>
        <div class="seccion-contenido">
            <table class="campo-tabla">
                <tr>
                    <td class="campo-label">Horas Fuera de Casa:</td>
                    <td class="campo-valor">' . htmlspecialchars($formulario['horas_fuera_casa']) . '</td>
                </tr>
                <tr>
                    <td class="campo-label">Viajes Frecuentes:</td>
                    <td class="campo-valor">
                        <div class="texto-largo">' . htmlspecialchars($formulario['viajes_frecuentes']) . '</div>
                    </td>
                </tr>
                <tr>
                    <td class="campo-label">Experiencias Previas con Mascotas:</td>
                    <td class="campo-valor">
                        <div class="texto-largo">' . htmlspecialchars($formulario['experiencia_previas']) . '</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="seccion">
        <div class="seccion-titulo">Información sobre Mascota Solicitada</div>
        <div class="seccion-contenido">
            <table style="width: 100%; margin-bottom: 15px;">
                <tr>
                    <td style="width: 70%; vertical-align: top;">
                        <table class="campo-tabla">
                            <tr>
                                <td class="campo-label">Nombre de la Mascota:</td>
                                <td class="campo-valor campo-destacado">' . htmlspecialchars($formulario['nombre_mascota']) . '</td>
                            </tr>
                            <tr>
                                <td class="campo-label">ID Mascota:</td>
                                <td class="campo-valor">#' . htmlspecialchars($formulario['id_mascota']) . '</td>
                            </tr>
                            <tr>
                                <td class="campo-label">Otras Mascotas en el Hogar:</td>
                                <td class="campo-valor">
                                    <div class="texto-largo">' . htmlspecialchars($formulario['otras_mascotas']) . '</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="campo-label">¿Mascotas Actuales Vacunadas?:</td>
                                <td class="campo-valor">
                                    <span class="' . ($formulario['mascotas_vacunadas'] ? 'boolean-si' : 'boolean-no') . '">
                                        ' . $mascotas_vacunadas . '
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 30%; text-align: center; vertical-align: top; padding-left: 20px;">
                        <div style="border: 2px solid #1a1333; padding: 10px; background: #fafafa;">
                            <div style="width: 120px; height: 120px; margin: 0 auto; border: 2px dashed #ccc; background: #f9f9f9; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #666; text-align: center;">
                                <img src="' . htmlspecialchars($formulario['imagen_mascota']) . '" alt="Foto de la Mascota" style="max-width: 130px; max-height: 130px;">
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="seccion">
        <div class="seccion-titulo">Compromiso Económico y Motivación</div>
        <div class="seccion-contenido">
            <table class="campo-tabla">
                <tr>
                    <td class="campo-label">Compromiso con Gastos Veterinarios:</td>
                    <td class="campo-valor">
                        <span class="' . ($formulario['compromiso_gastos'] ? 'boolean-si' : 'boolean-no') . '">
                            ' . $compromiso_gastos . '
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="campo-label">Situación Económica:</td>
                    <td class="campo-valor">
                        <div class="texto-largo">' . htmlspecialchars($formulario['situacion_economica']) . '</div>
                    </td>
                </tr>
                <tr>
                    <td class="campo-label">Motivación para Adoptar:</td>
                    <td class="campo-valor">
                        <div class="texto-largo">' . htmlspecialchars($formulario['motivacion']) . '</div>
                    </td>
                </tr>
                <tr>
                    <td class="campo-label">Expectativas:</td>
                    <td class="campo-valor">
                        <div class="texto-largo">' . htmlspecialchars($formulario['expectativas']) . '</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="seccion">
        <div class="seccion-titulo">Información Institucional</div>
        <div class="seccion-contenido">
            <table class="campo-tabla">
                <tr>
                    <td class="campo-label">NIT Fundación:</td>
                    <td class="campo-valor campo-destacado">' . htmlspecialchars($formulario['nit_fundacion']) . '</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer-institucional">
        <table style="width: 100%; margin-top: 25px; border-top: 2px solid #1a1333; padding-top: 15px;">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    <p class="footer-petsconnect">PetsConnect</p>
                    <p class="footer-descripcion">Sistema de Gestión y Adopción de Mascotas</p>
                    <p style="font-size: 10px; color: #666; margin: 0;">
                        Conectando mascotas con familias responsables
                    </p>
                </td>
                <td style="width: 40%; text-align: right; vertical-align: top;">
                    <p class="footer-info">
                        Documento generado: ' . date('d/m/Y H:i:s') . '<br>
                        Sistema: PetsConnect<br>
                        <strong>www.petsconnect.com</strong>
                    </p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>';

        return $html;
    }
}

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
        case 'generar_pdf':
            $controller->generarFormularioPDF();
            break;
    }
}
