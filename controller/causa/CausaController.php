<?php
require_once (__DIR__ . '/../../Model/Causa/CausaModel.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modeloCausa = new Causa();

    // REGISTRAR Causa
    if (isset($_POST['accion']) && $_POST['accion'] === 'registrar') {
        // Validar campos requeridos
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $meta = $_POST['meta'];
        $estado_causa = $_POST['estado_causa'];
        $fecha_creacion = date('Y-m-d H:i:s');
        $nit_fundacion = $_POST['nit_fundacion'];
        $tipo_causa = $_POST['tipo_causa'];
        $imagen_url = null;

        // Procesar imagen si se sube
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreImagen = uniqid() . '_' . basename($_FILES['imagen']['name']);
            $rutaDestino = __DIR__ . '/../../Public/images/causa/' . $nombreImagen;
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                $imagen_url = $nombreImagen;
            }
        }

        $resultado = $modeloCausa->add(
            $nombre,
            $descripcion,
            $meta,
            $estado_causa,
            $fecha_creacion,
            $nit_fundacion,
            $imagen_url,
            $tipo_causa
        );

         if ($resultado) {
            echo "Causa registrada correctamente";
        } else {
            echo "Error al registrar causa";
        }
        exit;
    }

    // ACTUALIZAR Causa
    if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
        $id_causa = $_POST['id_causa'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $meta = $_POST['meta'] ?? '';
        $estado_causa = $_POST['estado_causa'] ?? '';
        $nit_fundacion = $_POST['nit_fundacion'] ?? '';
        $tipo_causa = $_POST['tipo_causa'] ?? '';
        $imagen_url = $_POST['imagen_url'] ?? null;

        // Procesar nueva imagen si se sube
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreImagen = uniqid() . '_' . basename($_FILES['imagen']['name']);
            $rutaDestino = __DIR__ . '/../../Public/images/causa/' . $nombreImagen;
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                $imagen_url = $nombreImagen;
            }
        }

        $resultado = $modeloCausa->update(
            $id_causa,
            $nombre,
            $descripcion,
            $meta,
            $estado_causa,
            $nit_fundacion,
            $imagen_url,
            $tipo_causa
        );

        if ($resultado) {
            echo "Causa actualizada correctamente";
        } else {
            echo "Error al actualizar causa";
        }
        exit;
    }

    // ELIMINAR Causa
    if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar' && isset($_POST['id_causa'])) {
        $id_causa = $_POST['id_causa'];
        $resultado = $modeloCausa->delete($id_causa);
        echo $resultado ? "Causa eliminada correctamente" : "Error al eliminar Causa";
        exit;
    }
}
