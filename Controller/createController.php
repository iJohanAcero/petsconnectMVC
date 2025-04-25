<?php
require_once __DIR__ . '/../Model/createModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $edad_meses = $_POST['edad_meses'];
    $sexo = $_POST['sexo'];
    $imagen = $_POST['imagen'];
    $id_tipo_mascota = $_POST['id_tipo_mascota'];
    $nit_fundacion = $_POST['nit_fundacion'];
    $num_serie_vacuna = $_POST['num_serie_vacuna'];

    $modelo = new createModel();
    $resultado = $modelo->crearMascotas($nombre, $edad_meses, $sexo, $imagen, $id_tipo_mascota, $nit_fundacion, $num_serie_vacuna, $id_mascota);

    if ($resultado) {
        header("Location: ../Views/productos/create.php?mensaje=Producto creado con éxito");
    } else {
        header("Location: ../Views/productos/create.php?mensaje=Error al crear el producto");
    }
}
?>
