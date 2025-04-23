<?php

require_once "Model/Usuario.php";

class UsuarioController {
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new Usuario();
    }

    public function login($email, $contrasena) {
        return $this->usuarioModel->login($email, $contrasena);
    }

    public function registrar($nombre, $apellido,$contrasena,$email,$direccion,$telefono ) {
        return $this->usuarioModel->registrar($nombre, $apellido ,$contrasena,$email,$direccion,$telefono);
    }
}
?>
<!-- Hola estoy probando el git  -->