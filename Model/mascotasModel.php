<?php
require_once __DIR__ . '/../Config/db.php';

class mascotasModel {
    private $conexion;

    public function __construct() {
        $db = new Database();
        $this->conexion = $db->conectar();
    }

    public function obtenerMascotas() {
        $resultado = $this->conexion->query("SELECT * FROM t_mascota");
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
    public function eliminarMascotas($id_mascota) {
        $stmt = $this->conexion->prepare("DELETE FROM t_mascota WHERE id_mascota = ?");
        $stmt->bind_param("i", $id_mascota);
        return $stmt->execute();
    }
    // Metodos para editar productos 
    public function obtenerMascotasPorId($id_mascota) {
        $stmt = $this->conexion->prepare("SELECT * FROM t_mascota WHERE id_mascota = ?");
        $stmt->bind_param("i", $id_mascota);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }
    
    public function actualizarMascotas($id_mascota, $nombre, $edad_meses, $sexo, $imagen, $id_tipo_mascota, $nit_fundacion, $num_serie_vacuna) {
        $stmt = $this->conexion->prepare("UPDATE t_mascota SET nombre=?, edad_meses=?, sexo=?, imagen=?, id_tipo_mascota=?, nit_fundacion=?,num_serie_vacuna=?  WHERE id_mascota=?");
        $stmt->bind_param("sissiisi", $nombre, $edad_meses, $sexo, $imagen, $id_tipo_mascota, $nit_fundacion, $num_serie_vacuna, $id_mascota);
        $stmt->execute();
    }
    

}
