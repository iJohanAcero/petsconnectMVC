<?php
require_once('../../Model/publicacion/PublicacionModel.php');

session_start(); // Para manejar mensajes entre redirecciones

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modeloPublicacion = new Publicacion();

    // 1️⃣ REGISTRAR producto
    if (isset($_POST['accion']) && $_POST['accion'] === 'registrar') {
        $titulo = $_POST['titulo'];
        $contenido = $_POST['contenido'];
        $imagen = $_POST['imagen'];
        $fecha = date('Y-m-d H:i:s');
        $nit_fundacion = $_POST['nit_fundacion'];

        $resultado = $modeloPublicacion->add($titulo, $contenido, $imagen, $fecha, $nit_fundacion);

        if ($resultado) {
            echo "Publicacion registrada correctamente";
        } else {
            echo "Error al registrar publicacion";
        }
        exit;
    }

    // 2️⃣ ACTUALIZAR producto
    if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $tipo_producto = $_POST['tipo_producto'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $cantidad_disponible = $_POST['cantidad_disponible'];

        $resultado = $modeloProducto->update($id, $nombre, $tipo_producto, $descripcion, $precio, $cantidad_disponible);

        if ($resultado) {
            echo "Producto actualizado correctamente";
        } else {
            echo "Error al actualizar producto";
        }
        exit;
    }

    // 3️⃣ ELIMINAR producto
    if (isset($_POST['eliminar']) && isset($_POST['id'])) {
        $id = $_POST['id'];
        $resultado = $modeloProducto->delete($id);

        if ($resultado) {
            echo "Producto eliminado correctamente";
        } else {
            echo "Error al eliminar producto";
        }
        exit;
    }
}

// Si no es POST o no hay acción válida
echo "Acción no válida o método no permitido";
exit;
