<?php

require_once "../../Model/fundacion/FundacionModel.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modeloFundacion = new Fundacion();

    // REGISTRAR fundación
    if (isset($_POST['accion']) && $_POST['accion'] === 'registrar_fundacion') {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $contrasena = $_POST['contrasena'];
        $email = $_POST['email'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $nit_fundacion = $_POST['nit_fundacion'];

        $resultado = $modeloFundacion->registrarFundacion($nombre, $apellido, $contrasena, $email, $direccion, $telefono, $nit_fundacion);

        if ($resultado) {
            echo "Fundación registrada correctamente";
        } else {
            echo "Error al registrar fundación";
        }
        exit;
    }

    //  ACTUALIZAR fundacion
    if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $contrasena = $_POST['contrasena'];
        $email = $_POST['email'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $nit_fundacion = $_POST['nit_fundacion'];

        $resultado = $modeloFundacion->updateFundacion($nombre, $apellido, $contrasena, $email, $direccion, $telefono, $nit_fundacion);

        if ($resultado) {
            echo "Producto actualizado correctamente";
        } else {
            echo "Error al actualizar producto";
        }
        exit;
    }

    // Eliminar fundacion
    if (isset($_POST['eliminar']) && isset($_POST['nit'])) {
        $nit = $_POST['nit'];
        $resultado = $modeloFundacion->delete($nit);

        if ($resultado) {
            echo "Fundacion eliminada correctamente";
        } else {
            echo "Error al eliminar Fundacion";
        }
        exit;
    }
}
