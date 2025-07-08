<?php
// Se requiere el archivo de conexión con la base de datos

require_once '../../Model/conexion.php';

class Causa
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    public function add($nombre, $descripcion, $meta, $estado_causa, $nit_fundacion, $imagen_url, $tipo_causa)
    {
        $sql = "INSERT INTO causas (nombre, descripcion, meta, estado_causa, nit_fundacion, imagen_url, tipo_causa)
                VALUES (:nombre, :descripcion, :meta, :estado_causa, :nit_fundacion, :imagen_url, :tipo_causa)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':meta', $meta);
        $stmt->bindParam(':estado_causa', $estado_causa);
        $stmt->bindParam(':nit_fundacion', $nit_fundacion);
        $stmt->bindParam(':imagen_url', $imagen_url);
        $stmt->bindParam(':tipo_causa', $tipo_causa);
        return $stmt->execute();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM causas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id_causa)
    {
        $stmt = $this->db->prepare("SELECT * FROM causas WHERE id_causa = :id_causa");
        $stmt->bindParam(':id_causa', $id_causa);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id_causa, $nombre, $descripcion, $meta, $estado_causa, $nit_fundacion, $imagen_url, $tipo_causa)
    {
        $sql = "UPDATE causas SET nombre = :nombre, descripcion = :descripcion, meta = :meta, estado_causa = :estado_causa,
                nit_fundacion = :nit_fundacion, imagen_url = :imagen_url, tipo_causa = :tipo_causa WHERE id_causa = :id_causa";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_causa', $id_causa);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':meta', $meta);
        $stmt->bindParam(':estado_causa', $estado_causa);
        $stmt->bindParam(':nit_fundacion', $nit_fundacion);
        $stmt->bindParam(':imagen_url', $imagen_url);
        $stmt->bindParam(':tipo_causa', $tipo_causa);
        return $stmt->execute();
    }

    public function delete($id_causa)
    {
        $stmt = $this->db->prepare("DELETE FROM causas WHERE id_causa = :id_causa");
        $stmt->bindParam(':id_causa', $id_causa);
        return $stmt->execute();
    }
}
