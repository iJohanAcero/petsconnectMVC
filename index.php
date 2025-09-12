<?php
namespace App;

require_once __DIR__ . '/vendor/autoload.php';

use App\Controller\Usuario\UsuarioController;
use App\Controller\Publicacion\PublicacionController;
use App\Controller\AuthController;
use App\Config\Roles;

session_start();

function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
        }
    }
}

loadEnv(__DIR__ . '/../.env');

// --- MANEJO DE ACCIONES AJAX PRIMERO ---
$action = $_GET['action'] ?? null;

// Procesar acciones antes que las rutas de página
if ($action) {
    switch ($action) {
        case 'recientes':
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $controller = new PublicacionController();
                $controller->recientes();
                exit;
            }
            break;
            
        case 'logout':
            session_start();
            session_destroy();
            header("Location: https://petsconnectcol.com/index.php");
            exit;
            break;
            
        case 'login_google':
            (new AuthController())->loginGoogle();
            exit;
            break;
            
        case 'logout':
            (new AuthController())->logout();
            exit;
            break;
            
        case 'google_callback':
            (new AuthController())->googleCallback();
            exit;
            break;
    }
}

// --- Manejo de restablecimiento de contraseña ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'guardar_nueva_contrasena') {
    error_log("Entró al bloque de restablecimiento");
    $auth = new AuthController();
    $auth->guardar_nueva_contrasena();
    exit;
}

// --- Definición de rutas protegidas ---
$routes = [
    "admin_home"    => ["role" => "admin",    "file" => "view/home/admin_home.php"],
    "guardian_home" => ["role" => "guardian", "file" => "view/home/guardian_home.php"],
    "fundacion_home" => ["role" => "fundacion", "file" => "view/home/fundacion_home.php"],
    "login"        => ["role" => "guest", "file" => "view/login/login.php"],
    "registro"     => ["role" => "guest", "file" => "view/login/register.php"],
    "recuperar_contrasena" => ["role" => "guest", "file" => "view/login/recuperarContraseña.php"],
    "restablecer_contrasena" => ["role" => "guest", "file" => "view/login/restablecerContraseña.php"],
    "landing" => ["role" => "guest", "file" => "view/login/landing.php"],
    "cartelera" => [
        "role" => "all",
        "file" => "view/cartelera/vistaFundacion.php",
        "params" => ["id"]
    ]
];

// --- Manejo de formularios POST ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    $controller = new UsuarioController();
    
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
            if (Roles::esAdmin($user["id_usuario"])) {
                $_SESSION["tipo_usuario"] = "admin";
                header("Location: index.php?page=admin_home");
                exit;
            } else if (Roles::esGuardian($user["id_usuario"])) {
                $_SESSION["tipo_usuario"] = "guardian";
                header("Location: index.php?page=guardian_home");
                exit;
            } else if (Roles::esFundacion($user["id_usuario"])) {
                $_SESSION["tipo_usuario"] = "fundacion";
                header("Location: index.php?page=fundacion_home");
                exit;
            } else {
                $_SESSION["tipo_usuario"] = "desconocido";
                $mensaje = "No se pudo determinar el rol del usuario";
                exit;
            }
        } else {
            $mensaje = "Usuario o contraseña incorrecta";
            require_once $routes["login"]["file"];
            exit;
        }
    }
}

// --- Si no hay sesión, mostrar landing o registro ---
$page = $_GET["page"] ?? "";

if (!isset($_SESSION["user"])) {
    if ($page === "registro") {
        require_once $routes["registro"]["file"];
    } elseif ($page === "login") {
        require_once $routes["login"]["file"];
    } elseif ($page === "recuperar_contrasena") {
        require_once $routes["recuperar_contrasena"]["file"];
    } elseif ($page === "restablecer_contrasena") {
        require_once $routes["restablecer_contrasena"]["file"];
    } else {
        require_once $routes["landing"]["file"];
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