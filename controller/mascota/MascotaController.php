<?php

namespace App\controller\mascota;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/cloudinary.php'; // Incluir configuración de Cloudinary

use App\Model\Mascota\Mascota;
use Exception;

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
            $public_id
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

        // Verificar si se obtuvieron datos válidos
        if (!$mascotaActual || empty($mascotaActual)) {
            echo "No se pudieron obtener los datos de la mascota";
            return;
        }

        // Verificar si es un array de arrays (como [0 => array(...)])
        if (isset($mascotaActual[0]) && is_array($mascotaActual[0])) {
            $mascotaActual = $mascotaActual[0];
        }

        // Eliminar de la base de datos primero
        $resultado = $this->modeloMascota->delete($id_mascota);

        // Si se eliminó correctamente, eliminar también de Cloudinary
        if ($resultado) {
            // Verificar si existe public_id y no está vacío
            $public_id = $mascotaActual['public_id'] ?? ($mascotaActual['public_id'] ?? null);

            if (!empty($public_id)) {
                $deleteResult = deleteImageFromCloudinary($public_id);

                if ($deleteResult['result'] !== 'ok') {
                    error_log("Error al eliminar imagen de Cloudinary: " . print_r($deleteResult, true));
                }
            } else {
                error_log("No se encontró public_id para eliminar de Cloudinary");
            }
        }

        echo $resultado ? "Mascota eliminada correctamente" : "Error al eliminar mascota";
    }

    // 4️⃣ OBTENER todas las mascotas para carrusel/cartas
    public function getAllMascotasCarrusel()
    {
        try {
            $mascotas = $this->modeloMascota->getAllMascotasCarrusel();

            header('Content-Type: application/json');
            echo json_encode($mascotas);
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Error al obtener mascotas: ' . $e->getMessage()]);
            exit;
        }
    }

    // 5️⃣ OBTENER detalles de una mascota específica
    public function getDetallesMascota()
    {
        $id = $_GET['id'] ?? '';

        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'ID de mascota no proporcionado']);
            exit;
        }

        try {
            $mascota = $this->modeloMascota->getDetallesPorId($id);

            header('Content-Type: application/json');
            echo json_encode($mascota);
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Error al obtener detalles: ' . $e->getMessage()]);
            exit;
        }
    }

    // 7️⃣ FILTRAR mascotas por criterios específicos
    public function filtrarMascotas()
    {
        $especie = $_GET['especie'] ?? '';
        $tamano = $_GET['tamano'] ?? '';
        $edad = $_GET['edad'] ?? '';
        $genero = $_GET['genero'] ?? '';

        try {
            $mascotas = $this->modeloMascota->filtrarMascotas($especie, $tamano, $edad, $genero);

            header('Content-Type: application/json');
            echo json_encode($mascotas);
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Error al filtrar mascotas: ' . $e->getMessage()]);
            exit;
        }
    }

    // 8️⃣ OBTENER perfil completo de mascota con información de fundación
    public function getPerfilCompleto()
    {
        $id = $_GET['id'] ?? '';

        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'ID de mascota no proporcionado']);
            exit;
        }

        try {
            $perfil = $this->modeloMascota->getPerfilCompletoPorId($id);

            header('Content-Type: application/json');
            echo json_encode($perfil);
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Error al obtener perfil completo: ' . $e->getMessage()]);
            exit;
        }
    }
}

// Router de acciones - ACTUALIZADO para manejar GET y POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $controller = new MascotaController();

    if ($accion === 'registrar') {
        $controller->registrar();
    } elseif ($accion === 'editar') {
        $controller->editar();
    } elseif ($accion === 'eliminar') {
        $controller->eliminar();
    } elseif ($accion === 'solicitar_adopcion') {
        $controller->solicitarAdopcion();
    } elseif ($accion === 'toggle_favorito') {
        $controller->toggleFavorito();
    }
}

// NUEVO: Manejo de peticiones GET para AJAX
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    $controller = new MascotaController();

    if ($action === 'getAllMascotasCarrusel') {
        $controller->getAllMascotasCarrusel();
    } elseif ($action === 'getDetallesMascota') {
        $controller->getDetallesMascota();
    } elseif ($action === 'getInfoAdopcion') {
        $controller->getInfoAdopcion();
    } elseif ($action === 'filtrarMascotas') {
        $controller->filtrarMascotas();
    } elseif ($action === 'getPerfilCompleto') {
        $controller->getPerfilCompleto();
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Acción no válida']);
        exit;
    }
}