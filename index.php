<?php
require_once "controller/usuario/usuarioController.php";
require_once "config/roles.php";
require_once "controller/AuthController.php"; // Agrega esta línea arriba

session_start();
$controller = new UsuarioController();

// --- Manejo de formularios POST ---
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
if (isset($_POST['action'])) {
    if ($_POST['action'] === 'enviar_recuperacion') {
        (new AuthController())->enviar_recuperacion();
        exit;
    }
    if ($_POST['action'] === 'guardar_nueva_contrasena') {
        (new AuthController())->guardar_nueva_contrasena();
        exit;
    }
}

;
// --- Logout ---
if (isset($_GET["action"]) && $_GET["action"] == "logout") {
    session_destroy();
    header("Location: view/login/login.php");
    exit;
}

// --- Definición de rutas protegidas ---
$routes = [
    "admin_home"    => ["role" => "admin",    "file" => "view/home/admin_home.php"],
    "guardian_home" => ["role" => "guardian", "file" => "view/home/guardian_home.php"],
    "registro"      => ["role" => "guest",    "file" => "view/login/register.php"],
    // Puedes agregar más rutas aquí
];

// --- Si no hay sesión, mostrar landing o registro ---
$page = $_GET["page"] ?? "";
if (!isset($_SESSION["user"])) {
    if ($page === "registro") {
        require_once $routes["registro"]["file"];
    } else {
        require_once "view/login/landing.php";
    }
    exit;
}

// --- Redirección automática según el tipo de usuario ---
if (!$page) {
    if ($_SESSION["tipo_usuario"] === "admin") {
        header("Location: index.php?page=admin_home");
    } elseif ($_SESSION["tipo_usuario"] === "guardian") {
        header("Location: index.php?page=guardian_home");
    } else {
        session_destroy();
        header("Location: view/login/login.php");
    }
    exit;
}

// --- Validación de rutas y roles ---
if (isset($routes[$page])) {
    $requiredRole = $routes[$page]["role"];
    if (
        ($requiredRole === "guest") ||
        ($_SESSION["tipo_usuario"] === $requiredRole)
    ) {
        require_once $routes[$page]["file"];
    } else {
        exit("Acceso denegado");
    }
} else {
    // Página no encontrada
    http_response_code(404);
    echo "Página no encontrada";
}



// Aquí cargas tu landing page u otras vistas normalmente
