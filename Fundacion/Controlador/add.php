<?php

require_once ('../Modelo/FundacionModel.php');

if($_POST){
    $ModeloFundacion= new Fundacion();
    $nit_fundacion=$_POST['nit_fundacion'];
    $id_usuario=$_POST['id_usuario'];
    $id_perfil=$_POST['id_perfil'];

    $ModeloFundacion->add($nit_fundacion, $id_usuario, $id_perfil);
}else{
    header("Location: ../Pages/Fundacion_view.php");
}
?>