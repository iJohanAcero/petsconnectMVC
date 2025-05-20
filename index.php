<?php
require_once "controller/usuarioController.php";
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
                header("Location: acceso_denegado.php");
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
    require_once "Views/login.php";
}

$page = $_GET["page"] ?? "";

switch ($page) {
    case "admin_home":
        if ($_SESSION["tipo_usuario"] === "admin") {
            require_once "views/admin_home.php";
        } else {
            exit("Acceso denegado");
        }
        break;

    case "guardian_home":
        if ($_SESSION["tipo_usuario"] === "guardian") {
            require_once "views/guardian_home.php";
        } else {
            exit("Acceso denegado");
        }
        break;

    case "registro":
        if (!isset($_SESSION["user"])) {
            require_once "views/registro.php";
        } else {
            header("Location: index.php?page=admin_home"); // o guardian_home
        }
        break;

}
