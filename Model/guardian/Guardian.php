<?php

namespace App\Model\Guardian;
use App\Model\Conexion;
use PDOException;
use Exception;
use PDO;

class Guardian {
    private $db;

    public function __construct() {
        $this->db = (new Conexion())->getConexion();
    }

    // Registrar nuevo guardian
    public function registrarGuardian($nombre, $apellido, $contrasena, $email, $direccion, $telefono) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO t_usuario (nombre, apellido, contrasena, email, direccion, telefono) 
                                       VALUES (:nombre, :apellido, :contrasena, :email, :direccion, :telefono)");
            
            $hash = password_hash($contrasena, PASSWORD_BCRYPT);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':contrasena', $hash);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':direccion', $direccion);
            $stmt->bindParam(':telefono', $telefono);
            
            if (!$stmt->execute()) {
                $this->db->rollback();
                return false;
            }

            $id_usuario = $this->db->lastInsertId();

            // 2. Llamar al procedimiento almacenado con el id_usuario
            $call = $this->db->prepare("CALL crear_guardian(:p_id_usuario)");
            $call->bindParam(':p_id_usuario', $id_usuario);
            
            if (!$call->execute()) {
                $this->db->rollback();
                return false;
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    public function getGuardian() {
        $rows = [];

        $sql = "SELECT 
                g.id_usuario AS id_usuario,
                g.id_perfil AS id_perfil,
                g.id_registro AS id_registro,
                u.nombre AS nombre_guardian,
                u.apellido AS apellido_guardian,
                u.email AS correo,
                u.telefono AS telefono_guardian,
                u.direccion AS direccion_guardian,
                p.imagen AS imagen_guardian
            FROM t_guardian g
            INNER JOIN t_usuario u ON g.id_usuario = u.id_usuario
            INNER JOIN t_perfil p ON g.id_perfil = p.id_perfil";

        $call = $this->db->prepare($sql);
        $call->execute();

        while ($row = $call->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }

        return $rows;
    }

    // Obtener guardian por ID de usuario
    public function getGuardianById($id_usuario) {
        $stmt = $this->db->prepare("SELECT 
                g.id_usuario,
                g.id_perfil,
                g.id_registro,
                u.nombre,
                u.apellido,
                u.email,
                u.direccion,
                u.telefono,
                p.imagen
            FROM t_guardian g
            INNER JOIN t_usuario u ON g.id_usuario = u.id_usuario
            INNER JOIN t_perfil p ON g.id_perfil = p.id_perfil
            WHERE g.id_usuario = :id_usuario");
        
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar guardian 
    public function updateGuardian($id_usuario, $nombre, $apellido, $email, $direccion, $telefono) {
        // Verificar que el guardian existe
        if (!$this->getGuardianById($id_usuario)) {
            return false;
        }

        $stmt = $this->db->prepare("UPDATE t_usuario 
            SET nombre = :nombre,
                apellido = :apellido,
                email = :email,
                direccion = :direccion,
                telefono = :telefono
            WHERE id_usuario = :id_usuario");
        
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':telefono', $telefono);

        return $stmt->execute();
    }

    // Eliminar guardian
    public function delete($id_usuario) {
        try {
            // Obtener datos del guardian antes de eliminar
            $guardian = $this->getGuardianById($id_usuario);
            if (!$guardian) {
                return false;
            }
            $this->db->beginTransaction();

            // 1. Eliminar de t_guardian
            $stmt = $this->db->prepare("DELETE FROM t_guardian WHERE id_usuario = :id_usuario");
            $stmt->bindParam(':id_usuario', $id_usuario);
            if (!$stmt->execute()) {
                $this->db->rollback();
                return false;
            }

            // 2. Eliminar de t_perfil
            if ($guardian['id_perfil']) {
                $stmt = $this->db->prepare("DELETE FROM t_perfil WHERE id_perfil = :id_perfil");
                $stmt->bindParam(':id_perfil', $guardian['id_perfil']);
                $stmt->execute();
            }

            // 3. Eliminar de t_registro
            if ($guardian['id_registro']) {
                $stmt = $this->db->prepare("DELETE FROM t_registro WHERE id_registro = :id_registro");
                $stmt->bindParam(':id_registro', $guardian['id_registro']);
                $stmt->execute();
            }

            // 4. Eliminar usuario
            $stmt = $this->db->prepare("DELETE FROM t_usuario WHERE id_usuario = :id_usuario");
            $stmt->bindParam(':id_usuario', $id_usuario);
            if (!$stmt->execute()) {
                $this->db->rollback();
                return false;
            }

            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            $this->db->rollback();
            if ($e->getCode() == '23000') {
                return 'constraint_error';
            }
            return false;
        }
    }

    // Obtener IDs de usuarios que son guardianes
    public function getIdsUsuariosGuardianes() {
        $ids = [];

        $stmt = $this->db->prepare("SELECT id_usuario FROM t_guardian");
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ids[] = $row;
        }

        return $ids;
    }
}