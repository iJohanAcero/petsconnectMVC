<?php

require_once ('../../Modelo/FundacionModel.php');

// Validamos que venga una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Instancia del modelo Usuario
    $usuarioModel = new Usuario();

    // Capturamos y limpiamos los datos del formulario
    $nombre = trim($_POST['nombre']);
    $apellido = "N/A"; // Valor por defecto
    $email = trim($_POST['email']);
    $direccion = trim($_POST['direccion']);
    $telefono = trim($_POST['telefono']);
    $nit = trim($_POST['nit_fundacion']);
    $contrasena = password_hash("123456", PASSWORD_DEFAULT);


    // Llamada al método que ejecuta el procedimiento almacenado
    $resultado = $usuarioModel->registrarFundacion($nombre, $apellido, $contrasena, $email, $direccion, $telefono, $nit);

    if ($resultado) {
        // Redirección en caso de éxito
        header("Location: ../view/fundacion/Fundacion_view.php?mensaje=registro_exitoso");
        exit;
    } else {
        // Redirección con mensaje de error
        header("Location: ../view/fundacion/Fundacion_view.php?mensaje=error_registro");
        exit;
    }
} else {
    // Redirección en caso de acceso sin POST
    header("Location: ../view/fundacion/Fundacion_view.php");
    exit;
}
