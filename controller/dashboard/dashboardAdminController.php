<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\dashboard\DashboardAdmin;
use App\Model\Fundacion\Fundacion;
use App\Config\Roles;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class dashboardAdminController
{
    private $model;

    public function __construct()
    {
        $this->model = new DashboardAdmin();
    }

    // Método para verificar sesión y obtener NIT (igual que tu validación)
    private function verificarSesion()
    {
        $id_usuario = $_SESSION['user']['id_usuario'] ?? null;

        if (!$id_usuario) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode([
                'data' => [],
                'success' => false,
                'message' => 'Usuario no autenticado'
            ]);
            exit;
        }

        // Verificar si es admin (admin ve todo)
        $esAdmin = Roles::esAdmin($id_usuario);
        if ($esAdmin) {
            return null; // null significa "mostrar todo"
        }

        // Si no es admin, obtener NIT de la fundación
        $nit_fundacion = Fundacion::obtenerNitPorUsuario($id_usuario);

        if (!$nit_fundacion) {
            header('HTTP/1.1 403 Forbidden');
            echo json_encode([
                'data' => [],
                'success' => false,
                'message' => 'Usuario sin fundación asignada'
            ]);
            exit;
        }

        return $nit_fundacion;
    }

    public function donacionesPorMes()
    {
        try {
            // Verificar sesión y obtener NIT si no es admin
            $this->verificarSesion();

            $data = $this->model->getDonacionesPorMes();

            echo json_encode([
                'data' => $data,
                'success' => true,
                'message' => 'Datos de donaciones por mes obtenidos correctamente'
            ]);
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode([
                'data' => [],
                'success' => false,
                'message' => 'Error al obtener datos: ' . $e->getMessage()
            ]);
        }
    }

    public function guardianesPorMes()
    {
        try {
            header('Content-Type: application/json');
            $data = $this->model->getGuardianesPorMes();

            echo json_encode([
                'data' => $data,
                'success' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function mascotasFelinasCaninas()
    {
        try {
            header('Content-Type: application/json');
            $data = $this->model->getMascotasFelinasCaninas();

            echo json_encode([
                'data' => $data,
                'success' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function publicacionesPorMes()
    {
        try {
            header('Content-Type: application/json');
            $data = $this->model->getPublicacionesPorMes();

            echo json_encode([
                'data' => $data,
                'success' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function mascotasPorEstado()
    {
        try {
            header('Content-Type: application/json');
            $data = $this->model->getMascotasPorEstado();

            echo json_encode([
                'data' => $data,
                'success' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function rankingFundaciones()
    {
        try {
            header('Content-Type: application/json');
            $data = $this->model->getRankingFundaciones();

            echo json_encode([
                'data' => $data,
                'success' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function tiposCausas()
    {
        try {
            header('Content-Type: application/json');
            $data = $this->model->getTiposCausas();

            echo json_encode([
                'data' => $data,
                'success' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function usuariosRegistrados()
    {
        try {
            header('Content-Type: application/json');
            $data = $this->model->getUsuariosRegistrados();

            echo json_encode([
                'data' => $data,
                'success' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new dashboardAdminController();
    $accion = $_GET['accion'] ?? '';

    switch ($accion) {
        case 'donacionesPorMes':
            $controller->donacionesPorMes();
            break;
        case 'guardianesPorMes':
            $controller->guardianesPorMes();
            break;
        case 'mascotasFelinasCaninas':
            $controller->mascotasFelinasCaninas();
            break;
        case 'publicacionesPorMes':
            $controller->publicacionesPorMes();
            break;
        case 'mascotasPorEstado':
            $controller->mascotasPorEstado();
            break;
        case 'rankingFundaciones':
            $controller->rankingFundaciones();
            break;
        case 'tiposCausas':
            $controller->tiposCausas();
            break;
        case 'usuariosRegistrados':
            $controller->usuariosRegistrados();
            break;
        default:
            header('HTTP/1.1 400 Bad Request');
            echo json_encode([
                'data' => [],
                'success' => false,
                'message' => 'Acción no válida'
            ]);
            break;
    }
}
