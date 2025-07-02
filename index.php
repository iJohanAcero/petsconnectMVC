<?php
require_once __DIR__ . '/vendor/autoload.php';
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

        $resultado = $controller->registrar($nombre, $apellido, $contrasena, $email, $direccion, $telefono);

        if (is_array($resultado) && isset($resultado['success'])) {
            if ($resultado['success']) {
                $mensajeRegsitroCorrecto = $resultado['message'];
            } else {
                $mensajeRegistroIncorrecto = $resultado['message'];
            }
        } else {
            $mensajeRegistroIncorrecto = "Error inesperado en el registro.";
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
                
            } else if (esFundacion($user["id_usuario"])) {
                $_SESSION["tipo_usuario"] = "fundacion";
                header("Location: index.php?page=fundacion_home");
                exit;
                
            } 
            else {
                $_SESSION["tipo_usuario"] = "desconocido";
                $mensaje = "No se pudo determinar el rol del usuario";
                exit;
            }
        } else {
            $mensaje = "Usuario o contraseña incorrecta";
        }
    }
}

// --- Logout ---
if (isset($_GET["action"]) && $_GET["action"] == "logout") {
    session_destroy();
    header("Location: index.php?page=landing");
    exit;
}
// --- Login con Google ---
if (isset($_GET['action']) && $_GET['action'] === 'login_google') {
    (new AuthController())->loginGoogle();
}
if (isset($_GET['action']) && $_GET['action'] === 'google_callback') {
    (new AuthController())->googleCallback();
}

// MOSTRAR PUBLICACIONES EN EL INICIO

if (isset($_GET['action']) && $_GET['action'] === 'publicaciones_recientes') {
    require_once "controller/publicacion/PublicacionController.php";
    exit;
}

// --- Definición de rutas protegidas ---
$routes = [
    "admin_home"    => ["role" => "admin",    "file" => "view/home/admin_home.php"],
    "guardian_home" => ["role" => "guardian", "file" => "view/home/guardian_home.php"],
    "fundacion_home" => ["role" => "fundacion", "file" => "view/home/fundacion_home.php"],
    "login"        => ["role" => "guest",   "file" => "view/login/login.php"],
    "registro"     => ["role" => "guest",   "file" => "view/login/register.php"],
];

// --- Si no hay sesión, mostrar landing o registro ---
$page = $_GET["page"] ?? "";

if (!isset($_SESSION["user"])) {
    if ($page === "registro") {
        require_once $routes["registro"]["file"];
    } elseif ($page === "login") {
        require_once $routes["login"]["file"];
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
    } elseif ($_SESSION["tipo_usuario"] === "fundacion") {
        header("Location: index.php?page=fundacion_home");
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