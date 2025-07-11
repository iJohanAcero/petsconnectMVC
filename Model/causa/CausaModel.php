<?php
// Se requiere el archivo de conexión con la base de datos

require_once(__DIR__ . '/../conexion.php');

class Causa
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    // Registrar nueva Causa
    public function add($nombre, $descripcion, $meta, $estado_causa, $fecha_creacion, $nit_fundacion, $imagen_url, $tipo_causa)
    {
        $statement = $this->db->prepare("INSERT INTO t_causa 
            (nombre, descripcion, meta, estado_causa, fecha_creacion, nit_fundacion, imagen_url, tipo_causa)
            VALUES (:nombre, :descripcion, :meta, :estado_causa, :fecha_creacion, :nit_fundacion, :imagen_url, :tipo_causa)");

        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':descripcion', $descripcion);
        $statement->bindParam(':meta', $meta);
        $statement->bindParam(':estado_causa', $estado_causa);
        $statement->bindParam(':fecha_creacion', $fecha_creacion);
        $statement->bindParam(':nit_fundacion', $nit_fundacion);
        $statement->bindParam(':imagen_url', $imagen_url);
        $statement->bindParam(':tipo_causa', $tipo_causa);

        // ❌ Ya NO se redirige, solo se devuelve true/false
        return $statement->execute();
    }

    // Método para obtener todas las Causas desde la base de datos
    public function getCausa()
    {
        $rows = [];
        $statement = $this->db->prepare("SELECT id_causa, nombre, descripcion, meta, estado_causa, fecha_creacion, nit_fundacion, imagen_url, tipo_causa FROM t_causa");
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

    // Método para actualizar un Causa usando su ID
    // Reemplaza el método update con este:
    public function update($id_causa, $nombre, $descripcion, $meta, $estado_causa, $nit_fundacion, $imagen_url, $tipo_causa)
    {
        $statement = $this->db->prepare("UPDATE t_causa SET
        nombre = :nombre,
        descripcion = :descripcion,
        meta = :meta,
        estado_causa = :estado_causa,
        nit_fundacion = :nit_fundacion,
        imagen_url = :imagen_url,
        tipo_causa = :tipo_causa
        WHERE id_causa = :id_causa");

        $statement->bindParam(':id_causa', $id_causa);
        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':descripcion', $descripcion);
        $statement->bindParam(':meta', $meta);
        $statement->bindParam(':estado_causa', $estado_causa);
        $statement->bindParam(':nit_fundacion', $nit_fundacion);
        $statement->bindParam(':imagen_url', $imagen_url);
        $statement->bindParam(':tipo_causa', $tipo_causa);

        return $statement->execute();
    }

    // Eliminar causa
    public function delete($id)
    {
        $statement = $this->db->prepare("DELETE FROM t_causa WHERE id_causa = :id");
        $statement->bindParam(':id', $id);

        return $statement->execute(); // Devuelve true o false
    }




    // MOSTRAR causa RECIENTES EN EL INICIO
    public function getcausaRecientes($limit, $offset)
    {
        $statement = $this->db->prepare(
            "SELECT p.*, f.nombre AS nombre_fundacion 
         FROM t_causa p
         INNER JOIN t_fundacion f ON p.nit_fundacion = f.nit_fundacion
         ORDER BY p.meta DESC LIMIT :limit OFFSET :offset"
        );
        $statement->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $statement->execute();

        $rows = [];
        while ($resultado = $statement->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $resultado;
        }
        return $rows;
    }
}
