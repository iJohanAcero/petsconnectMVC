<?php

namespace App\controller\perfil;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Perfil\Perfil;
use Exception;

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
        try {
            $id = $_POST['id'] ?? $this->id_usuario;
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $preferencia = $_POST['preferencia'] ?? '';

            // Obtener redes sociales del formulario
            $redes_sociales = $_POST['redes_sociales'] ?? [];

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
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
                exit;
            }

            // Actualizar el perfil
            $resultadoGuardian = $this->perfilModel->actualizarPerfilGuardian($id, $nombre, $descripcion, $preferencia, $imagen, $redes_sociales);
            $resultadoFundacion = $this->perfilModel->actualizarPerfilFundacion($id, $nombre, $descripcion, $preferencia, $imagen, $redes_sociales);

            if ($resultadoGuardian || $resultadoFundacion) {
                echo json_encode('Perfil actualizado correctamente.');
            } else {
                echo json_encode('Error al actualizar el perfil.');
            }
        } catch (Exception $e) {
            echo json_encode('Error: ' . $e->getMessage());
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
