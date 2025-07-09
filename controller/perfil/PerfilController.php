<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../../Model/perfil/PerfilModel.php');


$perfilModel = new PerfilModel();
$id = $_SESSION["user"]["id_usuario"];

// Obtener imagen actual
    $perfil = $perfilModel->getPerfilPorUsuario($id);
    $imagen = $perfil['imagen'];

// Actualizar perfil de guardian
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $preferencia = $_POST['preferencia'];
     // Procesar imagen
        $imagen = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreImagen = uniqid() . '_' . $_FILES['imagen']['name'];
            $rutaDestino = '../../Public/images/perfil/' . $nombreImagen;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino);
            $imagen = $nombreImagen;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            $imagen = $nombreImagen; // <-- Solo se reemplaza si se subió una nueva
        }
        } else {
            $imagen = $perfil['imagen']; // Mantener la imagen actual si no se subió una nueva
        }

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($descripcion) || empty($preferencia)) {
        echo "Todos los campos son obligatorios.";
        exit;
    }

    // Actualizar el perfil
    $resultado = $perfilModel->actualizarPerfilGuardian($id, $nombre, $descripcion, $preferencia, $imagen);

    if ($resultado) {
        echo "Perfil actualizado correctamente.";
        exit;
    } else {
        echo "Error al actualizar el perfil.";
    }
}
?>