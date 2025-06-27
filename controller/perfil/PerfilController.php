<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../../Model/perfil/PerfilModel.php');


$perfilModel = new PerfilModel();
$id_usuario = $_SESSION["user"]["id_usuario"];

$perfil = $perfilModel->getPerfilPorUsuario($id_usuario);

?>