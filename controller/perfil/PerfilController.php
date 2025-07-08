<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../../Model/perfil/PerfilModel.php');


$perfilModel = new PerfilModel();
$id = $_SESSION["user"]["id_usuario"];

$perfil = $perfilModel->getPerfilPorUsuario($id);

?>