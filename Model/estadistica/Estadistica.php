<?php

namespace App\Model\estadistica;

use App\Model\Conexion;
use Exception;
use PDO;

class estadistica
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

public function obtenerEstadisticasNavbar() {
    $estadisticas = [];
    
    try {
        // Mascotas disponibles
        $sql1 = "SELECT COUNT(*) as total 
                FROM t_mascota m 
                INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion 
                WHERE ea.tipo_estado = 'EN ADOPCION'";
        $resultado1 = $this->db->query($sql1);
        $estadisticas['mascotas_disponibles'] = $resultado1 ? $resultado1->fetch(PDO::FETCH_ASSOC)['total'] : 0;

        // Adopciones exitosas
        $sql3 = "SELECT COUNT(*) as total 
                FROM t_proceso_adopcion pa 
                INNER JOIN t_estado_adopcion ea ON pa.id_estado = ea.id_estado_adopcion 
                WHERE ea.tipo_estado = 'ADOPTADO'";
        $resultado3 = $this->db->query($sql3);
        $estadisticas['adopciones_exitosas'] = $resultado3 ? $resultado3->fetch(PDO::FETCH_ASSOC)['total'] : 0;

        // Solicitudes en trámite
        $sql4 = "SELECT COUNT(*) as total 
                FROM t_proceso_adopcion pa 
                INNER JOIN t_estado_adopcion ea ON pa.id_estado = ea.id_estado_adopcion 
                WHERE ea.tipo_estado = 'EN TRAMITE'";
        $resultado4 = $this->db->query($sql4);
        $estadisticas['solicitudes_tramite'] = $resultado4 ? $resultado4->fetch(PDO::FETCH_ASSOC)['total'] : 0;

        $estadisticas['mascotas_nuevas'] = $estadisticas['mascotas_disponibles'];

        return $estadisticas;
        
    } catch (Exception $e) {
        return [
            'mascotas_disponibles' => 0,
            'adopciones_exitosas' => 0,
            'mascotas_nuevas' => 0,
            'solicitudes_tramite' => 0
        ];
    }
}
}
