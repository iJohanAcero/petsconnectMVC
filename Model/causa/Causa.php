<?php

namespace App\Model\Causa;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\conexion;
use PDO;

class Causa
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    // Registrar nueva Causa
    public function add($nombre, $descripcion, $meta, $estado_causa, $fecha_creacion, $nit_fundacion, $imagen_url, $tipo_causa, $public_id = null)
    {
        $statement = $this->db->prepare("INSERT INTO t_causa 
            (nombre, descripcion, meta, estado_causa, fecha_creacion, nit_fundacion, imagen_url, tipo_causa, public_id)
            VALUES (:nombre, :descripcion, :meta, :estado_causa, :fecha_creacion, :nit_fundacion, :imagen_url, :tipo_causa, :public_id)");

        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':descripcion', $descripcion);
        $statement->bindParam(':meta', $meta);
        $statement->bindParam(':estado_causa', $estado_causa);
        $statement->bindParam(':fecha_creacion', $fecha_creacion);
        $statement->bindParam(':nit_fundacion', $nit_fundacion);
        $statement->bindParam(':imagen_url', $imagen_url);
        $statement->bindParam(':tipo_causa', $tipo_causa);
        $statement->bindParam(':public_id', $public_id);

        return $statement->execute();
    }

    // Método para obtener todas las Causas desde la base de datos
    public function getCausa()
    {
        $rows = [];
        $statement = $this->db->prepare("SELECT id_causa, nombre, descripcion, meta, estado_causa, fecha_creacion, nit_fundacion, imagen_url, tipo_causa, public_id FROM t_causa");
        $statement->execute();
        while ($resultado = $statement->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $resultado;
        }
        return $rows;
    }

    // Obtener causa por ID
    public function getId($id)
    {
        $statement = $this->db->prepare("SELECT * FROM t_causa WHERE id_causa = :id");
        $statement->bindParam(':id', $id);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // Método para actualizar una Causa usando su ID
    public function update($id_causa, $nombre, $descripcion, $meta, $estado_causa, $nit_fundacion, $imagen_url, $tipo_causa, $public_id = null)
    {
        $statement = $this->db->prepare("UPDATE t_causa SET
        nombre = :nombre,
        descripcion = :descripcion,
        meta = :meta,
        estado_causa = :estado_causa,
        nit_fundacion = :nit_fundacion,
        imagen_url = :imagen_url,
        tipo_causa = :tipo_causa,
        public_id = :public_id
        WHERE id_causa = :id_causa");

        $statement->bindParam(':id_causa', $id_causa);
        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':descripcion', $descripcion);
        $statement->bindParam(':meta', $meta);
        $statement->bindParam(':estado_causa', $estado_causa);
        $statement->bindParam(':nit_fundacion', $nit_fundacion);
        $statement->bindParam(':imagen_url', $imagen_url);
        $statement->bindParam(':tipo_causa', $tipo_causa);
        $statement->bindParam(':public_id', $public_id);

        return $statement->execute();
    }

    // Eliminar causa
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM t_causa WHERE id_causa = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            // Verifica si es un error de clave foránea
            if ($e->getCode() == "23000") {
                throw new \Exception("No se puede eliminar esta causa porque tiene donaciones asociadas.");
            }
            throw $e; // otros errores se lanzan normalmente
        }
    }

    public function getCausasPorFundacion($nit_fundacion)
    {
        $sql = "SELECT * FROM t_causa WHERE nit_fundacion = :nit";
        $statement = $this->db->prepare($sql);
        $statement->bindParam(':nit', $nit_fundacion, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCausasCarrusel()
    {
        $sql = "SELECT 
                c.id_causa,
                c.nombre,
                c.descripcion,
                c.meta,
                c.estado_causa,
                c.fecha_creacion,
                c.nit_fundacion,
                c.imagen_url,
                c.tipo_causa,
                f.nombre AS nombre_fundacion
            FROM t_causa c
            INNER JOIN t_fundacion f 
                ON c.nit_fundacion = f.nit_fundacion
            WHERE c.estado_causa = 'ACTIVA'
            ORDER BY c.fecha_creacion DESC
            LIMIT 5";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getDetallesPorId($id)
    {
        $sql = "SELECT
                c.*,
                f.nombre as nombre_fundacion,
                f.nit_fundacion,
                u.nombre as rep_nombre,
                u.apellido as rep_apellido,
                u.telefono as rep_telefono,
                u.email as rep_email,
                p.descripcion as fundacion_descripcion,
                p.imagen as fundacion_imagen,
                p.public_id as fundacion_public_id
            FROM t_causa c
            INNER JOIN t_fundacion f ON c.nit_fundacion = f.nit_fundacion
            INNER JOIN t_usuario u ON f.id_usuario = u.id_usuario
            INNER JOIN t_perfil p ON f.id_perfil = p.id_perfil
            WHERE c.id_causa = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
