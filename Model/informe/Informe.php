<?php

namespace App\Model\Informe;

use App\Model\Conexion;
use PDO;

class Informe
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    public function getMascotasAdultas($nitFundacion = null)
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

        // Filtrar por fundación
        if ($nitFundacion !== null) {
            $sql .= " AND m.nit_fundacion = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$nitFundacion]);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMascotasPopulares($nitFundacion = null)
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
            LEFT JOIN t_formulario_adopcion fa ON m.id_mascota = fa.id_mascota";

        // Si no es admin, filtrar por fundación
        if ($nitFundacion !== null) {
            $sql .= " WHERE m.nit_fundacion = ?";
        }

        $sql .= " GROUP BY m.id_mascota, m.nombre, t.especie, f.nombre
                 HAVING COUNT(fa.id_formulario) > 0
                 ORDER BY total_solicitudes DESC";

        if ($nitFundacion !== null) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$nitFundacion]);
        } else {
            $stmt = $this->db->query($sql);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCausasProgreso($nitFundacion = null)
    {
        $sql = "SELECT 
                c.id_causa,
                c.nombre AS causa,
                f.nombre AS fundacion,
                c.meta,
                COALESCE(SUM(d.monto), 0) AS total_recaudado,
                (COALESCE(SUM(d.monto), 0) / c.meta) * 100 AS porcentaje_avance,
                c.estado_causa,
                c.fecha_creacion
            FROM t_causa c
            INNER JOIN t_fundacion f ON c.nit_fundacion = f.nit_fundacion
            LEFT JOIN t_donacion d ON c.id_causa = d.id_causa AND d.estado = 'pagado'";

        // Si no es admin, filtrar por fundación
        if ($nitFundacion !== null) {
            $sql .= " WHERE c.nit_fundacion = ?";
        }

        $sql .= " GROUP BY c.id_causa, c.nombre, f.nombre, c.meta, c.estado_causa, c.fecha_creacion
                    ORDER BY c.fecha_creacion DESC";

        if ($nitFundacion !== null) {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$nitFundacion]);
        } else {
            $stmt = $this->db->query($sql);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // INFORMES PARA LOS ADMINISTRADORES
    public function getDonacionFundacion()
    {
        $sql = "SELECT 
                f.nombre AS fundacion,
            COUNT(d.id_donacion) AS total_donaciones,
            COALESCE(SUM(d.monto), 0) AS total_recaudado,
            ROUND(AVG(d.monto), 2) AS promedio_donacion
        FROM t_fundacion f
        LEFT JOIN t_donacion d ON f.nit_fundacion = d.nit_fundacion
        GROUP BY f.nombre
        ORDER BY total_recaudado DESC;";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdopcionEspecie()
    {
        $sql = "SELECT 
                tm.especie AS especie,
                SUM(CASE WHEN ea.tipo_estado = 'ADOPTADO' THEN 1 ELSE 0 END) AS adopciones_completadas,
                SUM(CASE WHEN ea.tipo_estado = 'EN TRAMITE' THEN 1 ELSE 0 END) AS adopciones_pendientes,
                SUM(CASE WHEN ea.tipo_estado = 'EN ADOPCION' THEN 1 ELSE 0 END) AS en_adopcion
            FROM t_mascota m
            INNER JOIN t_tipo_mascota tm 
                ON m.id_tipo_mascota = tm.id_tipo_mascota
            INNER JOIN t_formulario_adopcion fa 
                ON m.id_mascota = fa.id_mascota
            INNER JOIN t_proceso_adopcion pa 
                ON fa.id_formulario = pa.id_formulario
            INNER JOIN t_estado_adopcion ea 
                ON pa.id_estado = ea.id_estado_adopcion
            GROUP BY tm.especie";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPublicacionesFundacion() {
    $sql = "SELECT 
                f.nombre AS fundacion,
                COUNT(p.id_publicacion) AS total_publicaciones,
                COALESCE(MAX(p.fecha), 'Sin publicaciones') AS ultima_publicacion
            FROM t_fundacion f
            LEFT JOIN t_publicacion p ON f.nit_fundacion = p.nit_fundacion
            GROUP BY f.nombre
            ORDER BY total_publicaciones DESC";

    $stmt = $this->db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
