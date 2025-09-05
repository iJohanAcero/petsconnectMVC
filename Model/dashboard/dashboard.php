<?php

namespace App\Model\dashboard;

use App\Model\Conexion;
use PDO;

class Dashboard
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    //  METODOS PARA OBTENER DATOS PARA LOS GRÁFICOS DEL DASHBOARD ADMINISTRADOR
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

    // METODOS PARA OBTENER DATOS PARA LOS GRÁFICOS DEL DASHBOARD FUNDACION

    public function getDonacionesPorCausa(string $nitFundacion): array
    {
        $sql = "SELECT 
                c.nombre AS causa,
                c.meta,
                COALESCE(SUM(d.monto), 0) AS total_recaudado
            FROM t_causa c
            LEFT JOIN t_donacion d 
                ON c.id_causa = d.id_causa
               AND d.estado = 'pagado'      -- opcional: solo pagos confirmados
            WHERE c.nit_fundacion = :nit
            GROUP BY c.id_causa, c.nombre, c.meta
            ORDER BY total_recaudado DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nit', $nitFundacion, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPublicacionesPorMesFundacion(string $nitFundacion): array
    {
        $sql = "SELECT 
                DATE_FORMAT(p.fecha, '%Y-%m') AS mes,
                COUNT(p.id_publicacion) AS total_publicaciones
            FROM t_publicacion p
            WHERE p.nit_fundacion = :nit
            GROUP BY mes
            ORDER BY mes ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nit', $nitFundacion, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdopcionesPorEspecie(string $nitFundacion): array
    {
        $sql = "SELECT tm.especie AS especie,
                   COUNT(pa.id_proceso) AS total_adopciones
            FROM t_tipo_mascota tm
            INNER JOIN t_mascota m ON tm.id_tipo_mascota = m.id_tipo_mascota
            INNER JOIN t_proceso_adopcion pa ON pa.id_formulario = m.id_mascota
            WHERE m.nit_fundacion = :nit
              AND pa.id_estado = 3 
            GROUP BY tm.especie
            ORDER BY total_adopciones DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nit', $nitFundacion, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCausasActivasPorTipo(string $nitFundacion): array
    {
        $sql = "SELECT c.tipo_causa,
                   COUNT(c.id_causa) AS total_causas
            FROM t_causa c
            WHERE c.nit_fundacion = :nit
              AND c.estado_causa = 'activa'
            GROUP BY c.tipo_causa
            ORDER BY total_causas DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nit', $nitFundacion, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMascotasAdoptadasPorMes(string $nitFundacion): array
    {
        $sql = "SELECT 
                DATE_FORMAT(pa.fecha_actualizada, '%Y-%m') AS mes,
                COUNT(pa.id_proceso) AS total_adoptadas
            FROM t_proceso_adopcion pa
            INNER JOIN t_formulario_adopcion f ON pa.id_formulario = f.id_formulario
            INNER JOIN t_mascota m ON f.id_mascota = m.id_mascota
            WHERE m.nit_fundacion = :nit
              AND pa.id_estado = 3  -- 3 = ADOPTADO
            GROUP BY mes
            ORDER BY mes ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nit', $nitFundacion, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDonacionesPorMesFundacion(string $nitFundacion): array
    {
        $sql = "SELECT 
                DATE_FORMAT(d.fecha, '%Y-%m') AS mes,
                SUM(d.monto) AS total_recaudado
            FROM t_donacion d
            INNER JOIN t_causa c ON d.id_causa = c.id_causa
            WHERE c.nit_fundacion = :nit
              AND d.estado = 'pagado'
            GROUP BY mes
            ORDER BY mes ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nit', $nitFundacion, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
