<?php

require_once "../../Model/fundacion/FundacionModel.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modeloFundacion = new Fundacion();

    if (isset($_POST['accion']) && $_POST['accion'] === 'registrar_fundacion') {
        $nombre = htmlspecialchars($_POST['rep_nombre'] ?? '');
        $apellido = htmlspecialchars($_POST['rep_apellido'] ?? '');
        $contrasena = $_POST['rep_contrasena'] ?? '';
        $email = filter_var($_POST['rep_email'] ?? '', FILTER_SANITIZE_EMAIL);
        $direccion = htmlspecialchars($_POST['rep_direccion'] ?? '');
        $telefono = htmlspecialchars($_POST['rep_telefono'] ?? '');
        $nombre_fundacion = htmlspecialchars($_POST['fund_nombre'] ?? ''); // ← ¡NUEVO!
        $nit_fundacion = htmlspecialchars($_POST['fund_nit'] ?? '');

        if (empty($nombre) || empty($apellido) || empty($contrasena) || empty($email) || empty($nombre_fundacion) || empty($nit_fundacion)) {
            echo "Todos los campos son obligatorios";
            exit;
        }

        $resultado = $modeloFundacion->registrarFundacion($nombre, $apellido, $contrasena, $email, $direccion, $telefono, $nombre_fundacion, $nit_fundacion);

        echo $resultado ? "Fundación registrada correctamente" : "Error al registrar fundación";
        exit;
    }

    //  ACTUALIZAR fundacion
    if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
        $nit = htmlspecialchars($_POST['nit'] ?? '');
        $nombre = htmlspecialchars($_POST['nombre'] ?? '');
        $apellido = htmlspecialchars($_POST['apellido'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $direccion = htmlspecialchars($_POST['direccion'] ?? '');
        $telefono = htmlspecialchars($_POST['telefono'] ?? '');

        if (empty($nit) || empty($nombre) || empty($apellido) || empty($email)) {
            echo "Todos los campos son obligatorios";
            exit;
        }

        $resultado = $modeloFundacion->updateFundacion($nit, $nombre, $apellido, $email, $direccion, $telefono);

        if ($resultado) {
            echo "Fundación actualizada correctamente";
            exit;
        } else {
            echo "Error al actualizar fundación";
            exit;
        }
    }

    // Eliminar fundacion
    if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar' && isset($_POST['nit'])) {
        $nit = $_POST['nit'];
        $resultado = $modeloFundacion->delete($nit);

        if ($resultado) {
            echo "Fundación eliminada correctamente";
        } else {
            echo "Error al eliminar fundación";
        }
        exit;
    }
}
