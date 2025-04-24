<?php
require_once('../Modelo/ProductoModel.php');
require_once('../../Conexion.php');

if ($_POST) {
    $ModeloProducto = new Productos();
    $id = $_POST['Id'];
    $nombre = $_POST['Nombre'];
    $tipo = $_POST['TipoProducto'];
    $descripcion = $_POST['Descripcion'];
    $cantidad = $_POST['Cantidad'];
    $precio = $_POST['Precio'];
    $ModeloProducto->update($id, $nombre, $tipo, $descripcion, $cantidad, $precio);
}else{
    header("Location: ../../ProductoView.php");
}
?>