<?php
require_once('../../Model/publicacion/PublicacionModel.php');

session_start(); // Para manejar mensajes entre redirecciones

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modeloPublicacion = new Publicacion();

    // 1️⃣ REGISTRAR Publicacion
    if (isset($_POST['accion']) && $_POST['accion'] === 'registrar') {
        $titulo = $_POST['titulo'];
        $contenido = $_POST['contenido'];

        // Procesar imagen
        $imagen = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreImagen = uniqid() . '_' . $_FILES['imagen']['name'];
            $rutaDestino = '../../Public/images/eventos_fundacion/' . $nombreImagen;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino);
            $imagen = $nombreImagen;
        }

        $fecha = date('Y-m-d H:i:s');
        $nit_fundacion = $_POST['nit_fundacion'];

        $resultado = $modeloPublicacion->add($titulo, $contenido, $imagen, $fecha, $nit_fundacion);

        if ($resultado) {
            echo "Publicacion registrada correctamente";
        } else {
            echo "Error al registrar publicacion";
        }
        exit;
    }

    // 2️⃣ ACTUALIZAR Publicacion
    if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
        $id = $_POST['id'];
        $titulo = $_POST['titulo'];
        $contenido = $_POST['contenido'];

        $resultado = $modeloPublicacion->update($id, $titulo, $contenido);

        if ($resultado) {
            echo "Publicacion actualizada correctamente";
        } else {
            echo "Error al actualizar publicacion";
        }
        exit;
    }

    // 3️⃣ ELIMINAR Publicacion
    if (isset($_POST['eliminar']) && isset($_POST['id'])) {
        $id = $_POST['id'];
        $resultado = $modeloPublicacion->delete($id);

        if ($resultado) {
            echo "Publicacion eliminado correctamente";
        } else {
            echo "Error al eliminar Publicacion";
        }
        exit;
    }
}

// Si no es POST o no hay acción válida
echo "Acción no válida o método no permitido";
exit;
