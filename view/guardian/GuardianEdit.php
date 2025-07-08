<?php
require_once("../../Model/perfil/PerfilModel.php");

$Modelo = new PerfilModel();

// Validación: si no se pasa el ID, se muestra un mensaje y se detiene la ejecución
if (!isset($_GET['id'])) {
    echo "ID de Perfil no especificado.";
    exit;
}

$id = $_GET['id']; // Ojo: era `$Id`, pero luego se usa `$id` en getId. Uniformamos.
$perfil = $Modelo->getPerfilPorUsuario($id);

// Si no se encuentra el perfil con ese ID, se avisa y se detiene
if (!$perfil || empty($perfil)) {
    echo "Perfil no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Actualizar perfil</title>
</head>

<body>
    <!-- Contenedor principal de Bootstrap -->
    <div class="container mt-1">
        <!-- Formulario con clases de Bootstrap -->
        <form id="form-editar-perfilGuardian" method="POST" action="/petsconnectMVC/controller/perfil/PerfilController.php">

            <!-- Campo oculto para el ID  -->
            <input type="hidden" name="id" value="<?= $perfil['id_usuario']; ?>">


            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= $perfil['nombre']; ?>" required>
            </div>


            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <input type="text" name="descripcion" class="form-control" value="<?= $perfil['descripcion']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="form-label">Preferencia</label>
                <input type="text" name="preferencia" class="form-control" value="<?= $perfil['preferencia']; ?>" required>
            </div>

            <input type="hidden" name="accion" value="editar">

            <!-- Botón de acción -->
            <button type="submit" class="btn btn-primary">Actualizar</button>

            <!-- Enlace para regresar -->
            <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal" aria-label="Close">← Volver a la lista</button>
        </form>
    </div>
</body>

</html>