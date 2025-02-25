<?php

require_once "Model/Usuario.php";

class UsuarioController {
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new Usuario();
    }

    public function login($email, $password){
        return $this->usuarioModel->login($email, $password);
    }
}
?>