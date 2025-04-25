<?php

require_once __DIR__ . "/../Model/conexion.php";

function esAdmin($id_usuario) {
    $db = (new Conexion())->getConexion();
    $stmt = $db->prepare("SELECT 1 FROM t_administrador WHERE id_usuario = ? LIMIT 1");
    $stmt->execute([$id_usuario]);
    return $stmt->rowCount() > 0;
}

function esGuardian($id_usuario) {
    $db = (new Conexion())->getConexion();
    $stmt = $db->prepare("SELECT 1 FROM t_guardian WHERE id_usuario = ? LIMIT 1");
    $stmt->execute([$id_usuario]);
    return $stmt->rowCount() > 0;
}
