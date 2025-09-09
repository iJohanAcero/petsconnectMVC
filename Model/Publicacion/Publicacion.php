<?php
namespace App\Model\Publicacion;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Conexion;
use PDO;

class Publicacion
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    // Registrar nuevo Publicacion
    public function add($titulo, $contenido, $imagen, $fecha, $nit_fundacion, $public_id = null)
    {
        $statement = $this->db->prepare("INSERT INTO t_publicacion 
            (titulo, contenido, imagen, fecha, nit_fundacion, public_id)
            VALUES (:titulo, :contenido, :imagen, :fecha, :nit_fundacion, :public_id)");

        $statement->bindParam(':titulo', $titulo);
        $statement->bindParam(':contenido', $contenido);
        $statement->bindParam(':imagen', $imagen);
        $statement->bindParam(':fecha', $fecha);
        $statement->bindParam(':nit_fundacion', $nit_fundacion);
        $statement->bindParam(':public_id', $public_id);

        return $statement->execute();
    }

    // Método para obtener todos los Publicacion desde la base de datos
    public function getPublicacion()
    {
        $rows = null;
        $statement = $this->db->prepare(
            "SELECT p.*, f.nombre AS nombre_fundacion 
            FROM t_publicacion p
            INNER JOIN t_fundacion f ON p.nit_fundacion = f.nit_fundacion"
        );
        $statement->execute();
        while ($resultado = $statement->fetch()) {
            $rows[] = $resultado;
        }
        return $rows;
    }

    //Obtener Publicacion por ID 
    public function getId($id)
    {
        $statement = $this->db->prepare("SELECT * FROM t_publicacion WHERE id_publicacion = :id");
        $statement->bindParam(':id', $id);
        $statement->execute();
        
        // Devolver un solo registro
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    //Método para actualizar - ahora incluye imagen
    public function update($id, $titulo, $contenido, $imagen, $public_id = null)
    {
        $statement = $this->db->prepare("UPDATE t_publicacion 
        SET titulo = :titulo, contenido = :contenido, imagen = :imagen, public_id = :public_id
        WHERE id_publicacion = :id");

        $statement->bindParam(':id', $id);
        $statement->bindParam(':titulo', $titulo);
        $statement->bindParam(':contenido', $contenido);
        $statement->bindParam(':imagen', $imagen);
        $statement->bindParam(':public_id', $public_id);

        return $statement->execute();
    }

    // Eliminar Publicacion
    public function delete($id)
    {
        $statement = $this->db->prepare("DELETE FROM t_publicacion WHERE id_publicacion = :id");
        $statement->bindParam(':id', $id);

        return $statement->execute();
    }

    public function getPublicacionesRecientes($limit, $offset)
    {
        $statement = $this->db->prepare(
            "SELECT p.*, 
                f.nombre AS nombre_fundacion, 
                pr.imagen AS imagen_fundacion,
                pr.descripcion AS descripcion_fundacion
         FROM t_publicacion p
         INNER JOIN t_fundacion f ON p.nit_fundacion = f.nit_fundacion
         LEFT JOIN t_perfil pr ON f.id_perfil = pr.id_perfil
         ORDER BY p.fecha DESC 
         LIMIT :limit OFFSET :offset"
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

    public function getPublicacionesPorFundacion($nit_fundacion)
    {
        $sql = "SELECT * FROM t_publicacion WHERE nit_fundacion = :nit";
        $statement = $this->db->prepare($sql);
        $statement->bindParam(':nit', $nit_fundacion);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}