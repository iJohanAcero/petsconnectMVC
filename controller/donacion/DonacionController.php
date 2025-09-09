<?php

namespace App\Controller\Donacion;

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../Config/stripe.php';

use App\Model\Donacion\Donacion;
use Exception;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class DonacionController
{
    private $Donacion;

    public function __construct()
    {
        $this->Donacion = new Donacion();
    }

    /* Endpoint principal */

    public function manejarPeticion()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {
            header('Content-Type: application/json');
            $accion = $_POST['accion'] ?? '';

            switch ($accion) {
                case 'crearDonacion':
                    $this->procesarCrearDonacion();
                    break;

                default:
                    http_response_code(400);
                    echo json_encode(['error' => 'Acción no válida']);
                    break;
            }
        } elseif ($method === 'GET') {
            $action = $_GET['action'] ?? '';

            switch ($action) {
                case 'generarFacturaDonacionPDF':
                    $this->generarFacturaDonacionPDF();
                    break;

                default:
                    http_response_code(400);
                    echo json_encode(['error' => 'Acción GET no válida']);
                    break;
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
        }
    }


    /*Procesar creaciÃ³n de donaciÃ³n*/
    private function procesarCrearDonacion()
    {
        try {
            if (ob_get_level()) ob_end_clean();
            header('Content-Type: application/json');

            // Validar datos
            $idCausa = $_POST['id_causa'] ?? '';
            $monto = $_POST['monto'] ?? '';
            $nombreDonante = $_POST['nombre_donante'] ?? '';
            $emailDonante = $_POST['email_donante'] ?? '';

            // Obtener ID de usuario de la sesiÃ³n
            $idUsuario = $_SESSION['user']['id_usuario'];

            if (empty($idCausa) || empty($monto)) {
                echo json_encode([
                    'status' => 'error',
                     'Faltan datos requeridos'
                ]);
                exit;
            }

            // Validar email del donante para el recibo
            if (empty($emailDonante) || !filter_var($emailDonante, FILTER_VALIDATE_EMAIL)) {
                echo json_encode([
                    'status' => 'error',
                     'Email del donante es requerido y debe ser válido para enviar el recibo'
                ]);
                exit;
            }

            // Obtener NIT de la fundaciÃ³n
            $nitFundacion = $this->obtenerNitFundacionPorCausa($idCausa);

            try {
                // 1. Crear PaymentIntent en Stripe con envío automático de recibo
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => $monto * 100,
                    'currency' => 'cop',
                    'payment_method_types' => ['card'],
                    'metadata' => [
                        'id_causa' => $idCausa,
                        'nombre_donante' => $nombreDonante,
                        'email_donante' => $emailDonante,
                        'nit_fundacion' => $nitFundacion
                    ]
                ]);

                // Guardar en la base de datos 
                $this->Donacion->crearDonacion(
                    $idUsuario,
                    $idCausa,
                    $nitFundacion,
                    $monto,
                    $paymentIntent->id
                );

                echo json_encode([
                    'status' => 'success',
                    'clientSecret' => $paymentIntent->client_secret
                ]);
                exit;
            } catch (\Stripe\Exception\ApiErrorException $e) {
                echo json_encode([
                    'status' => 'error',
                     'Error de Stripe: ' . $e->getMessage()
                ]);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                 'Error interno del servidor: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    /* Obtener NIT de fundaciÃ³n */
    private function obtenerNitFundacionPorCausa($idCausa)
    {
        try {
            return $this->Donacion->obtenerNitFundacionPorCausa($idCausa);
        } catch (Exception $e) {
            return null;
        }
    }

    /* Crear un PaymentIntent y registrar la donaciÃ³n en estado pagado */
    public function crearDonacion($idUsuario, $idCausa, $nitFundacion, $monto, $emailDonante = null)
    {
        try {
            $paymentIntentData = [
                'amount' => (int) $monto,
                'currency' => 'cop',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'usuario_id' => $idUsuario,
                    'causa_id' => $idCausa,
                    'fundacion' => $nitFundacion
                ]
            ];

            $paymentIntent = \Stripe\PaymentIntent::create($paymentIntentData);

            // Guardar en BD como "pagado"
            $this->Donacion->crearDonacion(
                $idUsuario,
                $idCausa,
                $nitFundacion,
                $monto,
                $paymentIntent->id
            );

            return [
                'status' => 'success',
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                 $e->getMessage()
            ];
        }
    }

    public function generarFacturaDonacionPDF()
    {
        try {
            $id_donacion = $_GET['id_donacion'] ?? null;

            if (empty($id_donacion)) {
                echo json_encode([
                    'success' => false,
                     'ID de donación no proporcionado'
                ]);
                return;
            }

            // 🔹 Obtener datos de la donación desde el modelo
            $donacion = $this->Donacion->getDonacionPorId($id_donacion);

            if (!$donacion) {
                echo json_encode([
                    'success' => false,
                     'Donación no encontrada'
                ]);
                return;
            }

            // 🔹 Configuración de PDF mejorada
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => [180, 280], // Tamaño personalizado para factura
                'orientation' => 'P',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 5,
                'margin_footer' => 5,
                'default_font_size' => 10,
                'default_font' => 'helvetica',
                'display_mode' => 'fullpage',
                'setAutoTopMargin' => 'stretch',
                'setAutoBottomMargin' => 'stretch'
            ]);

            // 🔹 Crear la plantilla HTML de la factura
            $html = $this->crearPlantillaFactura($donacion);

            // 🔹 Escribir el contenido en el PDF
            $mpdf->WriteHTML($html);

            // 🔹 Nombre del archivo
            $nombre_archivo = 'factura_donacion_' . $id_donacion . '_' . date('Y-m-d') . '.pdf';

            // 🔹 Descargar el PDF
            $mpdf->Output($nombre_archivo, 'D');
        } catch (Exception $e) {
            error_log("Error generando PDF: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                 'Error al generar el PDF: ' . $e->getMessage()
            ]);
        }
    }

    private function crearPlantillaFactura(array $donacion): string
{
    // Format the amount with thousand separator and decimals
    $monto_formateado = number_format($donacion['monto'], 2, ',', '.');

    // Format the dates
    $fecha_donacion = date('Y-m-d H:i:s', strtotime($donacion['fecha']));
    $fecha_impresion = date('d/m/Y H:i:s');

    return "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Factura de Donación</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f5f5f5;
        }
        .pagina {
            width: 180mm;
            min-height: 280mm;
            margin: 0 auto;
            padding: 10mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background: white;
            box-sizing: border-box;
        }
        .encabezado {
            text-align: center;
            margin-bottom: 5mm;
            padding-bottom: 3mm;
            border-bottom: 1px solid #ddd;
        }
        .titulo-principal {
            font-size: 16px;
            font-weight: bold;
            color: #2e518f;
            margin-bottom: 2mm;
            text-transform: uppercase;
        }
        .subtitulo {
            font-size: 12px;
            color: #666;
            margin-bottom: 3mm;
        }
        .documento-tipo {
            font-size: 14px;
            font-weight: bold;
            background-color: #2e518f;
            color: white;
            padding: 2mm;
            border-radius: 3px;
            margin-bottom: 3mm;
        }
        .info-factura {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4mm;
            font-size: 9px;
        }
        .resolucion-dian {
            text-align: center;
            font-size: 8px;
            color: #666;
            margin-bottom: 4mm;
            padding: 2mm;
            border: 1px dashed #ccc;
            background-color: #f9f9f9;
        }
        .column-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4mm;
        }
        .columna {
            width: 49%;
        }
        .seccion {
            margin-bottom: 4mm;
            padding: 3mm;
            border: 1px solid #ddd;
            border-radius: 3px;
            background-color: #fff;
        }
        h3 {
            font-size: 11px;
            color: #2e518f;
            border-bottom: 1px solid #2e518f;
            padding-bottom: 1mm;
            margin-top: 0;
            margin-bottom: 2mm;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1mm;
            font-size: 9px;
        }
        th, td {
            padding: 2mm;
            border: 1px solid #ddd;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 35%;
        }
        .monto {
            font-size: 12px;
            font-weight: bold;
            color: #2e518f;
        }
        .estado {
            display: inline-block;
            padding: 1mm 2mm;
            border-radius: 3px;
            font-weight: bold;
            background-color: #dff0d8;
            color: #3c763d;
            font-size: 9px;
        }
        .detalle-pago {
            margin-top: 4mm;
        }
        .codigos {
            display: flex;
            justify-content: space-between;
            margin-top: 5mm;
            padding-top: 3mm;
            border-top: 1px solid #ddd;
        }
        .codigo-barras {
            text-align: center;
            font-family: 'Libre Barcode 128', cursive;
            font-size: 28px;
            margin-bottom: 2mm;
        }
        .leyenda {
            font-size: 8px;
            color: #666;
            text-align: center;
            margin-top: 3mm;
        }
        .pie-pagina {
            text-align: center;
            margin-top: 5mm;
            padding-top: 3mm;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 8px;
        }
        .nota-legal {
            font-size: 7px;
            color: #888;
            margin-top: 2mm;
            text-align: justify;
        }
    </style>
</head>
<body>
    <div class='pagina'>
        <div class='encabezado'>
            <div class='titulo-principal'>{$donacion['nombre_fundacion']}</div>
            <div class='subtitulo'>NIT: {$donacion['nit_fundacion']}</div>
            <div class='documento-tipo'>FACTURA DE DONACIÓN</div>
            
            <div class='info-factura'>
                <div><strong>N° Factura:</strong> {$donacion['id_donacion']}</div>
                <div><strong>Fecha de emisión:</strong> {$fecha_donacion}</div>
            </div>
            
            <div class='resolucion-dian'>
                Resolución DIAN: 18764009847834 - Número: 1000 - Fecha: 2023-11-15<br>
                Rango autorizado: 1 - 5000 - Prefijo: SET - Vigencia: 2024-12-31
            </div>
        </div>

        <div class='column-container'>
            <div class='columna'>
                <div class='seccion'>
                    <h3>Datos del Donante</h3>
                    <table>
                        <tr>
                            <th>Nombre/Razón Social</th>
                            <td>{$donacion['nombre_donante']}</td>
                        </tr>
                        <tr>
                            <th>Identificación</th>
                            <td>{$donacion['id_donante']}</td>
                        </tr>
                        <tr>
                            <th>Correo electrónico</th>
                            <td>{$donacion['email_donante']}</td>
                        </tr>
                        <tr>
                            <th>Teléfono de contacto</th>
                            <td>{$donacion['telefono_donante']}</td>
                        </tr>
                        <tr>
                            <th>Dirección</th>
                            <td>{$donacion['direccion_donante']}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class='columna'>
                <div class='seccion'>
                    <h3>Datos del Receptor</h3>
                    <table>
                        <tr>
                            <th>Nombre/Razón Social</th>
                            <td>{$donacion['nombre_fundacion']}</td>
                        </tr>
                        <tr>
                            <th>NIT</th>
                            <td>{$donacion['nit_fundacion']}</td>
                        </tr>
                        <tr>
                            <th>Responsable</th>
                            <td>{$donacion['responsable_fundacion']}</td>
                        </tr>
                        <tr>
                            <th>Correo electrónico</th>
                            <td>{$donacion['email_fundacion']}</td>
                        </tr>
                        <tr>
                            <th>Teléfono de contacto</th>
                            <td>{$donacion['telefono_fundacion']}</td>
                        </tr>
                        <tr>
                            <th>Dirección</th>
                            <td>{$donacion['direccion_fundacion']}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class='seccion detalle-pago'>
            <h3>Detalles del Pago</h3>
            <table>
                <tr>
                    <th>Descripción</th>
                    <th>Valor</th>
                </tr>
                <tr>
                    <td>Donación a {$donacion['nombre_fundacion']}</td>
                    <td class='monto'>\${$monto_formateado} COP</td>
                </tr>
                <tr>
                    <td>Método de Pago</td>
                    <td>{$donacion['metodo_pago']}</td>
                </tr>
                <tr>
                    <td>Estado</td>
                    <td><span class='estado'>{$donacion['estado']}</span></td>
                </tr>
            </table>
        </div>

        <div class='nota-legal'>
            Este documento es una factura de donación equivalente según lo establecido en el artículo 617 del Estatuto Tributario.
            La presente factura equivale a un documento equivalente según Resolución 007 de 2021 de la DIAN.
            Valor recibido conforme, no hay lugar a reclamos posteriores.
        </div>

        <div class='pie-pagina'>
            <p>¡Gracias por tu apoyo! Tu generosidad ayuda a transformar vidas 🐾</p>
            <p>{$donacion['nombre_fundacion']} - NIT: {$donacion['nit_fundacion']}</p>
            <p>Documento impreso el: {$fecha_impresion}</p>
        </div>
    </div>
</body>
</html>
";
}
}


if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    $controller = new DonacionController();
    $controller->manejarPeticion();
}
