<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\dashboard\Dashboard;
use App\Model\Fundacion\Fundacion;
use App\Config\Roles;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class dashboardFundacionController
{
    private $model;

    public function __construct()
    {
        $this->model = new Dashboard();
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

    public function donacionesPorCausa()
    {
        header('Content-Type: application/json'); // Siempre al inicio

        try {
            $nit = $this->verificarSesion();
            if ($nit === null) { // admin
                echo json_encode([
                    'data' => [],
                    'success' => false,
                    'message' => 'Este recurso es solo para fundaciones'
                ]);
                return;
            }

            $data = $this->model->getDonacionesPorCausa($nit);
            echo json_encode([
                'data' => $data,
                'success' => true,
                'message' => 'Datos de donaciones por causa obtenidos correctamente'
            ], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo json_encode([
                'data' => [],
                'success' => false,
                'message' => 'Error al obtener datos: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    public function publicacionesPorMes()
    {
        header('Content-Type: application/json'); // Siempre al inicio

        try {
            $nit = $this->verificarSesion();
            if ($nit === null) { // admin
                echo json_encode([
                    'data' => [],
                    'success' => false,
                    'message' => 'Este recurso es solo para fundaciones'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $data = $this->model->getPublicacionesPorMes($nit);
            echo json_encode([
                'data' => $data,
                'success' => true,
                'message' => 'Datos de publicaciones por mes obtenidos correctamente'
            ], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo json_encode([
                'data' => [],
                'success' => false,
                'message' => 'Error al obtener datos: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    public function adopcionesPorEspecie()
    {
        header('Content-Type: application/json'); // Siempre al inicio

        try {
            $nit = $this->verificarSesion();
            if ($nit === null) { // admin no puede
                echo json_encode([
                    'data' => [],
                    'success' => false,
                    'message' => 'Este recurso es solo para fundaciones'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $data = $this->model->getAdopcionesPorEspecie($nit);
            echo json_encode([
                'data' => $data,
                'success' => true,
                'message' => 'Datos de adopciones por especie obtenidos correctamente'
            ], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo json_encode([
                'data' => [],
                'success' => false,
                'message' => 'Error al obtener datos: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    public function causasActivasPorTipo()
    {
        header('Content-Type: application/json'); // Siempre al inicio

        try {
            $nit = $this->verificarSesion();
            if ($nit === null) { // admin no accede
                echo json_encode([
                    'data' => [],
                    'success' => false,
                    'message' => 'Este recurso es solo para fundaciones'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $data = $this->model->getCausasActivasPorTipo($nit);
            echo json_encode([
                'data' => $data,
                'success' => true,
                'message' => 'Datos de causas activas por tipo obtenidos correctamente'
            ], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo json_encode([
                'data' => [],
                'success' => false,
                'message' => 'Error al obtener datos: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    public function mascotasAdoptadasPorMes()
    {
        header('Content-Type: application/json'); // Siempre al inicio

        try {
            $nit = $this->verificarSesion();
            if ($nit === null) { // admin no accede
                echo json_encode([
                    'data' => [],
                    'success' => false,
                    'message' => 'Este recurso es solo para fundaciones'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $data = $this->model->getMascotasAdoptadasPorMes($nit);
            echo json_encode([
                'data' => $data,
                'success' => true,
                'message' => 'Datos de mascotas adoptadas por mes obtenidos correctamente'
            ], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo json_encode([
                'data' => [],
                'success' => false,
                'message' => 'Error al obtener datos: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    public function donacionesPorMes()
{
    header('Content-Type: application/json'); // Siempre al inicio

    try {
        $nit = $this->verificarSesion();
        if ($nit === null) { // admin no accede
            echo json_encode([
                'data' => [],
                'success' => false,
                'message' => 'Este recurso es solo para fundaciones'
            ], JSON_PRETTY_PRINT);
            return;
        }

        $data = $this->model->getDonacionesPorMesFundacion($nit);
        echo json_encode([
            'data' => $data,
            'success' => true,
            'message' => 'Datos de donaciones por mes obtenidos correctamente'
        ], JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        echo json_encode([
            'data' => [],
            'success' => false,
            'message' => 'Error al obtener datos: ' . $e->getMessage()
        ], JSON_PRETTY_PRINT);
    }
}

}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new dashboardFundacionController();
    $accion = $_GET['accion'] ?? '';

    switch ($accion) {
        case 'donacionesPorCausa':
            $controller->donacionesPorCausa();
            break;
        case 'publicacionesPorMes':
            $controller->publicacionesPorMes();
            break;
        case 'adopcionesPorEspecie':
            $controller->adopcionesPorEspecie();
            break;
        case 'causasActivasPorTipo':
            $controller->causasActivasPorTipo();
            break;
        case 'mascotasAdoptadasPorMes':
            $controller->mascotasAdoptadasPorMes();
            break;
        case 'donacionesPorMes':
            $controller->donacionesPorMes();
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
