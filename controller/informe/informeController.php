<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Model\Informe\Informe;

class InformeController {
    private $model;

    public function __construct() {
        $this->model = new Informe();
    }

    public function mascotasAdultas() {
        try {
            header('Content-Type: application/json');
            $data = $this->model->getMascotasAdultas();
            echo json_encode([
                'data' => $data,
                'success' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'data' => [],
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function mascotasPopulares() {
        try {
            header('Content-Type: application/json');
            $data = $this->model->getMascotasPopulares();
            echo json_encode([
                'data' => $data,
                'success' => true
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'data' => [],
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}

// Router
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new informeController();
    $accion = $_GET['accion'] ?? '';
    
    switch($accion) {
        case 'mascotas_adultas':
            $controller->mascotasAdultas();
            break;
        case 'mascotas_populares':
            $controller->mascotasPopulares();
            break;
        default:
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => 'Acción no válida']);
    }
}