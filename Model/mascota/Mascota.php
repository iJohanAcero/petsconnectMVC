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

    //Agregar nueva mascota 
    public function add($nombre, $edad_meses, $sexo, $imagen, $id_tipo_mascota, $nit_fundacion, $id_estado_adopcion, $numero_chip = null, $public_id = null)
    {
        $statement = $this->db->prepare("INSERT INTO t_mascota
        (nombre, edad_meses, sexo, imagen, id_tipo_mascota, nit_fundacion, id_estado_adopcion, numero_chip, public_id)
        VALUES (:nombre, :edad_meses, :sexo, :imagen, :id_tipo_mascota, :nit_fundacion, :id_estado_adopcion, :numero_chip, :public_id)");

        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':edad_meses', $edad_meses);
        $statement->bindParam(':sexo', $sexo);
        $statement->bindParam(':imagen', $imagen);
        $statement->bindParam(':id_tipo_mascota', $id_tipo_mascota);
        $statement->bindParam(':nit_fundacion', $nit_fundacion);
        $statement->bindParam(':id_estado_adopcion', $id_estado_adopcion);
        $statement->bindParam(':numero_chip', $numero_chip);
        $statement->bindParam(':public_id', $public_id);

        return $statement->execute();
    }


    //Obtener todas las mascotas con JOINs para mostrar datos legibles
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
                m.numero_chip,
                m.public_id,
                tm.especie,
                ea.tipo_estado
            FROM t_mascota m
            LEFT JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
            LEFT JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    //Obtener mascota por ID (CORREGIDO - devuelve un solo array)
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

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar mascota
    public function update($id_mascota, $nombre, $edad_meses, $sexo, $imagen, $id_tipo_mascota, $id_estado_adopcion, $numero_chip = null, $public_id = null)
    {
        $statement = $this->db->prepare("UPDATE t_mascota SET
        nombre = :nombre,
        edad_meses = :edad_meses,
        sexo = :sexo,
        imagen = :imagen,
        id_tipo_mascota = :id_tipo_mascota,
        id_estado_adopcion = :id_estado_adopcion,
        numero_chip = :numero_chip,
        public_id = :public_id
        WHERE id_mascota = :id_mascota");

        $statement->bindParam(':id_mascota', $id_mascota, PDO::PARAM_INT);
        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':edad_meses', $edad_meses);
        $statement->bindParam(':sexo', $sexo);
        $statement->bindParam(':imagen', $imagen);
        $statement->bindParam(':id_tipo_mascota', $id_tipo_mascota);
        $statement->bindParam(':id_estado_adopcion', $id_estado_adopcion);
        $statement->bindParam(':numero_chip', $numero_chip);
        $statement->bindParam(':public_id', $public_id);

        return $statement->execute();
    }

    // Eliminar mascota 
    public function delete($id_mascota)
    {
        $stmt = $this->db->prepare("DELETE FROM t_mascota WHERE id_mascota = :id");
        $stmt->bindParam(':id', $id_mascota);

        return $stmt->execute();
    }

    // Obtener todas las mascotas para carrusel/cartas
    public function getAllMascotasCarrusel()
    {
        $sql = "SELECT 
                    m.id_mascota,
                    m.nombre,
                    m.edad_meses,
                    m.sexo,
                    m.imagen,
                    m.public_id,
                    tm.especie,
                    ea.tipo_estado,
                    f.nombre as nombre_fundacion,
                    f.nit_fundacion,
                    CASE 
                        WHEN m.edad_meses < 12 THEN 'cachorro'
                        WHEN m.edad_meses BETWEEN 12 AND 36 THEN 'joven'
                        WHEN m.edad_meses BETWEEN 37 AND 84 THEN 'adulto'
                        ELSE 'senior'
                    END as categoria_edad
                FROM t_mascota m
                INNER JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
                INNER JOIN t_fundacion f ON m.nit_fundacion = f.nit_fundacion
                WHERE ea.tipo_estado IN ('EN ADOPCION', 'TRANSITO')
                ORDER BY m.id_mascota DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener detalles específicos de una mascota
    public function getDetallesPorId($id)
    {
        $sql = "SELECT 
                    m.*,
                    tm.especie,
                    ea.tipo_estado,
                    f.nombre as nombre_fundacion,
                    f.nit_fundacion,
                    u.nombre as rep_nombre,
                    u.apellido as rep_apellido,
                    u.telefono as rep_telefono,
                    u.email as rep_email,
                    p.descripcion as fundacion_descripcion,
                    p.imagen as fundacion_imagen,
                    CASE 
                        WHEN m.edad_meses < 12 THEN 'cachorro'
                        WHEN m.edad_meses BETWEEN 12 AND 36 THEN 'joven'
                        WHEN m.edad_meses BETWEEN 37 AND 84 THEN 'adulto'
                    END as categoria_edad
                FROM t_mascota m
                INNER JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
                INNER JOIN t_fundacion f ON m.nit_fundacion = f.nit_fundacion
                INNER JOIN t_usuario u ON f.id_usuario = u.id_usuario
                INNER JOIN t_perfil p ON f.id_perfil = p.id_perfil
                WHERE m.id_mascota = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // Filtrar mascotas por criterios
    public function filtrarMascotas($especie = '', $edad = '', $genero = '')
    {
        $sql = "SELECT 
                    m.id_mascota,
                    m.nombre,
                    m.edad_meses,
                    m.sexo,
                    m.imagen,
                    m.public_id,
                    tm.especie,
                    ea.tipo_estado,
                    f.nombre as nombre_fundacion,
                    f.nit_fundacion,
                    CASE 
                        WHEN m.edad_meses < 12 THEN 'cachorro'
                        WHEN m.edad_meses BETWEEN 12 AND 36 THEN 'joven'
                        WHEN m.edad_meses BETWEEN 37 AND 84 THEN 'adulto'
                        ELSE 'senior'
                    END as categoria_edad
                FROM t_mascota m
                INNER JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
                INNER JOIN t_fundacion f ON m.nit_fundacion = f.nit_fundacion
                WHERE ea.tipo_estado IN ('EN ADOPCION', 'TRANSITO')";

        $params = [];

        // Filtro por especie
        if (!empty($especie)) {
            if ($especie === 'perro') {
                $sql .= " AND tm.especie = 'Canino'";
            } elseif ($especie === 'gato') {
                $sql .= " AND tm.especie = 'Felino'";
            } else {
                $sql .= " AND LOWER(tm.especie) LIKE LOWER(:especie)";
                $params[':especie'] = '%' . $especie . '%';
            }
        }

        // Filtro por género
        if (!empty($genero)) {
            $sql .= " AND m.sexo = :genero";
            $params[':genero'] = $genero;
        }

        // Filtro por edad (usando HAVING para usar el campo calculado)
        $havingConditions = [];

        if (!empty($edad)) {
            switch ($edad) {
                case 'cachorro':
                    $havingConditions[] = "categoria_edad = 'cachorro'";
                    break;
                case 'joven':
                    $havingConditions[] = "categoria_edad = 'joven'";
                    break;
                case 'adulto':
                    $havingConditions[] = "categoria_edad = 'adulto'";
                    break;
                case 'senior':
                    $havingConditions[] = "categoria_edad = 'senior'";
                    break;
            }
        }

        // Agregar HAVING
        if (!empty($havingConditions)) {
            $sql .= " HAVING " . implode(' AND ', $havingConditions);
        }

        $sql .= " ORDER BY m.id_mascota DESC";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener perfil completo de mascota
    public function getPerfilCompletoPorId($id)
    {
        $sql = "SELECT 
                    m.*,
                    tm.especie,
                    ea.tipo_estado,
                    f.nombre as nombre_fundacion,
                    f.nit_fundacion,
                    u.nombre as rep_nombre,
                    u.apellido as rep_apellido,
                    u.telefono as rep_telefono,
                    u.email as rep_email,
                    u.direccion as fundacion_direccion,
                    p.descripcion as fundacion_descripcion,
                    p.imagen as fundacion_imagen,
                    p.nombre as fundacion_perfil_nombre,
                    CASE 
                        WHEN m.edad_meses < 12 THEN 'cachorro'
                        WHEN m.edad_meses BETWEEN 12 AND 36 THEN 'joven'
                        WHEN m.edad_meses BETWEEN 37 AND 84 THEN 'adulto'
                        ELSE 'senior'
                    END as categoria_edad,
                    
                    -- Contar otras mascotas de la misma fundación
                    (SELECT COUNT(*) FROM t_mascota m2 
                     WHERE m2.nit_fundacion = m.nit_fundacion 
                     AND m2.id_estado_adopcion IN (1, 4) 
                     AND m2.id_mascota != m.id_mascota) as otras_mascotas_disponibles
                FROM t_mascota m
                INNER JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
                INNER JOIN t_fundacion f ON m.nit_fundacion = f.nit_fundacion
                INNER JOIN t_usuario u ON f.id_usuario = u.id_usuario
                INNER JOIN t_perfil p ON f.id_perfil = p.id_perfil
                WHERE m.id_mascota = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado;
    }


    // Obtener todos los tipos de mascota
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

    // Obtener mascotas por fundación específica
    public function getMascotasPorFundacion($nit_fundacion)
    {
        $sql = "SELECT 
                m.id_mascota,
                m.nombre,
                m.edad_meses,
                m.sexo,
                m.imagen,
                m.nit_fundacion,
                m.numero_chip,  
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


    // Obtener todos los estados de adopción
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

    // Obtener todos los NIT de fundaciones
    public function getNitsFundacion()
    {
        $rows = [];

        $stmt = $this->db->prepare("SELECT nit_fundacion FROM t_fundacion");
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $resultado; // Deja el resultado completo como arreglo asociativo
        }

        return $rows;
    }
}
