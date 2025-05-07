<?php
require_once __DIR__ . '/../Config/db.php';

class CreateModel {
    private $conexion;

    public function __construct() {
        $db = new Database();
        $this->conexion = $db->conectar();
    }

    public function crearMascotas($id_mascota, $nombre, $edad_meses, $sexo, $imagen, $id_tipo_mascota, $nit_fundacion, $num_serie_vacuna) {
        $stmt = $this->conexion->prepare("INSERT INTO t_mascotas (nombre, edad_meses, sexo, imagen, id_tipo_mascota, nit_fundacion, num_serie_vacuna) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissiisi", $nombre, $edad_meses, $sexo, $imagen, $id_tipo_mascota, $nit_fundacion, $num_serie_vacuna, $id_mascota);
        return $stmt->execute();
    }

}



