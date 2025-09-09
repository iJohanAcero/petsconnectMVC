<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Informe\Informe;
use App\Model\Fundacion\Fundacion;
use App\Config\Roles;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class InformeController
{
    private $model;

    public function __construct()
    {
        $this->model = new Informe();
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
                 'Usuario no autenticado'
            ]);
            exit;
        }

        // Verificar si es admin 
        $esAdmin = Roles::esAdmin($id_usuario);
        if ($esAdmin) {
            return null;
        }

        // Si no es admin, obtener NIT de la fundación
        $nit_fundacion = Fundacion::obtenerNitPorUsuario($id_usuario);

        if (!$nit_fundacion) {
            header('HTTP/1.1 403 Forbidden');
            echo json_encode([
                'data' => [],
                'success' => false,
                 'Usuario sin fundación asignada'
            ]);
            exit;
        }

        return $nit_fundacion;
    }

    public function mascotasAdultas()
    {
        try {
            $nitFundacion = $this->verificarSesion();
            header('Content-Type: application/json');
            $data = $this->model->getMascotasAdultas($nitFundacion);
            echo json_encode([
                'data' => $data,
                'success' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'data' => [],
                'success' => false,
                 $e->getMessage()
            ]);
        }
    }

    public function mascotasPopulares()
    {
        try {
            $nitFundacion = $this->verificarSesion();
            header('Content-Type: application/json');
            $data = $this->model->getMascotasPopulares($nitFundacion);
            echo json_encode([
                'data' => $data,
                'success' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'data' => [],
                'success' => false,
                 $e->getMessage()
            ]);
        }
    }

    public function causasProgreso()
    {
        try {
            $nitFundacion = $this->verificarSesion();
            header('Content-Type: application/json');
            $data = $this->model->getCausasProgreso($nitFundacion);
            echo json_encode([
                'data' => $data,
                'success' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'data' => [],
                'success' => false,
                 $e->getMessage()
            ]);
        }
    }

    // metodos para informes de administradores
    public function donacionFundacion()
    {
        try {
            header('Content-Type: application/json');
            $nitFundacion = $this->verificarSesion();

            $data = $this->model->getDonacionFundacion($nitFundacion);

            echo json_encode([
                'data' => $data,
                'success' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'data' => [],
                'success' => false,
                 $e->getMessage()
            ]);
        }
    }

    public function adopcionEspecie()
    {
        try {
            header('Content-Type: application/json');
            $nitFundacion = $this->verificarSesion();

            $data = $this->model->getAdopcionEspecie($nitFundacion);

            echo json_encode([
                'data' => $data,
                'success' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'data' => [],
                'success' => false,
                 $e->getMessage()
            ]);
        }
    }

    public function publicacionesFundacion() {
    try {
        header('Content-Type: application/json');
        $data = $this->model->getPublicacionesFundacion();

        echo json_encode([
            'data' => $data,
            'success' => true
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'data' => [],
            'success' => false,
             $e->getMessage()
        ]);
    }
}
}

// Router
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new informeController();
    $accion = $_GET['accion'] ?? '';

    switch ($accion) {
        case 'mascotas_adultas':
            $controller->mascotasAdultas();
            break;
        case 'mascotas_populares':
            $controller->mascotasPopulares();
            break;
        case 'causas_donaciones':
            $controller->causasProgreso();
            break;
        case 'donacion_fundacion':
            $controller->donacionFundacion();
            break;
        case 'adopcion_especie':
            $controller->adopcionEspecie();
            break;
        case 'publicaciones_fundacion':
            $controller->publicacionesFundacion();
            break;
        default:
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Acción no válida']);
    }
}
