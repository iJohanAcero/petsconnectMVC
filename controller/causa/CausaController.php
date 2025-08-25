<?php

namespace App\controller\causa;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/cloudinary.php';
use App\Model\Causa\Causa;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class CausaController
{
    private $modeloCausa;

    public function __construct()
    {
        $this->modeloCausa = new Causa();
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

    public function registrar()
    {
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $meta = $_POST['meta'] ?? '';
        $estado_causa = $_POST['estado_causa'] ?? '';
        $fecha_creacion = date('Y-m-d H:i:s');
        $nit_fundacion = $_POST['nit_fundacion'] ?? '';
        $tipo_causa = $_POST['tipo_causa'] ?? '';
        $imagen_url = null;
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
                'causas',
                null // public_id automático
            );

            if ($uploadResult['success']) {
                $imagen_url = $uploadResult['url'];
                $public_id = $uploadResult['public_id'];
            } else {
                echo "Error al subir imagen: " . $uploadResult['error'];
                return;
            }
        }

        $resultado = $this->modeloCausa->add(
            $nombre,
            $descripcion,
            $meta,
            $estado_causa,
            $fecha_creacion,
            $nit_fundacion,
            $imagen_url,
            $tipo_causa,
            $public_id
        );

        echo $resultado ? "Causa registrada correctamente" : "Error al registrar causa";
    }

    public function editar()
    {
        $id_causa = $_POST['id_causa'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $meta = $_POST['meta'] ?? '';
        $estado_causa = $_POST['estado_causa'] ?? '';
        $nit_fundacion = $_POST['nit_fundacion'] ?? '';
        $tipo_causa = $_POST['tipo_causa'] ?? '';

        // Obtener la causa actual para conservar datos existentes
        $causaActual = $this->modeloCausa->getId($id_causa);
        $imagen_url = $causaActual['imagen_url']; // Mantener la imagen actual por defecto
        $public_id = $causaActual['public_id'] ?? null; // Mantener public_id actual

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
                'causas',
                null 
            );

            if ($uploadResult['success']) {
                // Eliminar imagen anterior de Cloudinary si existe
                if (!empty($causaActual['public_id'])) {
                    deleteImageFromCloudinary($causaActual['public_id']);
                }

                $imagen_url = $uploadResult['url'];
                $public_id = $uploadResult['public_id'];
            } else {
                echo "Error al subir imagen: " . $uploadResult['error'];
                return;
            }
        }

        $resultado = $this->modeloCausa->update(
            $id_causa,
            $nombre,
            $descripcion,
            $meta,
            $estado_causa,
            $nit_fundacion,
            $imagen_url,
            $tipo_causa,
            $public_id 
        );

        echo $resultado ? "Causa actualizada correctamente" : "Error al actualizar causa";
    }

    public function eliminar()
    {
        $id_causa = $_POST['id_causa'] ?? '';
        if (empty($id_causa)) {
            echo "ID de causa no proporcionado";
            return;
        }

        // Obtener datos de la causa antes de eliminar para limpiar Cloudinary
        $causaActual = $this->modeloCausa->getId($id_causa);
        
        // Eliminar de la base de datos
        $resultado = $this->modeloCausa->delete($id_causa);
        
        // Si se eliminó correctamente, eliminar también de Cloudinary
        if ($resultado && !empty($causaActual['public_id'])) {
            deleteImageFromCloudinary($causaActual['public_id']);
        }

        echo $resultado ? "Causa eliminada correctamente" : "Error al eliminar Causa";
    }
}

// Router de acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $controller = new CausaController();

    if ($accion === 'registrar') {
        $controller->registrar();
    } elseif ($accion === 'editar') {
        $controller->editar();
    } elseif ($accion === 'eliminar') {
        $controller->eliminar();
    }
}