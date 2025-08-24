<?php

namespace App\controller\mascota;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/cloudinary.php'; // Incluir configuración de Cloudinary

use App\Model\Mascota\Mascota;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class MascotaController
{
    private $modeloMascota;

    public function __construct()
    {
        $this->modeloMascota = new Mascota();
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

    // 1️⃣ REGISTRAR mascota
    public function registrar()
    {
        $id_mascota = $_POST['id_mascota'] ?? null;
        $nombre = $_POST['nombre'] ?? '';
        $edad_meses = $_POST['edad_meses'] ?? '';
        $sexo = $_POST['sexo'] ?? '';
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
                'mascotas', // carpeta específica para mascotas
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

        $id_tipo_mascota = $_POST['id_tipo_mascota'] ?? '';
        $id_estado_adopcion = $_POST['id_estado_adopcion'] ?? '';
        $nit_fundacion = $_POST['nit_fundacion'] ?? '';

        // Validación básica
        if (empty($id_tipo_mascota)) {
            echo "Error: Debes seleccionar un tipo de mascota válido";
            return;
        }

        $resultado = $this->modeloMascota->add(
            $id_mascota,
            $nombre,
            $edad_meses,
            $sexo,
            $imagen,
            $id_tipo_mascota,
            $nit_fundacion,
            $id_estado_adopcion,
            $public_id // Agregar public_id
        );

        echo $resultado ? "Mascota registrada correctamente" : "Error al registrar mascota";
    }

    // 2️⃣ ACTUALIZAR mascota
    public function editar()
    {
        $id_mascota = $_POST['id_mascota'] ?? null;
        $nombre = $_POST['nombre'] ?? '';
        $edad_meses = $_POST['edad_meses'] ?? '';
        $sexo = $_POST['sexo'] ?? '';

        // Obtener la mascota actual para conservar datos existentes
        $mascotaActual = $this->modeloMascota->getId($id_mascota);
        $imagen = $mascotaActual['imagen']; // Mantener la imagen actual por defecto
        $public_id = $mascotaActual['public_id'] ?? null; // Mantener public_id actual

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
                'mascotas',
                null // Nuevo public_id automático
            );

            if ($uploadResult['success']) {
                // Eliminar imagen anterior de Cloudinary si existe
                if (!empty($mascotaActual['public_id'])) {
                    deleteImageFromCloudinary($mascotaActual['public_id']);
                }

                $imagen = $uploadResult['url'];
                $public_id = $uploadResult['public_id'];
            } else {
                echo "Error al subir imagen: " . $uploadResult['error'];
                return;
            }
        }

        $id_tipo_mascota = $_POST['id_tipo_mascota'] ?? null;
        $id_estado_adopcion = $_POST['id_estado_adopcion'] ?? null;

        $resultado = $this->modeloMascota->update(
            $id_mascota,
            $nombre,
            $edad_meses,
            $sexo,
            $imagen,
            $id_tipo_mascota,
            $id_estado_adopcion,
            $public_id // Agregar public_id
        );

        echo $resultado ? "Mascota actualizada correctamente" : "Error al actualizar mascota";
    }

    // 3️⃣ ELIMINAR mascota 
    public function eliminar()
    {
        $id_mascota = $_POST['id_mascota'] ?? '';
        if (empty($id_mascota)) {
            echo "ID de mascota no proporcionado";
            return;
        }

        // Obtener datos de la mascota antes de eliminar para limpiar Cloudinary
        $mascotaActual = $this->modeloMascota->getId($id_mascota);

        // Eliminar de la base de datos
        $resultado = $this->modeloMascota->delete($id_mascota);

        // Si se eliminó correctamente, eliminar también de Cloudinary
        if ($resultado && !empty($mascotaActual['public_id'])) {
            deleteImageFromCloudinary($mascotaActual['public_id']);
        }

        echo $resultado ? "Mascota eliminada correctamente" : "Error al eliminar mascota";
    }
}

// Router de acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $controller = new MascotaController();

    if ($accion === 'registrar') {
        $controller->registrar();
    } elseif ($accion === 'editar') {
        $controller->editar();
    } elseif ($accion === 'eliminar') {
        $controller->eliminar();
    }
}