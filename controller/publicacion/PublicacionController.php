<?php

namespace App\Controller\publicacion;
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Publicacion\Publicacion;


class PublicacionController
{

    
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

        // Procesar imagen si viene
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreImagen = uniqid() . '_' . $_FILES['imagen']['name'];
            $rutaDestino = __DIR__ . '/../Public/images/eventos_fundacion/' . $nombreImagen;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino);
            $imagen = $nombreImagen;
        }

        $fecha = date('Y-m-d H:i:s');
        $nit_fundacion = $_POST['nit_fundacion'] ?? '';

        $modeloPublicacion = new Publicacion();
        $resultado = $modeloPublicacion->add($titulo, $contenido, $imagen, $fecha, $nit_fundacion);

        echo $resultado ? "Publicacion registrada correctamente" : "Error al registrar publicacion";
    }

    public function editar()
    {
        $id = $_POST['id'] ?? null;
        $titulo = $_POST['titulo'] ?? '';
        $contenido = $_POST['contenido'] ?? '';

        if (!$id) {
            echo "ID no proporcionado";
            return;
        }

        $modeloPublicacion = new Publicacion();
        $resultado = $modeloPublicacion->update($id, $titulo, $contenido);

        echo $resultado ? "Publicacion actualizada correctamente" : "Error al actualizar publicacion";
    }

    public function eliminar()
    {
        $id = htmlspecialchars($_POST['id'] ?? '');

        if (empty($id)) {
            echo "ID de publicación no proporcionado";
            return;
        }

        $modeloPublicacion = new Publicacion();
        $resultado = $modeloPublicacion->delete($id);

        echo $resultado ? "Publicación eliminada correctamente" : "Error al eliminar publicación";
    }


}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $controller = new PublicacionController();

    if ($accion === 'editar') {
        $controller->editar();
    } elseif ($accion === 'registrar') {
        $controller->registrar();
    } elseif ($accion === 'eliminar') {
        $controller->eliminar();
    }
}