<?php
require_once(__DIR__ . '/../conexion.php');

class PerfilModel
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    // Obtener todos los datos del perfil de un guardian por su id_usuario
    public function getPerfilPorUsuario($id_usuario)
    {
        $stmt = $this->db->prepare("
            SELECT p.*
            FROM t_guardian g
            INNER JOIN t_perfil p ON g.id_perfil = p.id_perfil
            WHERE g.id_usuario = :id_usuario
            LIMIT 1
        ");
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($perfil) {
        return $perfil;
    }
    // Buscar en fundacion si no es guardian
    $stmt = $this->db->prepare("
        SELECT p.*
        FROM t_fundacion f
        INNER JOIN t_perfil p ON f.id_perfil = p.id_perfil
        WHERE f.id_usuario = :id_usuario
        LIMIT 1
    ");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}