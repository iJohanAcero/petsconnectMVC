<?php
require_once('../../Model/mascota/MascotaModel.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $modeloMascota = new Mascota();
    $mascotas = $modeloMascota->getMascotas();
    header('Content-Type: application/json');
    echo json_encode($mascotas);
    exit;
}