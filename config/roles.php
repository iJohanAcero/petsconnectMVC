<?php
namespace App\config\roles;
// -------------------------------------
// CONFIGURACIÓN DE ROLES DE USUARIO
// -------------------------------------
use App\Model\Conexion;

// Funciones para verificar roles de usuario
namespace App\config;

use App\Model\Conexion;

class Roles {
    public static function esAdmin($id_usuario) {
        $db = (new Conexion())->getConexion();
        $stmt = $db->prepare("SELECT 1 FROM t_administrador WHERE id_usuario = ? LIMIT 1");
        $stmt->execute([$id_usuario]);
        return $stmt->rowCount() > 0;
    }

    public static function esGuardian($id_usuario) {
        $db = (new Conexion())->getConexion();
        $stmt = $db->prepare("SELECT 1 FROM t_guardian WHERE id_usuario = ? LIMIT 1");
        $stmt->execute([$id_usuario]);
        return $stmt->rowCount() > 0;
    }

    public static function esFundacion($id_usuario) {
        $db = (new Conexion())->getConexion();
        $stmt = $db->prepare("SELECT 1 FROM t_fundacion WHERE id_usuario = ? LIMIT 1");
        $stmt->execute([$id_usuario]);
        return $stmt->rowCount() > 0;
    }
}