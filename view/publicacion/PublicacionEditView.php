<?php
require_once("../../Model/publicacion/PublicacionModel.php");

$Modelo = new Publicacion();

// Validación: si no se pasa el ID, se muestra un mensaje y se detiene la ejecución
if (!isset($_GET['id'])) {
    echo "ID de publicacion no especificado.";
    exit;
}

$id = $_GET['id']; // Ojo: era `$Id`, pero luego se usa `$id` en getId. Uniformamos.
$publicacion = $Modelo->getId($id);

// Si no se encuentra el Publicacion con ese ID, se avisa y se detiene
if (!$publicacion || empty($publicacion)) {
    echo "publicacion no encontrado.";
    exit;
}

$publicacion = $publicacion[0]; // Tomamos el primer registro si viene en forma de arreglo
?>

<body>
    <!-- Contenedor principal de Bootstrap -->
    <div class="container mt-1">
        <!-- Formulario con clases de Bootstrap -->
        <form id="form-editar-publicacion" method="POST" action="/petsconnectMVC/controller/publicacion/PublicacionController.php">

            <!-- Campo oculto para el ID  -->
            <input type="hidden" name="id" value="<?= $publicacion['id_publicacion']; ?>">


            <div class="mb-3">
                <label class="form-label">Titulo</label>
                <input type="text" name="titulo" class="form-control" value="<?= $publicacion['titulo']; ?>" required>
            </div>


            <div class="mb-3">
                <label class="form-label">Contenido</label>
                <input type="text" name="contenido" class="form-control" value="<?= $publicacion['contenido']; ?>" required>
            </div>

            <input type="hidden" name="accion" value="editar">

            <!-- Botón de acción -->
            <button type="submit" class="btn btn-primary">Actualizar</button>

            <!-- Enlace para regresar -->
            <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal" aria-label="Close">← Volver a la lista</button>
        </form>
    </div>
</body>

