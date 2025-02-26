<?php
require_once "controller/usuarioController.php";

session_start();
$controller = new UsuarioController();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    if($_POST["action"] == "register") {
        $nombre = $_POST["nombre"];
        $contrasena = $_POST["contrasena"];
        $email = $_POST["email"];
        $direccion = $_POST["direccion"];
        $telefono = $_POST["telefono"];

        if ($controller->registrar($nombre,$contrasena,$email,$direccion,$telefono)) {
            echo "Usuario registrado correctamente";
        } else {
            echo "Error al registrar el usuario";
        }
    }
    if ($_POST["action"] == "login") {
        $email = $_POST["email"];
        $contrasena = $_POST["contrasena"];

        $user = $controller->login($email, $contrasena);

        if($user) {
            $_SESSION["user"] = $user;
            header("Location: index.php");
        } else {
            echo "Usuario o contraseña incorrecta";
        }
    }
    
    
} 

if (isset($_GET["action"]) && $_GET["action"] == "logout") {
    session_destroy();
    header("Location: index.php");
}

if (isset($_SESSION["user"])) {
    require_once "Views/admin_home.php";
} else {
    require_once "Views/login.php";
}
?>