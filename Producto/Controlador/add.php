<?php
require_once ('../Modelo/ProductoModel.php');

if($_POST){
    $ModeloProducto = new Productos();
    $Nombre=$_POST['Nombre'];
    $TipoProducto=$_POST['TipoProducto'];
    $Descripcion=$_POST['Descripcion'];
    $Precio=$_POST['Precio'];
    $Cantidad=$_POST['Cantidad'];

    $ModeloProducto->add($Nombre, $TipoProducto, $Descripcion, $Cantidad, $Precio);
}else{
    header("Location: ../Pages/ProductoView.php");
}
?>