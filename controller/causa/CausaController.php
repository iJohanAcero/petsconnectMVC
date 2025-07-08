<?php
require_once('../../Model/causa/CausaModel.php');
session_start();

$modeloCausa = new Causa();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // REGISTRAR causa
    if (isset($_POST['accion']) && $_POST['accion'] === 'registrar') {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $meta = $_POST['meta'];
        $estado_causa = $_POST['estado_causa'];
        $nit_fundacion = $_POST['nit_fundacion'];
        $imagen_url = $_POST['imagen_url'];
        $tipo_causa = $_POST['tipo_causa'];

        $resultado = $modeloCausa->add($nombre, $descripcion, $meta, $estado_causa, $nit_fundacion, $imagen_url, $tipo_causa);

        echo $resultado ? "Causa registrada correctamente" : "Error al registrar causa";
        exit;
    }

    // ACTUALIZAR causa
    if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
        $id_causa = $_POST['id_causa'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $meta = $_POST['meta'];
        $estado_causa = $_POST['estado_causa'];
        $nit_fundacion = $_POST['nit_fundacion'];
        $imagen_url = $_POST['imagen_url'];
        $tipo_causa = $_POST['tipo_causa'];

        $resultado = $modeloCausa->update($id_causa, $nombre, $descripcion, $meta, $estado_causa, $nit_fundacion, $imagen_url, $tipo_causa);

        echo $resultado ? "Causa actualizada correctamente" : "Error al actualizar causa";
        exit;
    }

    // ELIMINAR causa
    if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar' && isset($_POST['id_causa'])) {
        $id_causa = $_POST['id_causa'];
        $resultado = $modeloCausa->delete($id_causa);

        echo $resultado ? "Causa eliminada correctamente" : "Error al eliminar causa";
        exit;
    }
}

echo "Acción no válida o método no permitido";
exit;
