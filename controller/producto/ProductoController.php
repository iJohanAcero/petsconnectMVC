<?php
// Incluimos el modelo de productos
require_once('../../Model/producto/ProductoModel.php');

session_start(); // Para manejar mensajes entre redirecciones

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modeloProducto = new Productos();

    // 1️⃣ REGISTRAR
    if (isset($_POST['accion']) && $_POST['accion'] === 'registrar') {
        $nombre = $_POST['nombre'];
        $tipo_producto = $_POST['tipo_producto'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $cantidad_disponible = $_POST['cantidad_disponible'];

        $resultado = $modeloProducto->add($nombre, $tipo_producto, $descripcion, $precio, $cantidad_disponible);

        if ($resultado) {
            $_SESSION['mensaje'] = 'Producto registrado correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al registrar producto';
            $_SESSION['tipo_mensaje'] = 'error';
        }

        header("Location: ../../view/producto/ProductoView.php");
        exit;
    }

    // 2️⃣ ACTUALIZAR
    if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $tipo_producto = $_POST['tipo_producto'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $cantidad_disponible = $_POST['cantidad_disponible'];

        $resultado = $modeloProducto->update($id, $nombre, $tipo_producto, $descripcion, $precio, $cantidad_disponible);

        if ($resultado) {
            $_SESSION['mensaje'] = 'Producto actualizado correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al actualizar producto';
            $_SESSION['tipo_mensaje'] = 'error';
        }

        header("Location: ../../view/producto/ProductoView.php");
        exit;
    }

    // 3️⃣ ELIMINAR
    if (isset($_POST['eliminar']) && isset($_POST['id'])) {
        $id = $_POST['id'];
        $resultado = $modeloProducto->delete($id);

        if ($resultado) {
            $_SESSION['mensaje'] = 'Producto eliminado correctamente';
            $_SESSION['tipo_mensaje'] = 'success';
        } else {
            $_SESSION['mensaje'] = 'Error al eliminar producto';
            $_SESSION['tipo_mensaje'] = 'error';
        }

        header("Location: ../../view/producto/ProductoView.php");
        exit;
    }
}

// Si no es POST o no hay acción reconocida
header("Location: ../../view/producto/ProductoView.php");
exit;
