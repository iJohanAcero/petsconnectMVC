<?php

require_once "Model/conexion.php";

class Usuario
{
    private $db;



    public function __construct()
    {
        $this->db = (new Conexion())->conn;
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

        return $stmt->execute();
    }
}
