<?php

require_once ('../../Model/producto/ProductoModel.php');

if($_POST){
    $ModeloProducto = new Productos();
    $Nombre=$_POST['nombre'];
    $TipoProducto=$_POST['TipoProducto'];
    $Descripcion=$_POST['descripcion'];
    $Precio=$_POST['precio'];
    $Cantidad=$_POST['cantidad_disponible'];

    $ModeloProducto->add($Nombre, $TipoProducto, $Descripcion, $Cantidad, $Precio);
}else{
    header("Location: view/producto/ProductoView.php");
}
?>