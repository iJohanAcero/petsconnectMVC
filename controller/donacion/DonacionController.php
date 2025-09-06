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
                    'message' => 'Faltan datos requeridos'
                ]);
                exit;
            }

            // Validar email del donante para el recibo
            if (empty($emailDonante) || !filter_var($emailDonante, FILTER_VALIDATE_EMAIL)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Email del donante es requerido y debe ser válido para enviar el recibo'
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
                    'message' => 'Error de Stripe: ' . $e->getMessage()
                ]);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error interno del servidor: ' . $e->getMessage()
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
                'message' => $e->getMessage()
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
                    'message' => 'ID de donación no proporcionado'
                ]);
                return;
            }

            // 🔹 Obtener datos de la donación desde el modelo
            $donacion = $this->Donacion->getDonacionPorId($id_donacion);

            if (!$donacion) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Donación no encontrada'
                ]);
                return;
            }

            // 🔹 Configuración de PDF
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 10,       // Reducido para dar más espacio al contenido
                'margin_bottom' => 10,    // Reducido para dar más espacio al contenido
                'margin_header' => 5,
                'margin_footer' => 5,
                'default_font_size' => 10, // Tamaño de fuente por defecto
                'default_font' => 'helvetica', // Fuente compatible con PDF
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
                'message' => 'Error al generar el PDF: ' . $e->getMessage()
            ]);
        }
    }


    private function crearPlantillaFactura(array $donacion): string
    {
        return "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Factura de Donación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .factura-container {
            width: 100%;
            padding: 0;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2e518f;
        }
        h2 {
            color: #2e518f;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 18px;
        }
        .document-type {
            font-size: 14px;
            font-weight: bold;
            color: #666;
            margin-bottom: 10px;
        }
        .resolucion-dian {
            text-align: center;
            font-size: 10px;
            margin-bottom: 15px;
            color: #666;
        }
        .two-columns {
            width: 100%;
            margin-bottom: 15px;
        }
        .column-container {
            width: 100%;
            display: block;
        }
        .column {
            width: 48%;
            display: inline-block;
            vertical-align: top;
            box-sizing: border-box;
        }
        .left-column {
            padding-right: 2%;
        }
        .seccion {
            margin-bottom: 15px;
        }
        h3 {
            background-color: #f5f5f5;
            padding: 8px;
            border-left: 4px solid #2e518f;
            margin: 10px 0 8px 0;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        th, td {
            padding: 7px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f9f9f9;
            width: 35%;
            font-weight: bold;
        }
        .monto {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
        }
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
            background-color: #dff0d8;
            color: #3c763d;
        }
        .legal {
            font-size: 10px;
            margin-top: 20px;
            color: #777;
            text-align: justify;
        }
        .footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #666;
        }
        .qr-section {
            text-align: center;
            margin: 15px 0;
        }
        .qr-placeholder {
            display: inline-block;
            width: 100px;
            height: 100px;
            border: 1px dashed #ccc;
            line-height: 100px;
            text-align: center;
            color: #999;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class='factura-container'>
        <div class='resolucion-dian'>
            Resolución DIAN No. 18764000000000 - Facturación por donación - Régimen simplificado
        </div>
        
        <div class='header'>
            <h2>FACTURA DE DONACIÓN</h2>
            <div class='document-type'>Documento Equivalente</div>
            <p><strong>N° Factura:</strong> 13 &nbsp; | &nbsp; <strong>Fecha:</strong> 2025-09-03 00:04:50</p>
        </div>

        <div class='column-container'>
            <div class='column left-column'>
                <!-- Datos del Donante -->
                <div class='seccion'>
                    <h3>Datos del Donante</h3>
                    <table>
                        <tr>
                            <th>Nombre</th>
                            <td>Admin</td>
                        </tr>
                        <tr>
                            <th>Identificación</th>
                            <td>31</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>admin@gmail.com</td>
                        </tr>
                        <tr>
                            <th>Teléfono</th>
                            <td>123456</td>
                        </tr>
                        <tr>
                            <th>Dirección</th>
                            <td>Bogotá</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class='column'>
                <!-- Datos de la Fundación -->
                <div class='seccion'>
                    <h3>Datos de la Fundación</h3>
                    <table>
                        <tr>
                            <th>Nombre</th>
                            <td>Fundacion1</td>
                        </tr>
                        <tr>
                            <th>NIT</th>
                            <td>11111</td>
                        </tr>
                        <tr>
                            <th>Responsable</th>
                            <td>Jhon</td>
                        </tr>
                        <tr>
                            <th>Dirección</th>
                            <td>Bogotá, calle12</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>fundacion@gmail.com</td>
                        </tr>
                        <tr>
                            <th>Teléfono</th>
                            <td>111111</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Detalle de la Donación -->
        <div class='seccion'>
            <h3>Detalle de la Donación</h3>
            <table>
                <tr>
                    <th>Monto donado</th>
                    <td class='monto'>$50.000 COP</td>
                </tr>
                <tr>
                    <th>Método de Pago</th>
                    <td>stripe</td>
                </tr>
                <tr>
                    <th>Estado</th>
                    <td><span class='status'>pagado</span></td>
                </tr>
            </table>
        </div>

        <div class='legal'>
            <p>De acuerdo con el artículo 437 del Estatuto Tributario, las donaciones a entidades sin ánimo de lucro debidamente reconocidas pueden ser deducibles de impuestos. Consulte con su contador para más detalles.</p>
            <p>Esta factura de donación es un documento equivalente que cumple con los requisitos establecidos por la DIAN para operaciones de donación.</p>
        </div>

        <div class='footer'>
            <p>¡Gracias por tu apoyo! Tu aporte ayuda a cambiar vidas 🐾</p>
            <p>Fundacion1 - NIT: 11111</p>
            <p>Impreso el: 06/09/2025 08:03:21</p>
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
