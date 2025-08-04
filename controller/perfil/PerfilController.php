<?php
namespace App\controller\perfil;

require_once __DIR__ . '/../../vendor/autoload.php';
use App\Model\Perfil\Perfil;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class PerfilController
{
    private $perfilModel;
    private $id_usuario;

    public function __construct()
    {
        $this->perfilModel = new Perfil();
        $this->id_usuario = $_SESSION["user"]["id_usuario"] ?? null;
    }

    public function editar()
    {
        $id = $_POST['id'] ?? $this->id_usuario;
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $preferencia = $_POST['preferencia'] ?? '';

        // Obtener imagen actual del perfil
        $perfil = $this->perfilModel->getPerfilPorUsuario($id);
        $imagen_actual = $perfil['imagen'] ?? null;

        // Procesar imagen
        $imagen = $imagen_actual;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreImagen = uniqid() . '_' . $_FILES['imagen']['name'];
            $rutaDestino = __DIR__ . '/../../Public/images/perfil/' . $nombreImagen;
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                $imagen = $nombreImagen;
            }
        }

        // Validar campos obligatorios
        if (empty($nombre) || empty($descripcion) || empty($preferencia)) {
            echo "Todos los campos son obligatorios.";
            exit;
        }

        // Actualizar el perfil (puede ser guardian o fundación)
        $resultadoGuardian = $this->perfilModel->actualizarPerfilGuardian($id, $nombre, $descripcion, $preferencia, $imagen);
        $resultadoFundacion = $this->perfilModel->actualizarPerfilFundacion($id, $nombre, $descripcion, $preferencia, $imagen);

        if ($resultadoGuardian || $resultadoFundacion) {
            echo "Perfil actualizado correctamente.";
        } else {
            echo "Error al actualizar el perfil.";
        }
    }
}

// Router de acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $controller = new PerfilController();

    if ($accion === 'editar') {
        $controller->editar();
    }
}