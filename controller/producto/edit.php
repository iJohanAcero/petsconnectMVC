<?php
require_once('../Modelo/ProductoModel.php');
require_once('../../Model/conexion.php');

if ($_POST) {
    $ModeloProducto = new Productos();
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo_producto'];
    $descripcion = $_POST['descripcion'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $ModeloProducto->update($id, $nombre, $tipo, $descripcion, $cantidad, $precio);
}else{
    header("Location: ../../ProductoView.php");
}
?>