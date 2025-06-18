<?php

require_once ('../../Model/producto/ProductoModel.php');

if($_POST){
    $ModeloProducto = new Productos();
    $Nombre=$_POST['nombre'];
    $Tipo_producto=$_POST['tipo_producto'];
    $Descripcion=$_POST['descripcion'];    $Cantidad=$_POST['cantidad_disponible'];
    $Precio=$_POST['precio'];

    $ModeloProducto->add($Nombre, $Tipo_producto, $Descripcion, $Cantidad, $Precio);
}else{
    header("Location: view/producto/ProductoView.php");
}
?>