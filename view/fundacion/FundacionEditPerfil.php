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
        <form id="form-editar-perfilFundacion" method="POST">
            <input type="hidden" name="id" value="<?= $perfil['id_usuario']; ?>">
            <input type="hidden" name="accion" value="editar">

            <div class="mb-3 text-center">
                <img id="preview-imagen"
                    src="/petsconnectMVC/Public/images/perfil/<?= htmlspecialchars($perfil['imagen'] ?? 'default.jpg'); ?>"
                    alt="Imagen de perfil"
                    class="rounded-circle img-fluid"
                    style="width: 150px; height: 150px; object-fit: cover;">
            </div>

            <!-- Input de imagen -->
            <div class="mb-3">
                <label class="form-label">Actualizar imagen de perfil</label>
                <input type="file" name="imagen" id="input-imagen" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control text-center"
                    value="<?= htmlspecialchars($perfil['nombre']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control text-center" rows="3" required><?= htmlspecialchars($perfil['descripcion']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Preferencia de mascotas</label>
                <input type="text" name="preferencia" class="form-control text-center"
                    value="<?= htmlspecialchars($perfil['preferencia']); ?>" required>
            </div>

            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary me-2">Guardar cambios</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
</body>

</html>