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
    public function getPerfilPorUsuario($id)
    {
        // Buscar en guardian y traer también el id_usuario
        $stmt = $this->db->prepare("
            SELECT p.*, g.id_usuario
            FROM t_guardian g
            INNER JOIN t_perfil p ON g.id_perfil = p.id_perfil
            WHERE g.id_usuario = :id_usuario
            LIMIT 1
        ");
        $stmt->bindParam(':id_usuario', $id, PDO::PARAM_INT);
        $stmt->execute();
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($perfil) {
            return $perfil;
        }
        // Buscar en fundacion si no es guardian y traer también el id_usuario
        $stmt = $this->db->prepare("
            SELECT p.*, f.id_usuario
            FROM t_fundacion f
            INNER JOIN t_perfil p ON f.id_perfil = p.id_perfil
            WHERE f.id_usuario = :id_usuario
            LIMIT 1
        ");
        $stmt->bindParam(':id_usuario', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Actualizar perfil de guardian
    public function actualizarPerfilGuardian($id, $nombre, $descripcion, $preferencia, $imagen)
    {
        $stmt = $this->db->prepare("
            UPDATE t_perfil 
            SET nombre = :nombre, descripcion = :descripcion, preferencia = :preferencia, imagen = :imagen
            WHERE id_perfil = (
                SELECT id_perfil FROM t_guardian WHERE id_usuario = :id_usuario
            )
        ");
        $stmt->bindParam(':id_usuario', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':preferencia', $preferencia, PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
        
        return $stmt->execute();
    }

    public function actualizarPerfilFundacion($id, $nombre, $descripcion, $preferencia, $imagen)
    {
        $stmt = $this->db->prepare("
            UPDATE t_perfil 
            SET nombre = :nombre, descripcion = :descripcion, preferencia = :preferencia, imagen = :imagen
            WHERE id_perfil = (
                SELECT id_perfil FROM t_fundacion WHERE id_usuario = :id_usuario
            )
        ");
        $stmt->bindParam(':id_usuario', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':preferencia', $preferencia, PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
        
        return $stmt->execute();
    }
}