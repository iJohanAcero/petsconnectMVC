<?php
require_once "Controller/usuarioController.php";

session_start();
$controller = new UsuarioController();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {

    if ($_POST["action"] == "login") {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $user = $controller->login($email, $password);

        if ($user) {
            $_SESSION["user"] = $user;
            header("Location: index.php");
        } else {
            echo "Usuario o contraseña incorrecta";
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
}
