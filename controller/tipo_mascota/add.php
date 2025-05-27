<?php

require_once ('../../Model/tipo_mascota/TipoMascotaModel.php');

if($_POST){
    $ModeloTipoMascota = new TipoMascota();
    $especie=$_POST['especie'];
    $raza=$_POST['raza'];

    $ModeloTipoMascota->add($especie, $raza);
}else{
    header("Location: view/tipo_mascota/Tipo_mascota_view.php");
}
?>