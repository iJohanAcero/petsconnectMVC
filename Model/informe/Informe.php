<?php

namespace App\Model\Informe;

use App\Model\Conexion;
use PDOException;
use Exception;
use PDO;

class Informe
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    public function getMascotasAdultas()
    {
        $sql = "SELECT 
                    m.id_mascota,
                    m.nombre AS mascota,
                    t.especie,
                    m.sexo,
                    m.edad_meses,
                    f.nombre AS fundacion,
                    ea.tipo_estado AS estado_adopcion
                FROM t_mascota m
                INNER JOIN t_tipo_mascota t ON m.id_tipo_mascota = t.id_tipo_mascota
                INNER JOIN t_fundacion f ON m.nit_fundacion = f.nit_fundacion
                INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
                WHERE m.edad_meses >= 36
                  AND ea.tipo_estado = 'En adopción'";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMascotasPopulares()
    {
        $sql = "SELECT 
                m.id_mascota,
                m.nombre AS mascota,
                t.especie,
                f.nombre AS fundacion,
                COUNT(fa.id_formulario) AS total_solicitudes
            FROM t_mascota m
            INNER JOIN t_tipo_mascota t ON m.id_tipo_mascota = t.id_tipo_mascota
            INNER JOIN t_fundacion f ON m.nit_fundacion = f.nit_fundacion
            LEFT JOIN t_formulario_adopcion fa ON m.id_mascota = fa.id_mascota
            GROUP BY m.id_mascota, m.nombre, t.especie, f.nombre
            HAVING COUNT(fa.id_formulario) > 5
            ORDER BY total_solicitudes DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
