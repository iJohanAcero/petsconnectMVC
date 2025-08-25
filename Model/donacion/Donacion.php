<?php

namespace App\Model\Donacion;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Conexion;
use PDO;

class Donacion
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    
    // Registrar nueva donacion
    public function add($nombre, $descripcion, $meta, $estado_causa, $fecha_creacion, $nit_fundacion, $imagen_url, $tipo_causa, $public_id = null)
    {
        $statement = $this->db->prepare("INSERT INTO t_donacion 
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
}