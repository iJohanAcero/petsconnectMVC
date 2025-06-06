<?php

require_once ('../../Model/producto/ProductoModel.php');

if($_POST){
    $ModeloProducto = new Productos();
    $Nombre=$_POST['nombre'];
    $tipo_producto=$_POST['tipo_producto'];
    $Descripcion=$_POST['descripcion'];
    $Precio=$_POST['precio'];
    $Cantidad=$_POST['cantidad_disponible'];

    $ModeloProducto->add($Nombre, $tipo_producto, $Descripcion, $Cantidad, $Precio);
}else{
    header("Location: view/producto/ProductoView.php");
}
?>