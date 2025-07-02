<?php
require_once('../../Model/mascota/MascotaModel.php');
require_once('../../config/roles.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener ID de usuario desde la sesión
$id_usuario = $_SESSION['user']['id_usuario'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modeloMascota = new Mascota();

    // 1️⃣ REGISTRAR mascota
    if (isset($_POST['accion']) && $_POST['accion'] === 'registrar') {
        $id_mascota = $_POST['id_mascota'];
        $nombre = $_POST['nombre'];
        $edad_meses = $_POST['edad_meses'];
        $sexo = $_POST['sexo'];
        $imagen = null;
        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === 0) {
            $imagen = $_FILES["imagen"]["name"];
            move_uploaded_file($_FILES["imagen"]["tmp_name"], __DIR__ . "/../../public/images/mascotas/" . $imagen);
        }
        $id_tipo_mascota = $_POST['id_tipo_mascota'];
        $id_estado_adopcion = $_POST['id_estado_adopcion'] ?? 2; // EN ADOPCIÓN por defecto

        // Fundaciones: su NIT viene por la sesión
        if (esFundacion($id_usuario)) {
            $nit_fundacion = $_SESSION['nit_fundacion'] ?? null;
        } elseif (esAdmin($id_usuario)) {
            $nit_fundacion = $_POST['nit_fundacion'];
        } else {
            echo "Error: no autorizado";
            exit;
        }

        $resultado = $modeloMascota->add(
            $id_mascota,
            $nombre,
            $edad_meses,
            $sexo,
            $imagen,
            $id_tipo_mascota,
            $nit_fundacion,
            $id_estado_adopcion
        );

        echo $resultado ? "Mascota registrada correctamente" : "Error al registrar mascota";
        exit;
    }

    // 2️⃣ ACTUALIZAR mascota
    if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
        $id_mascota = $_POST['id_mascota'];
        $nombre = $_POST['nombre'];
        $edad_meses = $_POST['edad_meses'];
        $sexo = $_POST['sexo'];
        $imagen = $_POST['imagen_actual'] ?? null;
        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === 0) {
            $imagen = $_FILES["imagen"]["name"];
            move_uploaded_file($_FILES["imagen"]["tmp_name"], __DIR__ . "/../../public/images/mascotas/" . $imagen);
        }
        $id_tipo_mascota = $_POST['id_tipo_mascota'];
        $id_estado_adopcion = $_POST['id_estado_adopcion'];

        // Fundaciones y administradores pueden editar
        if (!esAdmin($id_usuario) && !esFundacion($id_usuario)) {
            echo "Error: no autorizado para editar";
            exit;
        }

        $resultado = $modeloMascota->update(
            $id_mascota,
            $nombre,
            $edad_meses,
            $sexo,
            $imagen,
            $id_tipo_mascota,
            $id_estado_adopcion
        );

        echo $resultado ? "Mascota actualizada correctamente" : "Error al actualizar mascota";
        exit;
    }

    // 3️⃣ ELIMINAR mascota (solo administrador)
    if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar' && isset($_POST['id_mascota'])) {
        if (!esAdmin($id_usuario)) {
            echo "Error: solo el administrador puede eliminar";
            exit;
        }

        $id = $_POST['id_mascota'];
        $resultado = $modeloMascota->delete($id);

        echo $resultado ? "Mascota eliminada correctamente" : "Error al eliminar mascota";
        exit;
    }
}

// Si no es POST o no hay acción válida
echo "Acción no válida o método no permitido";
exit;
