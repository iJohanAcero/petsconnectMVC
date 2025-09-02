<?php

namespace App\Model\Donacion;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Conexion;
use PDO;

class Donacion {
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    /* Crear registro de donación en estado */
    public function crearDonacion($idUsuario, $idCausa, $nitFundacion, $monto, $paymentId) {
        $sql = "INSERT INTO t_donacion 
                (id_usuario, id_causa, nit_fundacion, stripe_payment_id, monto, estado, fecha) 
                VALUES (:id_usuario, :id_causa, :nit_fundacion, :payment_id, :monto, 'pagado', NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':id_causa', $idCausa, PDO::PARAM_INT);
        $stmt->bindParam(':nit_fundacion', $nitFundacion, PDO::PARAM_INT);
        $stmt->bindParam(':payment_id', $paymentId, PDO::PARAM_STR);
        $stmt->bindParam(':monto', $monto);

        return $stmt->execute();
    }

    /* Obtener donación por ID de Stripe */
    public function obtenerPorPaymentId($paymentId) {
        $sql = "SELECT * FROM t_donacion WHERE stripe_payment_id = :payment_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':payment_id', $paymentId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* Obtener NIT de fundación por ID de causa */
    public function obtenerNitFundacionPorCausa($idCausa) {
        $sql = "SELECT nit_fundacion 
                FROM t_causa 
                WHERE id_causa = :id_causa 
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_causa', $idCausa, PDO::PARAM_INT);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['nit_fundacion'] : null;
    }

    /* Obtener historial de donaciones de un usuario */
    public function obtenerDonacionesUsuario($idUsuario) {
        $sql = "SELECT d.*, c.nombre as causa_nombre, f.nombre as fundacion_nombre
                FROM t_donacion d
                LEFT JOIN t_causa c ON d.id_causa = c.id_causa
                LEFT JOIN t_fundacion f ON d.nit_fundacion = f.nit_fundacion
                WHERE d.id_usuario = :id_usuario
                ORDER BY d.fecha DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Obtener estadísticas de donaciones por causa */
    public function obtenerEstadisticasCausa($idCausa) {
        $sql = "SELECT 
                    COUNT(*) as total_donaciones,
                    SUM(CASE WHEN estado = 'pagado' THEN monto ELSE 0 END) as total_recaudado,
                    AVG(CASE WHEN estado = 'pagado' THEN monto ELSE NULL END) as promedio_donacion
                FROM t_donacion 
                WHERE id_causa = :id_causa";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_causa', $idCausa, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}