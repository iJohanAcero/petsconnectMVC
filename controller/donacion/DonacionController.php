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
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }

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
    }

    /*Procesar creación de donación*/
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

            // Obtener ID de usuario de la sesión
            $idUsuario = $_SESSION['user']['id_usuario'];

            if (empty($idCausa) || empty($monto)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Faltan datos requeridos'
                ]);
                exit;
            }

            // Obtener NIT de la fundación
            $nitFundacion = $this->obtenerNitFundacionPorCausa($idCausa);

            try {
                // 1. Crear PaymentIntent en Stripe
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => $monto * 100,
                    'currency' => 'cop',
                    'metadata' => [
                        'id_causa' => $idCausa,
                        'nombre_donante' => $nombreDonante,
                        'email_donante' => $emailDonante
                    ]
                ]);

                // 2. Guardar en la base de datos
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
                    'message' => $e->getMessage()
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

    /* Obtener NIT de fundación */
    private function obtenerNitFundacionPorCausa($idCausa)
    {
        try {
            return $this->Donacion->obtenerNitFundacionPorCausa($idCausa);
        } catch (Exception $e) {
            return null;
        }
    }

    /* Crear un PaymentIntent y registrar la donación en estado pagado */
    public function crearDonacion($idUsuario, $idCausa, $nitFundacion, $monto)
    {
        try {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => (int) $monto,
                'currency' => 'cop',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'usuario_id' => $idUsuario,
                    'causa_id' => $idCausa,
                    'fundacion' => $nitFundacion
                ]
            ]);

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
}


if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    $controller = new DonacionController();
    $controller->manejarPeticion();
}
