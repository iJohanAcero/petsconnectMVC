<?php
namespace App\controller\causa;
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

        // Procesar imagen si se sube
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreImagen = uniqid() . '_' . basename($_FILES['imagen']['name']);
            $rutaDestino = __DIR__ . '/../../Public/images/causa/' . $nombreImagen;
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                $imagen_url = $nombreImagen;
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
            $tipo_causa
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
        $imagen_url = $_POST['imagen_url'] ?? null;

        // Procesar nueva imagen si se sube
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreImagen = uniqid() . '_' . basename($_FILES['imagen']['name']);
            $rutaDestino = __DIR__ . '/../../Public/images/causa/' . $nombreImagen;
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                $imagen_url = $nombreImagen;
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
            $tipo_causa
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
        $resultado = $this->modeloCausa->delete($id_causa);
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