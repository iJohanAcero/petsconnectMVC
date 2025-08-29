<?php

namespace App\Controller\publicacion;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/cloudinary.php';

use App\Model\Publicacion\Publicacion;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class PublicacionController
{
    private $modeloPublicacion;

    public function __construct()
    {
        $this->modeloPublicacion = new Publicacion();
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

    public function recientes()
    {
        // Validar y asignar página
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;

        // Instanciar modelo
        $modeloPublicacion = new Publicacion();

        // Obtener publicaciones recientes con paginación
        $publicaciones = $modeloPublicacion->getPublicacionesRecientes($limit, $offset);

        // Enviar resultado
        echo json_encode($publicaciones);
    }

    public function registrar()
    {
        $titulo = $_POST['titulo'] ?? '';
        $contenido = $_POST['contenido'] ?? '';
        $imagen = null;
        $public_id = null;

        // Procesar imagen si se sube
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            // Validar imagen
            $validation = $this->validateImage($_FILES['imagen']);
            if (!$validation['valid']) {
                echo "Error: " . $validation['error'];
                return;
            }

            // Subir a Cloudinary usando la función global
            $uploadResult = uploadImageToCloudinary(
                $_FILES['imagen']['tmp_name'],
                'publicaciones',
                null // public_id automático
            );

            if ($uploadResult['success']) {
                $imagen = $uploadResult['url'];
                $public_id = $uploadResult['public_id'];
            } else {
                echo "Error al subir imagen: " . $uploadResult['error'];
                return;
            }
        }

        $fecha = date('Y-m-d H:i:s');
        $nit_fundacion = $_POST['nit_fundacion'] ?? '';

        $resultado = $this->modeloPublicacion->add(
            $titulo,
            $contenido,
            $imagen,
            $fecha,
            $nit_fundacion,
            $public_id
        );

        echo $resultado ? "Publicacion registrada correctamente" : "Error al registrar publicacion";
    }

    public function editar()
    {
        $id = $_POST['id'] ?? null;
        $titulo = $_POST['titulo'] ?? '';
        $contenido = $_POST['contenido'] ?? '';

        // Obtener la publicacion actual para conservar datos existentes
        $publicacionActual = $this->modeloPublicacion->getId($id);
        $imagen = $publicacionActual['imagen']; // Mantener la imagen actual por defecto
        $public_id = $publicacionActual['public_id'] ?? null; // Mantener public_id actual

        // Procesar nueva imagen si se sube
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            // Validar imagen
            $validation = $this->validateImage($_FILES['imagen']);
            if (!$validation['valid']) {
                echo "Error: " . $validation['error'];
                return;
            }

            // Subir nueva imagen a Cloudinary usando la función global
            $uploadResult = uploadImageToCloudinary(
                $_FILES['imagen']['tmp_name'],
                'publicaciones',
                null
            );

            if ($uploadResult['success']) {
                // Eliminar imagen anterior de Cloudinary si existe
                if (!empty($publicacionActual['public_id'])) {
                    deleteImageFromCloudinary($publicacionActual['public_id']);
                }

                $imagen = $uploadResult['url'];
                $public_id = $uploadResult['public_id'];
            } else {
                echo "Error al subir imagen: " . $uploadResult['error'];
                return;
            }
        }

        $resultado = $this->modeloPublicacion->update(
            $id,
            $titulo,
            $contenido,
            $imagen,
            $public_id
        );

        echo $resultado ? "Publicación actualizada correctamente" : "Error al actualizar publicación";
    }

    public function eliminar()
    {
        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            echo "ID de publicación no proporcionado";
            return;
        }

        // Obtener datos de la publicación antes de eliminar para limpiar Cloudinary
        $publicacionActual = $this->modeloPublicacion->getId($id);

        // Eliminar de la base de datos
        $resultado = $this->modeloPublicacion->delete($id);

        // Si se eliminó correctamente, eliminar también de Cloudinary
        if ($resultado && !empty($publicacionActual['public_id'])) {
            deleteImageFromCloudinary($publicacionActual['public_id']);
        }

        echo $resultado ? "Publicación eliminada correctamente" : "Error al eliminar Publicación";
    }
}

// Router de acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $controller = new PublicacionController();

    if ($accion === 'registrar') {
        $controller->registrar();
    } elseif ($accion === 'editar') {
        $controller->editar();
    } elseif ($accion === 'eliminar') {
        $controller->eliminar();
    }
}
