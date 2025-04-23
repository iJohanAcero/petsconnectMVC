<?php
require_once "controller/usuarioController.php";

session_start();
$controller = new UsuarioController();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    if($_POST["action"] == "register") {
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $contrasena = $_POST["contrasena"];
        $email = $_POST["email"];
        $direccion = $_POST["direccion"];
        $telefono = $_POST["telefono"];

        if ($controller->registrar($nombre, $apellido,$contrasena,$email,$direccion,$telefono)) {
            $mensajeRegsitroCorrecto = "Usuario registrado correctamente";
        } else {
            $mensajeRegistroIncorrecto= "Error al registrar el usuario";
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
            $mensaje = "Usuario o contraseña incorrecta";
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