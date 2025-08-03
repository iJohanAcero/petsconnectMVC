<?php
namespace App\controller\mascota;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Mascota\Mascota;
use App\config\Roles;



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class MascotaController
{
    private $modeloMascota;
    private $id_usuario;

    public function __construct()
    {
        $this->modeloMascota = new Mascota();
        $this->id_usuario = $_SESSION['user']['id_usuario'] ?? null;
    }

    // 1️⃣ REGISTRAR mascota
    public function registrar()
    {
        $id_mascota = $_POST['id_mascota'] ?? null;
        $nombre = $_POST['nombre'] ?? '';
        $edad_meses = $_POST['edad_meses'] ?? '';
        $sexo = $_POST['sexo'] ?? '';
        $imagen = null;

        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
            $nombreImagen = uniqid() . '_' . $_FILES['imagen']['name'];
            $rutaDestino = __DIR__ . '/../../Public/images/mascotas/' . $nombreImagen;
            move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino);
            $imagen = $nombreImagen;
        }

        $id_tipo_mascota = $_POST['id_tipo_mascota'] ?? null;
        $id_estado_adopcion = $_POST['id_estado_adopcion'] ?? 2; // EN ADOPCIÓN por defecto

        // Fundaciones: su NIT viene por la sesión
        if (Roles::esFundacion($this->id_usuario)) {
            $nit_fundacion = $_SESSION['nit_fundacion'] ?? null;
        } elseif (Roles::esAdmin($this->id_usuario)) {
            $nit_fundacion = $_POST['nit_fundacion'] ?? null;
        } else {
            echo "Error: no autorizado";
            exit;
        }

        $resultado = $this->modeloMascota->add(
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
    }

    // 2️⃣ ACTUALIZAR mascota
    public function editar()
    {
        $id_mascota = $_POST['id_mascota'] ?? null;
        $nombre = $_POST['nombre'] ?? '';
        $edad_meses = $_POST['edad_meses'] ?? '';
        $sexo = $_POST['sexo'] ?? '';
        $imagen = $_POST['imagen_actual'] ?? null;

        if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] === UPLOAD_ERR_OK) {
            $nombreImagen = uniqid() . '_' . $_FILES['imagen']['name'];
            $rutaDestino = __DIR__ . '/../../Public/images/mascotas/' . $nombreImagen;
            move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaDestino);
            $imagen = $nombreImagen;

        }

        

        $id_tipo_mascota = $_POST['id_tipo_mascota'] ?? null;
        $id_estado_adopcion = $_POST['id_estado_adopcion'] ?? null;

        // Fundaciones y administradores pueden editar
        if (!Roles::esAdmin($this->id_usuario) && !Roles::esFundacion($this->id_usuario)) {
            echo "Error: no autorizado para editar";
            exit;
        }

        $resultado = $this->modeloMascota->update(
            $id_mascota,
            $nombre,
            $edad_meses,
            $sexo,
            $imagen,
            $id_tipo_mascota,
            $id_estado_adopcion
        );

        echo $resultado ? "Mascota actualizada correctamente" : "Error al actualizar mascota";
    }

    // 3️⃣ ELIMINAR mascota (solo administrador)
    public function eliminar()
    {
        if (!Roles::esAdmin($this->id_usuario)) {
            echo "Error: solo el administrador puede eliminar";
            exit;
        }

        $id = $_POST['id_mascota'] ?? null;
        if (!$id) {
            echo "ID de mascota no proporcionado";
            exit;
        }

        $resultado = $this->modeloMascota->delete($id);

        echo $resultado ? "Mascota eliminada correctamente" : "Error al eliminar mascota";
    }
}

// Router de acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $controller = new MascotaController();

    if ($accion === 'editar') {
        $controller->editar();
    } elseif ($accion === 'registrar') {
        $controller->registrar();
    } elseif ($accion === 'eliminar') {
        $controller->eliminar();
    }
}