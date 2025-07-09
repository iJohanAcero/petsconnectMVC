<?php
// Requiere la conexión a la base de datos
require_once '../../Model/conexion.php';

class Mascota
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    // 🟢 Agregar nueva mascota usando PROCEDIMIENTO ALMACENADO
    public function add($id_mascota, $nombre, $edad_meses, $sexo, $imagen, $id_tipo_mascota, $nit_fundacion, $id_estado_adopcion)
    {
        try {
            $sql = "CALL sp_insertar_mascota(:id_mascota, :nombre, :edad_meses, :sexo, :imagen, :id_tipo_mascota, :nit_fundacion, :id_estado_adopcion)";
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':id_mascota', $id_mascota);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':edad_meses', $edad_meses);
            $stmt->bindParam(':sexo', $sexo);
            $stmt->bindParam(':imagen', $imagen);
            $stmt->bindParam(':id_tipo_mascota', $id_tipo_mascota);
            $stmt->bindParam(':nit_fundacion', $nit_fundacion);
            $stmt->bindParam(':id_estado_adopcion', $id_estado_adopcion);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // 🟡 Obtener todas las mascotas con JOINs para mostrar datos legibles
    public function getMascotas()
    {
        $rows = [];

        $sql = "SELECT 
                    m.id_mascota,
                    m.nombre,
                    m.edad_meses,
                    m.sexo,
                    m.imagen,
                    m.nit_fundacion,
                    
                    tm.especie,
                    tm.raza,
                    tm.tamaño,
                    tm.tipo_pelaje,

                    ea.tipo_estado

                FROM t_mascota m
                INNER JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $resultado;
        }

        return $rows;
    }

    // 🟠 Obtener mascota por ID
    public function getId($id)
    {
        $rows = null;

        $sql = "SELECT 
                    m.*, 
                    tm.especie, tm.raza, tm.tamaño, tm.tipo_pelaje,
                    ea.tipo_estado
                FROM t_mascota m
                INNER JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
                INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
                WHERE m.id_mascota = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $resultado;
        }

        return $rows;
    }

    // 🔵 Actualizar mascota
    public function update($id_mascota, $nombre, $edad_meses, $sexo, $imagen, $id_tipo_mascota, $id_estado_adopcion)
    {
        $sql = "UPDATE t_mascota 
                SET nombre = :nombre,
                    edad_meses = :edad_meses,
                    sexo = :sexo,
                    imagen = :imagen,
                    id_tipo_mascota = :id_tipo_mascota,
                    id_estado_adopcion = :id_estado_adopcion
                WHERE id_mascota = :id_mascota";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':id_mascota', $id_mascota);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':edad_meses', $edad_meses);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':imagen', $imagen);
        $stmt->bindParam(':id_tipo_mascota', $id_tipo_mascota);
        $stmt->bindParam(':id_estado_adopcion', $id_estado_adopcion);

        return $stmt->execute();
    }

    // 🔴 Eliminar mascota (solo para admin)
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

    // 🟢 Obtener mascotas para el portafolio (en adopción, en trámite o en tránsito)
    public function getMascotasPortafolio()
{
    $rows = null;

    $sql = "SELECT 
                m.id_mascota,
                m.nombre,
                m.edad_meses,
                m.sexo,
                m.imagen,
                tm.especie,
                tm.raza,
                tm.tipo_pelaje,
                ea.tipo_estado
            FROM t_mascota m
            INNER JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
            INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
            WHERE ea.tipo_estado IN ('EN ADOPCION', 'EN TRAMITE', 'TRANSITO')";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $rows[] = $resultado;
    }

    return $rows;
}

}
