<?php

namespace App\controller\mascota;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Mascota\Mascota;



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class MascotaController
{
    private $modeloMascota;

    public function __construct()
    {
        $this->modeloMascota = new Mascota();
    }

    // 1️⃣ REGISTRAR mascota
    public function registrar()
    {
        $id_mascota = $_POST['id_mascota'] ?? null;
        $nombre = $_POST['nombre'] ?? '';
        $edad_meses = $_POST['edad_meses'] ?? '';
        $sexo = $_POST['sexo'] ?? '';
        $imagen = null;

        // Procesar imagen si se sube
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreImagen = uniqid() . '_' . basename($_FILES['imagen']['name']);
            $rutaDestino = __DIR__ . '/../../Public/images/mascotas/' . $nombreImagen;
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                $imagen = $nombreImagen;
            }
        }

        $id_tipo_mascota = $_POST['id_tipo_mascota'] ?? '';
        $id_estado_adopcion = $_POST['id_estado_adopcion'] ?? '';
        $nit_fundacion = $_POST['nit_fundacion'] ?? '';

        // Validación básica
    if (empty($id_tipo_mascota)) {
        die("Error: Debes seleccionar un tipo de mascota válido");
    }



        $resultado = $this->modeloMascota->add(
            $id_mascota,
            $nombre,
            $edad_meses,
            $sexo,
            $imagen,
            $id_tipo_mascota,
            $nit_fundacion,
            $id_estado_adopcion,
            $nit_fundacion
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

        // Obtener la mascota actual para conservar la imagen existente
        $mascotaActual = $this->modeloMascota->getId($id_mascota);
        $imagen = $mascotaActual['imagen']; // Mantener la imagen actual por defecto

        // Procesar nueva imagen si se sube
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreImagen = uniqid() . '_' . basename($_FILES['imagen']['name']);
            $rutaDestino = __DIR__ . '/../../Public/images/mascotas/' . $nombreImagen;
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                $imagen = $nombreImagen;
                // Opcional: eliminar la imagen anterior si existe
                if (!empty($mascotaActual['imagen'])) {
                    $imagenAnterior = __DIR__ . '/../../Public/images/mascotas/' . $mascotaActual['imagen'];
                    if (file_exists($imagenAnterior)) {
                        unlink($imagenAnterior);
                    }
                }
            }
        }



        $id_tipo_mascota = $_POST['id_tipo_mascota'] ?? null;
        $id_estado_adopcion = $_POST['id_estado_adopcion'] ?? null;
        $nit_fundacion = $_POST['nit_fundacion'] ?? null;

        $resultado = $this->modeloMascota->update(
            $id_mascota,
            $nombre,
            $edad_meses,
            $sexo,
            $imagen,
            $id_tipo_mascota,
            $id_estado_adopcion,
            $nit_fundacion
        );

        echo $resultado ? "Mascota actualizada correctamente" : "Error al actualizar mascota";
    }

    // 3️⃣ ELIMINAR mascota 
    public function eliminar()
    {
        $id_mascota = $_POST['id_mascota'] ?? '';
        if (empty($id_mascota)) {
            echo "ID de mascota no proporcionado";
            return;
        }
        $resultado = $this->modeloMascota->delete($id_mascota);
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
