<?php
require_once __DIR__ .  '/../Model/mascotasModel.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $modelo = new mascotasModel();
    $resultado = $modelo->eliminarMascotas($id);

    if ($resultado) {
        header("Location: ../Views/Pages/index.php?mensaje=Mascota eliminada con éxito");
    } else {
        header("Location: ../Views/Pages/index.php?mensaje=Error al eliminar el mascota");
    }
} else {
    header("Location: ../Views/Pages/index.php?mensaje=ID no proporcionado");
}

?>