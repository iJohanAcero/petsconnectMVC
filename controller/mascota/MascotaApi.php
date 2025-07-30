<?php
namespace App\controller\mascota;
use App\Model\Mascota\Mascota;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $modeloMascota = new Mascota();
    $mascotas = $modeloMascota->getMascotas();
    header('Content-Type: application/json');
    echo json_encode($mascotas);
    exit;
}