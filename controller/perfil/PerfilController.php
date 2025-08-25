<?php

namespace App\controller\perfil;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/cloudinary.php'; // Incluir configuración de Cloudinary

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

    /**
     * Validar archivo de imagen
     */
    private function validateImage($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            return ['valid' => false, 'error' => 'Tipo de archivo no permitido. Solo JPEG, PNG, GIF y WebP.'];
        }

        if ($file['size'] > $maxSize) {
            return ['valid' => false, 'error' => 'El archivo es demasiado grande. Máximo 5MB.'];
        }

        return ['valid' => true];
    }

    // MÉTODO NUEVO para obtener perfil por ID (para el modal)
    public function getPerfilPorId()
    {
        try {
            // Verificar que se recibió el ID
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                throw new Exception('ID de perfil no proporcionado');
            }

            $idPerfil = (int)$_GET['id'];

            // Obtener los datos del perfil usando el método que ya tienes
            $perfil = $this->perfilModel->getPerfilPorIdPerfil($idPerfil);

            if (!$perfil) {
                throw new Exception('Perfil no encontrado');
            }

            // Devolver respuesta JSON exitosa
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'perfil' => $perfil
            ]);
        } catch (Exception $e) {
            // Devolver respuesta JSON con error
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function editar()
    {
        try {
            // Asegurar que la respuesta sea JSON
            header('Content-Type: application/json');

            $id = $_POST['id'] ?? $this->id_usuario;
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $preferencia = $_POST['preferencia'] ?? '';

            // Obtener redes sociales del formulario
            $redes_sociales = $_POST['redes_sociales'] ?? [];

            // Obtener perfil actual para conservar datos existentes
            $perfilActual = $this->perfilModel->getPerfilPorUsuario($id);
            $imagen = $perfilActual['imagen'] ?? null;
            $public_id = $perfilActual['public_id'] ?? null;

            // Procesar imagen si se sube
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                // Validar imagen
                $validation = $this->validateImage($_FILES['imagen']);
                if (!$validation['valid']) {
                    echo json_encode(['success' => false, 'message' => $validation['error']]);
                    exit;
                }

                // Subir a Cloudinary usando la función global
                $uploadResult = uploadImageToCloudinary(
                    $_FILES['imagen']['tmp_name'],
                    'perfiles', // carpeta específica para perfiles
                    null // public_id automático
                );

                if ($uploadResult['success']) {
                    // Eliminar imagen anterior de Cloudinary si existe
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

            // Validar campos obligatorios
            if (empty($nombre) || empty($descripcion) || empty($preferencia)) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
                exit;
            }

            // Actualizar el perfil - necesitarías modificar tu modelo para aceptar public_id
            $resultadoGuardian = $this->perfilModel->actualizarPerfilGuardian($id, $nombre, $descripcion, $preferencia, $imagen, $redes_sociales, $public_id);
            $resultadoFundacion = $this->perfilModel->actualizarPerfilFundacion($id, $nombre, $descripcion, $preferencia, $imagen, $redes_sociales, $public_id);

            if ($resultadoGuardian || $resultadoFundacion) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Perfil actualizado correctamente.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al actualizar el perfil.'
                ]);
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    // Método para eliminar perfil (si es necesario)
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
                'message' => $resultado ? 'Perfil eliminado correctamente' : 'Error al eliminar perfil'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}

// Router de acciones MEJORADO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $controller = new PerfilController();

    if ($accion === 'editar') {
        $controller->editar();
    } elseif ($accion === 'eliminar') {
        $controller->eliminar();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // NUEVO: Manejar peticiones GET para el modal
    $action = $_GET['action'] ?? '';
    $controller = new PerfilController();

    if ($action === 'getPerfilPorId') {
        $controller->getPerfilPorId();
    }
}