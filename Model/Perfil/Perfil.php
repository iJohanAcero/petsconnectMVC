<?php

namespace App\Model\perfil;

use App\Model\Conexion;
use PDOException;
use Exception;
use PDO;


class Perfil
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    public function getRedesSocialesPorPerfil($id_perfil)
    {
        $stmt = $this->db->prepare("
        SELECT id_red, tipo_red, url_red 
        FROM t_perfil_redes 
        WHERE id_perfil = :id_perfil
        ORDER BY id_red ASC
    ");
        $stmt->bindParam(':id_perfil', $id_perfil, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPerfilPorUsuario($id)
    {
        // Buscar en guardian y traer también el id_usuario
        $stmt = $this->db->prepare("
        SELECT p.*, g.id_usuario, u.telefono, u.direccion, u.email
        FROM t_guardian g
        INNER JOIN t_perfil p ON g.id_perfil = p.id_perfil
        INNER JOIN t_usuario u ON g.id_usuario = u.id_usuario
        WHERE g.id_usuario = :id_usuario
        LIMIT 1
    ");
        $stmt->bindParam(':id_usuario', $id, PDO::PARAM_INT);
        $stmt->execute();
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($perfil) {
            // Obtener redes sociales del perfil
            $perfil['redes_sociales'] = $this->getRedesSocialesPorPerfil($perfil['id_perfil']);
            return $perfil;
        }

        // Buscar en fundacion si no es guardian y traer también el id_usuario
        $stmt = $this->db->prepare("
        SELECT p.*, f.id_usuario, u.telefono, u.direccion, u.email
        FROM t_fundacion f
        INNER JOIN t_perfil p ON f.id_perfil = p.id_perfil
        INNER JOIN t_usuario u ON f.id_usuario = u.id_usuario
        WHERE f.id_usuario = :id_usuario
        LIMIT 1
    ");
        $stmt->bindParam(':id_usuario', $id, PDO::PARAM_INT);
        $stmt->execute();
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($perfil) {
            // Obtener redes sociales del perfil
            $perfil['redes_sociales'] = $this->getRedesSocialesPorPerfil($perfil['id_perfil']);
            return $perfil;
        }

        return false;
    }

    // Versión mejorada del método actualizarPerfilFundacion
    public function actualizarPerfilFundacion($id, $nombre, $descripcion, $preferencia, $imagen, $redes_sociales = [])
    {
        try {
            $this->db->beginTransaction();

            // Actualizar datos básicos del perfil
            $stmt = $this->db->prepare("
            UPDATE t_perfil
            SET nombre = :nombre, descripcion = :descripcion, preferencia = :preferencia, imagen = :imagen
            WHERE id_perfil = (
                SELECT id_perfil FROM t_fundacion WHERE id_usuario = :id_usuario
            )
        ");
            $stmt->bindParam(':id_usuario', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':preferencia', $preferencia, PDO::PARAM_STR);
            $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                $this->db->rollBack();
                return false;
            }

            // Obtener el id_perfil
            $id_perfil = $this->getIdPerfilPorUsuario($id);

            // Actualizar redes sociales usando el método mejorado
            if (!$this->actualizarRedesSociales($id_perfil, $redes_sociales)) {
                $this->db->rollBack();
                return false;
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error actualizando perfil fundación: " . $e->getMessage());
            return false;
        }
    }

    // Método auxiliar para obtener id_perfil
    private function getIdPerfilPorUsuario($id_usuario)
    {
        $stmt = $this->db->prepare("SELECT id_perfil FROM t_fundacion WHERE id_usuario = :id_usuario");
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id_perfil'] ?? null;
    }

    // Método para agregar una red social usando el procedimiento almacenado
    public function agregarRedSocial($id_perfil, $tipo_red, $url_red)
    {
        try {
            $stmt = $this->db->prepare("CALL sp_agregar_red_social(?, ?, ?)");
            $stmt->bindParam(1, $id_perfil, PDO::PARAM_INT);
            $stmt->bindParam(2, $tipo_red, PDO::PARAM_STR);
            $stmt->bindParam(3, $url_red, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            // Si es el error personalizado del procedimiento
            if ($e->getCode() == '45000') {
                throw new Exception($e->getMessage());
            }
            return false;
        }
    }

    // Método mejorado para actualizar redes sociales
    private function actualizarRedesSociales($id_perfil, $redes_sociales)
    {
        try {
            // Eliminar redes sociales existentes
            $stmt = $this->db->prepare("DELETE FROM t_perfil_redes WHERE id_perfil = :id_perfil");
            $stmt->bindParam(':id_perfil', $id_perfil, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                return false;
            }

            // Insertar nuevas redes sociales usando el procedimiento almacenado
            if (!empty($redes_sociales)) {
                foreach ($redes_sociales as $red) {
                    if (!empty($red['tipo_red']) && !empty($red['url_red'])) {
                        if (!$this->agregarRedSocial($id_perfil, $red['tipo_red'], $red['url_red'])) {
                            return false;
                        }
                    }
                }
            }

            return true;
        } catch (Exception $e) {
            error_log("Error actualizando redes sociales: " . $e->getMessage());
            return false;
        }
    }

    // También actualiza el método para guardian
    public function actualizarPerfilGuardian($id, $nombre, $descripcion, $preferencia, $imagen, $redes_sociales = [])
    {
        try {
            $this->db->beginTransaction();

            // Actualizar datos básicos del perfil
            $stmt = $this->db->prepare("
            UPDATE t_perfil
            SET nombre = :nombre, descripcion = :descripcion, preferencia = :preferencia, imagen = :imagen
            WHERE id_perfil = (
                SELECT id_perfil FROM t_guardian WHERE id_usuario = :id_usuario
            )
        ");
            $stmt->bindParam(':id_usuario', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':preferencia', $preferencia, PDO::PARAM_STR);
            $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                $this->db->rollBack();
                return false;
            }

            // Obtener el id_perfil para guardian
            $stmt = $this->db->prepare("SELECT id_perfil FROM t_guardian WHERE id_usuario = :id_usuario");
            $stmt->bindParam(':id_usuario', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_perfil = $result['id_perfil'] ?? null;

            // Actualizar redes sociales
            if (!$this->actualizarRedesSociales($id_perfil, $redes_sociales)) {
                $this->db->rollBack();
                return false;
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
