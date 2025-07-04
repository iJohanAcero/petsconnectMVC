<?php

require_once "Model/conexion.php";


class Usuario
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    public function login($email, $contrasena)
    {
        $query = "SELECT * FROM t_usuario WHERE email =  :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($contrasena, $user['contrasena'])) {
            return $user;
        }

        return false;
    }

    public function findOrCreateGoogleUser($google_id, $nombre, $apellido, $email, $picture)
    {
        // Buscar usuario por google_id
        $stmt = $this->db->prepare("SELECT * FROM t_usuario WHERE google_id = :google_id");
        $stmt->bindParam(':google_id', $google_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return $user;
        }

        // Si no existe por google_id, buscar por email
        $stmt = $this->db->prepare("SELECT * FROM t_usuario WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $userByEmail = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userByEmail) {
            // Si existe por email, actualiza el google_id
            $stmt = $this->db->prepare("UPDATE t_usuario SET google_id = :google_id WHERE email = :email");
            $stmt->bindParam(':google_id', $google_id);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Devuelve el usuario actualizado
            return $this->findByGoogleId($google_id);
        }

        // Si no existe, crear usuario nuevo
        $query = "INSERT INTO t_usuario (nombre, apellido, email, google_id) VALUES (:nombre, :apellido, :email, :google_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':google_id', $google_id);
        $stmt->execute();

        // Obtener el ID del nuevo usuario
        $id_usuario = $this->db->lastInsertId();

        // Llamar al procedimiento almacenado para crear guardian + perfil + registro
        $call = $this->db->prepare("CALL crear_guardian(:id_usuario)");
        $call->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $call->execute();

        return $this->findByGoogleId($google_id);
    }

    public function findByGoogleId($google_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM t_usuario WHERE google_id = :google_id");
        $stmt->bindParam(':google_id', $google_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrar($nombre, $apellido, $contrasena, $email, $direccion, $telefono)
    {
        $hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $query = "INSERT INTO t_usuario (nombre, apellido, contrasena, email, direccion, telefono) VALUES (:nombre, :apellido, :contrasena, :email, :direccion, :telefono)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":apellido", $apellido);
        $stmt->bindParam(":contrasena", $hash);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":direccion", $direccion);
        $stmt->bindParam(":telefono", $telefono);

        if ($stmt->execute()) {
            // Obtener el ID del nuevo usuario
            $id_usuario = $this->db->lastInsertId();

            // Llamar al procedimiento almacenado para crear guardian + perfil + registro
            $call = $this->db->prepare("CALL crear_guardian(:id_usuario)");
            $call->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $call->execute();

            return true;
        }

        return false;
    }
}
