<?php

namespace App\controller\causa;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/cloudinary.php';

use App\Model\Causa\Causa;
use Exception;

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

    /** Validar archivo de imagen
     */
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


        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

            $validation = $this->validateImage($_FILES['imagen']);
            if (!$validation['valid']) {
                echo "Error: " . $validation['error'];
                return;
            }


            $uploadResult = uploadImageToCloudinary(
                $_FILES['imagen']['tmp_name'],
                'causas',
                null
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


        $causaActual = $this->modeloCausa->getId($id_causa);
        $imagen_url = $causaActual['imagen_url'];
        $public_id = $causaActual['public_id'] ?? null;

        // Procesar nueva imagen si se sube
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {

            $validation = $this->validateImage($_FILES['imagen']);
            if (!$validation['valid']) {
                echo "Error: " . $validation['error'];
                return;
            }

            // Subir nueva imagen a Cloudinary
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

        // eliminar también de Cloudinary
        if ($resultado && !empty($causaActual['public_id'])) {
            deleteImageFromCloudinary($causaActual['public_id']);
        }

        echo $resultado ? "Causa eliminada correctamente" : "Error al eliminar Causa";
    }

    // ✅ MÉTODO CORREGIDO
    public function getAllCausasCarrusel()
    {
        try {
            // ✅ Agregamos logs para debug
            error_log("Método getAllCausasCarrusel ejecutado");

            $causas = $this->modeloCausa->getAllCausasCarrusel();

            error_log("Número de causas encontradas: " . count($causas));

            // ✅ Limpiar cualquier output previo
            ob_clean();

            header('Content-Type: application/json');
            echo json_encode($causas);
            exit;
        } catch (Exception $e) {
            error_log("Error en getAllCausasCarrusel: " . $e->getMessage());

            // ✅ Limpiar output previo
            ob_clean();

            header('Content-Type: application/json');
            echo json_encode(['error' => 'Error al obtener causas: ' . $e->getMessage()]);
            exit;
        }
    }

    public function obtenerDetalleCausa()
    {
        try {
            $id_causa = $_POST['id_causa'] ?? '';

            if (empty($id_causa)) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'ID de causa no proporcionado']);
                exit;
            }

            // Usar la nueva función que trae todo con JOIN
            $detalleCompleto = $this->modeloCausa->getDetallesPorId($id_causa);

            if (!$detalleCompleto) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Causa no encontrada']);
                exit;
            }

            // Separar los datos de causa y fundación
            $causa = [
                'id_causa' => $detalleCompleto['id_causa'],
                'nombre' => $detalleCompleto['nombre'],
                'descripcion' => $detalleCompleto['descripcion'],
                'meta' => $detalleCompleto['meta'],
                'estado_causa' => $detalleCompleto['estado_causa'],
                'fecha_creacion' => $detalleCompleto['fecha_creacion'],
                'nit_fundacion' => $detalleCompleto['nit_fundacion'],
                'imagen_url' => $detalleCompleto['imagen_url'],
                'tipo_causa' => $detalleCompleto['tipo_causa'],
                'public_id' => $detalleCompleto['public_id']
            ];

            $fundacion = [
                'nombre' => $detalleCompleto['nombre_fundacion'],
                'nit_fundacion' => $detalleCompleto['nit_fundacion'],
                'imagen_url' => $detalleCompleto['fundacion_imagen'],
                'public_id' => $detalleCompleto['fundacion_public_id']
            ];

            $respuesta = [
                'causa' => $causa,
                'fundacion' => $fundacion
            ];

            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit;
        } catch (Exception $e) {
            error_log("Error en obtenerDetalleCausa: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Error interno: ' . $e->getMessage()]);
            exit;
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $controller = new CausaController();

    if ($accion === 'getAllCausasCarrusel') {
        $controller->getAllCausasCarrusel();
    } elseif ($accion === 'obtenerDetalleCausa') {  // ✅ NUEVA LÍNEA
        $controller->obtenerDetalleCausa();          // ✅ NUEVA LÍNEA
    } elseif ($accion === 'registrar') {
        $controller->registrar();
    } elseif ($accion === 'editar') {
        $controller->editar();
    } elseif ($accion === 'eliminar') {
        $controller->eliminar();
    }
}
