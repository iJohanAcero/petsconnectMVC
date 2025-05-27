<?php
require_once('../Modelo/ProductoModel.php');

if ($_POST) {
    $ModeloProducto = new Productos();
    $id = $_POST['Id'];
    $ModeloProducto->delete($id);
}else{
    header("Location: ../../ProductoView.php");
}
?>