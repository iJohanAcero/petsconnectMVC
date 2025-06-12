<?php
require_once "controller/usuario/usuarioController.php";
require_once "config/roles.php";

session_start();
$controller = new UsuarioController();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    if ($_POST["action"] == "register") {
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $contrasena = $_POST["contrasena"];
        $email = $_POST["email"];
        $direccion = $_POST["direccion"];
        $telefono = $_POST["telefono"];

        if ($controller->registrar($nombre, $apellido, $contrasena, $email, $direccion, $telefono)) {
            $mensajeRegsitroCorrecto = "Usuario registrado correctamente";
        } else {
            $mensajeRegistroIncorrecto = "Error al registrar el usuario";
        }
    }

    if ($_POST["action"] == "login") {
        $email = $_POST["email"];
        $contrasena = $_POST["contrasena"];

        $user = $controller->login($email, $contrasena);

        if ($user) {
            $_SESSION["user"] = $user;

            // DETECCIÓN DE ROL
            $tipo_usuario = "";

            // DETECTAR ROL
            if (esAdmin($user["id_usuario"])) {
                $_SESSION["tipo_usuario"] = "admin";
                header("Location: index.php?page=admin_home");
                exit;

            } else if (esGuardian($user["id_usuario"])) {
                $_SESSION["tipo_usuario"] = "guardian";
                header("Location: index.php?page=guardian_home");
                exit;

            } else {
                $_SESSION["tipo_usuario"] = "desconocido";
                $mensaje = "No se pudo determinar el rol del usuario";
                exit;
            }
        } else {
            $mensaje = "Usuario o contraseña incorrecta";
        }
    }
}

if (isset($_GET["action"]) && $_GET["action"] == "logout") {
    session_destroy();
    header("Location: index.php");
}

if (!isset($_SESSION["user"])) {
    require_once "view/login/landing.php";
}

$page = $_GET["page"] ?? "";

switch ($page) {
    case "admin_home":
        if ($_SESSION["tipo_usuario"] === "admin") {
            require_once "view/home/admin_home.php";
        } else {
            exit("Acceso denegado");
        }
        break;

    case "guardian_home":
        if ($_SESSION["tipo_usuario"] === "guardian") {
            require_once "view/home/guardian_home.php";
        } else {
            exit("Acceso denegado");
        }
        break;

    case "registro":
        if (!isset($_SESSION["user"])) {
            require_once "view/login/register.php";
        } else {
            header("Location: index.php?page=registro"); 
        }
        break;

}

if ($_GET['action'] === 'guardar_nueva_contraseña') {
    (new AuthController())->guardar_nueva_contraseña();
}
