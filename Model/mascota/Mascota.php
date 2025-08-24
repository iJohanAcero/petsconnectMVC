<?php
// Requiere la conexión a la base de datos
namespace App\Model\Mascota;

use App\Model\Conexion;
use PDO;
use PDOException;
use Exception;

class Mascota
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    // 🟢 Agregar nueva mascota 
    public function add($id_mascota, $nombre, $edad_meses, $sexo, $imagen, $id_tipo_mascota, $nit_fundacion, $id_estado_adopcion, $public_id = null)
    {
        $statement = $this->db->prepare("INSERT INTO t_mascota
            (id_mascota, nombre, edad_meses, sexo, imagen, id_tipo_mascota, nit_fundacion, id_estado_adopcion, public_id)
            VALUES (:id_mascota, :nombre, :edad_meses, :sexo, :imagen, :id_tipo_mascota, :nit_fundacion, :id_estado_adopcion, :public_id)");

        $statement->bindParam(':id_mascota', $id_mascota);
        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':edad_meses', $edad_meses);
        $statement->bindParam(':sexo', $sexo);
        $statement->bindParam(':imagen', $imagen);
        $statement->bindParam(':id_tipo_mascota', $id_tipo_mascota);
        $statement->bindParam(':nit_fundacion', $nit_fundacion);
        $statement->bindParam(':id_estado_adopcion', $id_estado_adopcion);
        $statement->bindParam(':public_id', $public_id);

        return $statement->execute();
    }

    // 🟡 Obtener todas las mascotas con JOINs para mostrar datos legibles
    public function getMascota()
    {
        $sql = "SELECT 
                    m.id_mascota,
                    m.nombre,
                    m.edad_meses,
                    m.sexo,
                    m.imagen,
                    m.nit_fundacion,
                    m.id_tipo_mascota,
                    m.id_estado_adopcion,
                    m.public_id,
                    tm.especie,
                    ea.tipo_estado
                FROM t_mascota m
                LEFT JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                LEFT JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion";
        
        error_log("Consulta SQL ejecutada: " . $sql);  // Para depuración
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🟠 Obtener mascota por ID (CORREGIDO - devuelve un solo array)
    public function getId($id)
    {
        $sql = "SELECT 
                    m.*, 
                    tm.especie,
                    ea.tipo_estado
                FROM t_mascota m
                INNER JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
                WHERE m.id_mascota = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // ✅ CORREGIDO: Devuelve un solo registro, no array de arrays
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 🔵 Actualizar mascota
    public function update($id_mascota, $nombre, $edad_meses, $sexo, $imagen, $id_tipo_mascota, $id_estado_adopcion, $public_id = null)
    {
        $statement = $this->db->prepare("UPDATE t_mascota SET
        nombre = :nombre,
        edad_meses = :edad_meses,
        sexo = :sexo,
        imagen = :imagen,
        id_tipo_mascota = :id_tipo_mascota,
        id_estado_adopcion = :id_estado_adopcion,
        public_id = :public_id
        WHERE id_mascota = :id_mascota");

        $statement->bindParam(':id_mascota', $id_mascota);
        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':edad_meses', $edad_meses);
        $statement->bindParam(':sexo', $sexo);
        $statement->bindParam(':imagen', $imagen);
        $statement->bindParam(':id_tipo_mascota', $id_tipo_mascota);
        $statement->bindParam(':id_estado_adopcion', $id_estado_adopcion);
        $statement->bindParam(':public_id', $public_id);

        return $statement->execute();
    }

    // 🔴 Eliminar mascota 
    public function delete($id_mascota)
    {
        $stmt = $this->db->prepare("DELETE FROM t_mascota WHERE id_mascota = :id");
        $stmt->bindParam(':id', $id_mascota);

        return $stmt->execute();
    }

    // 🟣 Obtener todos los tipos de mascota (para llenar selects)
    public function getTiposMascota()
    {
        $rows = null;
        $stmt = $this->db->prepare("SELECT * FROM t_tipo_mascota");
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $resultado;
        }

        return $rows;
    }

    public function getMascotasPorFundacion($nit_fundacion)
    {
        $sql = "SELECT 
                    m.id_mascota,
                    m.nombre,
                    m.edad_meses,
                    m.sexo,
                    m.imagen,
                    m.nit_fundacion,
                    m.public_id,
                    tm.especie,
                    ea.tipo_estado
                FROM t_mascota m
                LEFT JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                LEFT JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
                WHERE m.nit_fundacion = :nit";
        $statement = $this->db->prepare($sql);
        $statement->bindParam(':nit', $nit_fundacion, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🟤 Obtener todos los estados de adopción (para el select)
    public function getEstadosAdopcion()
    {
        $rows = [];
        $stmt = $this->db->prepare("SELECT * FROM t_estado_adopcion");
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $resultado;
        }

        return $rows;
    }

    // ⚪ Obtener todos los NIT de fundaciones (solo para admin)
    public function getNitsFundacion()
    {
        $rows = [];

        $stmt = $this->db->prepare("SELECT nit_fundacion FROM t_fundacion");
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $resultado; // ✅ Deja el resultado completo como arreglo asociativo
        }

        return $rows;
    }
}