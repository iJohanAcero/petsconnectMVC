<!-- AGREGAR -->
<?php

require_once('../Modelo/FundacionModel.php');

if ($_POST) {
    $ModeloFundacion = new Fundacion();
    $nit_fundacion = $_POST['nit_fundacion'];
    $id_usuario = $_POST['id_usuario'];
    $id_perfil = $_POST['id_perfil'];

    $ModeloFundacion->add($nit_fundacion, $id_usuario, $id_perfil);
} else {
    header("Location: ../Pages/Fundacion_view.php");
}
?>


<!-- ACTUALIZAR -->
<?php
require_once('../Modelo/ProductoModel.php');
require_once('../../Model/conexion.php');

if ($_POST) {
    $ModeloProducto = new Productos();
    $id = $_POST['Id'];
    $nombre = $_POST['Nombre'];
    $tipo = $_POST['TipoProducto'];
    $descripcion = $_POST['Descripcion'];
    $cantidad = $_POST['Cantidad'];
    $precio = $_POST['Precio'];
    $ModeloProducto->update($id, $nombre, $tipo, $descripcion, $cantidad, $precio);
} else {
    header("Location: ../../ProductoView.php");
}
?>


<!-- ELIMINAR -->
<?php
require_once('../Modelo/ProductoModel.php');

if ($_POST) {
    $ModeloProducto = new Productos();
    $id = $_POST['Id'];
    $ModeloProducto->delete($id);
} else {
    header("Location: ../../ProductoView.php");
}
?>