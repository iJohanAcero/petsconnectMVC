<!-- AGREGAR -->
<!-- <?php

// require_once('../Modelo/FundacionModel.php');

// if ($_POST) {
//     $ModeloFundacion = new Fundacion();
//     $nit_fundacion = $_POST['nit_fundacion'];
//     $nombre_fundacion = $_POST['nombre_fundacion'];
//     $id_usuario = $_POST['id_usuario'];
//     $id_perfil = $_POST['id_perfil'];

//     $ModeloFundacion->add($nit_fundacion, $nombre_fundacion, $id_usuario, $id_perfil);
// } else {
//     header("Location: ../../view/fundacion/FundacionView.php");
// }
?>


<!-- ACTUALIZAR -->
<!-- <?php
// require_once('../../Model/FundacionModel.php');
// require_once('../../Model/conexion.php');

// if ($_POST) {
//     $ModeloFundacion = new Fundacion();
//     $nit_fundacion = $_POST['nit_fundacion'];
//     $nombre_fundacion = $_POST['nombre_fundacion'];
//     $id_usuario = $_POST['id_usuario'];
//     $id_perfil = $_POST['id_perfil'];
    
//     $ModeloFundacion->update($nit_fundacion, $nombre_fundacion, $id_usuario, $id_perfil);
// } else {
//     header("Location: ../../view/fundacion/FundacionView.php");
// }
?>


<!-- ELIMINAR -->
<!-- <?php 
// require_once('../Modelo/ProductoModel.php');

// if ($_POST) {
//     $ModeloProducto = new Productos();
//     $id = $_POST['Id'];
//     $ModeloProducto->delete($id);
// } else {
//     header("Location: ../../ProductoView.php");
// }
?>
<?php

require_once('../../Model/FundacionModel.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $modelo = new Fundacion();

    if ($action === 'add') {
        $modelo->add($_POST['nit_fundacion'], $_POST['nombre_fundacion'], $_POST['id_usuario'], $_POST['id_perfil']);
    } elseif ($action === 'update') {
        $modelo->update($_POST['nit_fundacion'], $_POST['nombre_fundacion'], $_POST['id_usuario'], $_POST['id_perfil']);
    } elseif ($action === 'delete') {
        $modelo->delete($_POST['Id']);
    } else {
        header("Location: ../../view/fundacion/FundacionView.php");
    }
} else {
    header("Location: ../../view/fundacion/FundacionView.php");
}
