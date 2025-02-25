<?php

require_once "Model/conexion.php";

class Usuario {
private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->conn;
    }

    public function login($email, $password) {
        $query = "SELECT * FROM usuario WHERE email =  :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($email && password_verify($password, $email['contraseña'])) {
            return $user;
        }

        return false;
    }
}

?>