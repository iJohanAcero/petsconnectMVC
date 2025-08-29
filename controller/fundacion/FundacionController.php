<?php

namespace App\controller\Fundacion;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Fundacion\Fundacion;
use Exception;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class FundacionController
{
    private $modeloFundacion;

    public function __construct()
    {
        $this->modeloFundacion = new Fundacion();
    }

    public function registrar()
    {
        $nombre = htmlspecialchars($_POST['rep_nombre'] ?? '');
        $apellido = htmlspecialchars($_POST['rep_apellido'] ?? '');
        $contrasena = $_POST['rep_contrasena'] ?? '';
        $email = filter_var($_POST['rep_email'] ?? '', FILTER_SANITIZE_EMAIL);
        $direccion = htmlspecialchars($_POST['rep_direccion'] ?? '');
        $telefono = htmlspecialchars($_POST['rep_telefono'] ?? '');
        $nombre_fundacion = htmlspecialchars($_POST['fund_nombre'] ?? '');
        $nit_fundacion = htmlspecialchars($_POST['fund_nit'] ?? '');

        if (empty($nombre) || empty($apellido) || empty($contrasena) || empty($email) || empty($nombre_fundacion) || empty($nit_fundacion)) {
            echo "Todos los campos son obligatorios";
            return;
        }

        $resultado = $this->modeloFundacion->registrarFundacion(
            $nombre,
            $apellido,
            $contrasena,
            $email,
            $direccion,
            $telefono,
            $nombre_fundacion,
            $nit_fundacion
        );

        echo $resultado ? "Fundación registrada correctamente" : "Error al registrar fundación";
    }

    public function editar()
    {
        $nit = htmlspecialchars($_POST['nit'] ?? '');
        $nombre = htmlspecialchars($_POST['nombre'] ?? '');
        $apellido = htmlspecialchars($_POST['apellido'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $direccion = htmlspecialchars($_POST['direccion'] ?? '');
        $telefono = htmlspecialchars($_POST['telefono'] ?? '');

        // Validar campos obligatorios
        if (empty($nit) || empty($nombre) || empty($apellido) || empty($email)) {
            echo "Todos los campos son obligatorios";
            return;
        }

        $resultado = $this->modeloFundacion->updateFundacion(
            $nit,
            $nombre,
            $apellido,
            $email,
            $direccion,
            $telefono
        );

        echo $resultado ? "Fundación actualizada correctamente" : "Error al actualizar fundación";
    }

    public function eliminar()
    {
        $nit = $_POST['nit'] ?? '';

        if (empty($nit)) {
            echo "NIT de fundación no proporcionado";
            return;
        }

        $resultado = $this->modeloFundacion->delete($nit);

        if ($resultado === 'mascotas_asociadas') {
            $mascotas = $this->modeloFundacion->getMascotasAsociadas($nit);
            echo "No se puede eliminar la fundación porque tiene " . count($mascotas) . " mascota(s) asociada(s). Primero debe eliminar o reasignar las mascotas.";
        } elseif ($resultado === 'constraint_error') {
            echo "No se puede eliminar la fundación debido a restricciones de base de datos. Verifique que no tenga datos relacionados.";
        } elseif ($resultado) {
            echo "Fundación eliminada correctamente";
        } else {
            echo "Error al eliminar fundación";
        }
    }

    public function getAllFundacionesCarrusel()
    {
        try {
            $fundaciones = $this->modeloFundacion->getAllFundacionesCarrusel();

            header('Content-Type: application/json');
            echo json_encode($fundaciones);
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Error al obtener fundaciones: ' . $e->getMessage()]);
            exit;
        }
    }

    public function getDetallesFundacion()
    {
        $id = $_GET['id'] ?? '';

        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'ID de fundación no proporcionado']);
            exit;
        }

        try {
            $fundacion = $this->modeloFundacion->getDetallesPorId($id);

            header('Content-Type: application/json');
            echo json_encode($fundacion);
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Error al obtener detalles: ' . $e->getMessage()]);
            exit;
        }
    }

    public function getContactoFundacion()
    {
        $id = $_GET['id'] ?? '';

        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'ID de fundación no proporcionado']);
            exit;
        }

        try {
            $fundacion = $this->modeloFundacion->getContactoPorId($id);

            header('Content-Type: application/json');
            echo json_encode($fundacion);
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Error al obtener información de contacto: ' . $e->getMessage()]);
            exit;
        }
    }
}
// GET y POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $controller = new FundacionController();

    if ($accion === 'registrar_fundacion') {
        $controller->registrar();
    } elseif ($accion === 'editar') {
        $controller->editar();
    } elseif ($accion === 'eliminar') {
        $controller->eliminar();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    $controller = new FundacionController();

    if ($action === 'getAllFundacionesCarrusel') {
        $controller->getAllFundacionesCarrusel();
    } elseif ($action === 'getDetallesFundacion') {
        $controller->getDetallesFundacion();
    } elseif ($action === 'getContactoFundacion') {
        $controller->getContactoFundacion();
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Acción no válida']);
        exit;
    }
}
