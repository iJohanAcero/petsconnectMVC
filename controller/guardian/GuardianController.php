<?php 
namespace App\Controller\Guardian;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Guardian\Guardian;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class GuardianController {
    private $modeloGuardian;

    public function __construct() {
        $this->modeloGuardian = new Guardian();
    }

    public function registrar() {
        $nombre = htmlspecialchars($_POST['nombre'] ?? '');
        $apellido = htmlspecialchars($_POST['apellido'] ?? '');
        $contrasena = $_POST['contrasena'] ?? '';
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $direccion = htmlspecialchars($_POST['direccion'] ?? '');
        $telefono = htmlspecialchars($_POST['telefono'] ?? '');

        if (empty($nombre) || empty($apellido) || empty($contrasena) || empty($email)) {
            echo "Todos los campos son obligatorios";
            return;
        }

        $resultado = $this->modeloGuardian->registrarGuardian(
            $nombre,
            $apellido,
            $contrasena,
            $email,
            $direccion,
            $telefono
        );

        echo $resultado ? "Guardian registrado correctamente" : "Error al registrar guardian";
    }

    // Editar guardian 
    public function editar() {
        $id_usuario = htmlspecialchars($_POST['id_usuario'] ?? ''); // Cambiado de 'id' a 'id_usuario'
        $nombre = htmlspecialchars($_POST['nombre'] ?? '');
        $apellido = htmlspecialchars($_POST['apellido'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $direccion = htmlspecialchars($_POST['direccion'] ?? '');
        $telefono = htmlspecialchars($_POST['telefono'] ?? '');

        if (empty($id_usuario) || empty($nombre) || empty($apellido) || empty($email)) {
            echo "Todos los campos son obligatorios";
            return;
        }

        $resultado = $this->modeloGuardian->updateGuardian(
            $id_usuario, 
            $nombre,
            $apellido,
            $email,
            $direccion,
            $telefono
        );

        echo $resultado ? "Guardian actualizado correctamente" : "Error al actualizar guardian";
    }

    // Eliminar guardian 
    public function eliminar() {
        $id_usuario = $_POST['id_usuario'] ?? '';

        if (empty($id_usuario)) {
            echo "ID de usuario del guardian no proporcionado";
            return;
        }

        $resultado = $this->modeloGuardian->delete($id_usuario);

        if ($resultado === 'constraint_error') {
            echo "No se puede eliminar el guardian debido a restricciones de base de datos. Verifique que no tenga datos relacionados.";
        } elseif ($resultado) {
            echo "Guardian eliminado correctamente";
        } else {
            echo "Error al eliminar guardian";
        }
    }
}

// Router de acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $controller = new GuardianController();

    if ($accion === 'registrar_guardian') {
        $controller->registrar();
    } elseif ($accion === 'editar') {
        $controller->editar();
    } elseif ($accion === 'eliminar') {
        $controller->eliminar();
    } 
}