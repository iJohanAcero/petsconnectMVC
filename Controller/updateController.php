<?php
require_once __DIR__ . '/../Model/updateModel.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_mascota = $_POST['id_mascota'];
    $nombre = $_POST['nombre'];
    $edad_meses = $_POST['edad_meses'];
    $sexo = $_POST['sexo'];
    $imagen = $_POST['imagen'];
    $id_tipo_mascota = $_POST['id_tipo_mascota'];
    $nit_fundacion = $_POST['nit_fundacion'];
    $num_serie_vacuna = $_POST['num_serie_vacuna'];

    $modelo = new mascotasModel();
    $modelo->actualizarMascotas($id_mascota, $nombre, $edad_meses, $sexo, $imagen, $id_tipo_mascota, $nit_fundacion, $num_serie_vacuna);

    header("Location: ../Views/Pages/index.php");
    exit;
}

