<?php

namespace App\controller\perfil;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/cloudinary.php';

use App\Model\Perfil\Perfil;
use Exception;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class PerfilController
{
    private $perfilModel;
    private $id_usuario;

    public function __construct()
    {
        $this->perfilModel = new Perfil();
        $this->id_usuario = $_SESSION["user"]["id_usuario"] ?? null;
    }

    /** Validar archivo de imagen */
    private function validateImage($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024;

        if (!in_array($file['type'], $allowedTypes)) {
            return ['valid' => false, 'error' => 'Tipo de archivo no permitido. Solo JPEG, PNG, GIF y WebP.'];
        }

        if ($file['size'] > $maxSize) {
            return ['valid' => false, 'error' => 'El archivo es demasiado grande. Máximo 5MB.'];
        }

        return ['valid' => true];
    }

    public function getPerfilPorId()
    {
        try {
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                throw new Exception('ID de perfil no proporcionado');
            }

            $idPerfil = (int)$_GET['id'];

            // Obtener perfil
            $perfil = $this->perfilModel->getPerfilPorIdPerfil($idPerfil);

            if (!$perfil) {
                throw new Exception('Perfil no encontrado');
            }

            // Usar id_usuario
            $perfil['mascotas'] = $this->perfilModel->mascotasFundacion($perfil['id_usuario']);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'perfil' => $perfil
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'success' => false,
                $e->getMessage()
            ]);
        }
    }



    public function editar()
    {
        try {
            header('Content-Type: application/json');

            $id = $_POST['id'] ?? $this->id_usuario;
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $preferencia = $_POST['preferencia'] ?? '';

            $redes_sociales = $_POST['redes_sociales'] ?? [];

            $perfilActual = $this->perfilModel->getPerfilPorUsuario($id);
            $imagen = $perfilActual['imagen'] ?? null;
            $public_id = $perfilActual['public_id'] ?? null;

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

                $validation = $this->validateImage($_FILES['imagen']);
                if (!$validation['valid']) {
                    echo json_encode(['success' => false,  $validation['error']]);
                    exit;
                }

                $uploadResult = uploadImageToCloudinary(
                    $_FILES['imagen']['tmp_name'],
                    'perfiles',
                    null
                );

                if ($uploadResult['success']) {

                    if (!empty($perfilActual['public_id'])) {
                        deleteImageFromCloudinary($perfilActual['public_id']);
                    }

                    $imagen = $uploadResult['url'];
                    $public_id = $uploadResult['public_id'];
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al subir imagen: ' . $uploadResult['error']]);
                    exit;
                }
            }

            if (empty($nombre) || empty($descripcion) || empty($preferencia)) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
                exit;
            }

            // Actualizar el perfil
            $resultadoGuardian = $this->perfilModel->actualizarPerfilGuardian($id, $nombre, $descripcion, $preferencia, $imagen, $redes_sociales, $public_id);
            $resultadoFundacion = $this->perfilModel->actualizarPerfilFundacion($id, $nombre, $descripcion, $preferencia, $imagen, $redes_sociales, $public_id);

            if ($resultadoGuardian || $resultadoFundacion) {
                echo json_encode([
                    'success' => true,
                    'Perfil actualizado correctamente.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'Error al actualizar el perfil.'
                ]);
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'Error: ' . $e->getMessage()
            ]);
        }
    }

    // Método para eliminar perfil
    public function eliminar()
    {
        try {
            header('Content-Type: application/json');

            $id = $_POST['id'] ?? $this->id_usuario;
            if (empty($id)) {
                echo json_encode(['success' => false, 'message' => 'ID de perfil no proporcionado']);
                return;
            }

            $perfilActual = $this->perfilModel->getPerfilPorUsuario($id);

            $resultado = $this->perfilModel->eliminarPerfil($id);

            // Si se eliminó correctamente, eliminar también de Cloudinary
            if ($resultado && !empty($perfilActual['public_id'])) {
                deleteImageFromCloudinary($perfilActual['public_id']);
            }

            echo json_encode([
                'success' => $resultado,
                $resultado ? 'Perfil eliminado correctamente' : 'Error al eliminar perfil'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'Error: ' . $e->getMessage()
            ]);
        }
    }
}

// Router de acciones 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $controller = new PerfilController();

    if ($accion === 'editar') {
        $controller->editar();
    } elseif ($accion === 'eliminar') {
        $controller->eliminar();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    $controller = new PerfilController();

    if ($action === 'getPerfilPorId') {
        $controller->getPerfilPorId();
    }
}
