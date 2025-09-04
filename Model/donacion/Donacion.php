<?php

namespace App\Model\Donacion;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Conexion;
use PDO;

class Donacion
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    public function crearDonacion($idUsuario, $idCausa, $nitFundacion, $monto, $paymentId)
    {
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
    public function obtenerPorPaymentId($paymentId)
    {
        $sql = "SELECT * FROM t_donacion WHERE stripe_payment_id = :payment_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':payment_id', $paymentId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* Obtener donación por ID */
    public function obtenerPorId($idDonacion)
    {
        $sql = "SELECT 
                d.id_donacion,
                d.id_usuario,
                d.id_causa,
                d.nit_fundacion,
                d.stripe_payment_id,
                d.monto,
                d.metodo_pago,
                d.estado,
                d.fecha,
                c.nombre as causa_nombre,
                c.descripcion as causa_descripcion,
                f.nombre as fundacion_nombre
            FROM t_donacion d
            LEFT JOIN t_causa c ON d.id_causa = c.id_causa
            LEFT JOIN t_fundacion f ON d.nit_fundacion = f.nit_fundacion
            WHERE d.id_donacion = :id_donacion 
            LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_donacion', $idDonacion, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* Obtener NIT de fundación por ID de causa */
    public function obtenerNitFundacionPorCausa($idCausa)
    {
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

    /* Obtener todas las donaciones de un usuario con información completa */
    public function obtenerTodasDonacionesUsuario($idUsuario)
    {
        $sql = "SELECT 
                d.id_donacion,
                d.id_usuario,
                d.id_causa,
                d.nit_fundacion,
                d.stripe_payment_id,
                d.monto,
                d.metodo_pago,
                d.estado,
                d.fecha,
                c.nombre as causa_nombre,
                c.descripcion as causa_descripcion,
                c.imagen_url as causa_imagen,
                f.nombre as fundacion_nombre
            FROM t_donacion d
            LEFT JOIN t_causa c ON d.id_causa = c.id_causa
            LEFT JOIN t_fundacion f ON d.nit_fundacion = f.nit_fundacion
            WHERE d.id_usuario = :id_usuario";

        $sql .= " ORDER BY d.fecha DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);


        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Obtener todas las donaciones RECIBIDAS por una fundación */
    public function obtenerDonacionesPorFundacion($nitFundacion)
    {
        $sql = "SELECT 
            d.id_donacion,
            d.id_usuario,
            d.id_causa,
            d.nit_fundacion,
            d.stripe_payment_id,
            d.monto,
            d.metodo_pago,
            d.estado,
            d.fecha,
            c.nombre as causa_nombre,
            c.descripcion as causa_descripcion,
            c.imagen_url as causa_imagen,
            f.nombre as fundacion_nombre,
            u.nombre as donante_nombre,
            u.apellido as donante_apellido,
            u.email as donante_email
        FROM t_donacion d
        LEFT JOIN t_causa c ON d.id_causa = c.id_causa
        LEFT JOIN t_fundacion f ON d.nit_fundacion = f.nit_fundacion
        LEFT JOIN t_usuario u ON d.id_usuario = u.id_usuario
        WHERE d.nit_fundacion = :nit_fundacion
        ORDER BY d.fecha DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nit_fundacion', $nitFundacion, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Obtener TODAS las donaciones (para admin) */
    public function obtenerTodasLasDonaciones()
    {
        $sql = "SELECT 
            d.id_donacion,
            d.id_usuario,
            d.id_causa,
            d.nit_fundacion,
            d.stripe_payment_id,
            d.monto,
            d.metodo_pago,
            d.estado,
            d.fecha,
            c.nombre as causa_nombre,
            c.descripcion as causa_descripcion,
            c.imagen_url as causa_imagen,
            f.nombre as fundacion_nombre,
            u.nombre as donante_nombre,
            u.apellido as donante_apellido,
            u.email as donante_email
        FROM t_donacion d
        LEFT JOIN t_causa c ON d.id_causa = c.id_causa
        LEFT JOIN t_fundacion f ON d.nit_fundacion = f.nit_fundacion
        LEFT JOIN t_usuario u ON d.id_usuario = u.id_usuario
        ORDER BY d.fecha DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
