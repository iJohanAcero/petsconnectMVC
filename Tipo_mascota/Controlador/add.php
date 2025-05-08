<?php

require_once ('../Modelo/TipoMascotaModel.php');

if($_POST){
    $ModeloTipoMascota = new TipoMascota();
    $especie=$_POST['especie'];
    $raza=$_POST['raza'];

    $ModeloTipoMascota->add($especie, $raza);
}else{
    header("Location: ../Pages/Tipo_mascota_view.php");
}
?>