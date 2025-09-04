<?php

namespace App\Model\dashboard;

use App\Model\Conexion;
use PDO;

class DashboardAdmin
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    public function getDonacionesPorMes()
    {
        $sql = "SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, SUM(monto) AS total
            FROM t_donacion
            WHERE estado = 'pagado'
            GROUP BY mes
            ORDER BY mes";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGuardianesPorMes()
    {
        $sql = "SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes,
                   COUNT(*) AS total_guardianes
            FROM t_registro
            WHERE tipo_usuario = 'GUARDIAN'
            GROUP BY mes
            ORDER BY mes";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMascotasFelinasCaninas()
    {
        $sql = "SELECT tm.especie AS especie,
                   COUNT(m.id_mascota) AS total
            FROM t_mascota m
            INNER JOIN t_tipo_mascota tm ON m.id_tipo_mascota = tm.id_tipo_mascota
            WHERE tm.especie IN ('FELINO','CANINO')
            GROUP BY tm.especie";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPublicacionesPorMes()
    {
        $sql = "SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes,
                   COUNT(*) AS total
            FROM t_publicacion
            GROUP BY mes
            ORDER BY mes";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMascotasPorEstado()
    {
        $sql = "SELECT ea.tipo_estado,
                   COUNT(m.id_mascota) AS total
            FROM t_mascota m
            INNER JOIN t_estado_adopcion ea ON m.id_estado_adopcion = ea.id_estado_adopcion
            GROUP BY ea.tipo_estado";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRankingFundaciones()
    {
        $sql = "SELECT f.nombre AS fundacion,
                   COUNT(d.id_donacion) AS total_donaciones,
                   COALESCE(SUM(d.monto), 0) AS total_recaudado
            FROM t_fundacion f
            LEFT JOIN t_donacion d ON f.nit_fundacion = d.nit_fundacion
            GROUP BY f.nombre
            ORDER BY total_recaudado DESC
            LIMIT 10";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTiposCausas()
    {
        $sql = "SELECT tipo_causa,
                   COUNT(*) AS total_causas
            FROM t_causa
            GROUP BY tipo_causa
            ORDER BY total_causas DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsuariosRegistrados()
    {
        $sql = "SELECT tipo_usuario,
                   COUNT(*) AS total
            FROM t_registro
            WHERE tipo_usuario IN ('GUARDIAN','FUNDACION')
            GROUP BY tipo_usuario";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
