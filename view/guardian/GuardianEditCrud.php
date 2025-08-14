<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Guardian\Guardian;

$id_usuario = $_GET['id_usuario'] ?? '';

if (empty($id_usuario)) {
    echo "<div class='alert alert-danger'>ID de usuario no proporcionado</div>";
    exit;
}

$modelo = new Guardian();
$guardian = $modelo->getGuardianById($id_usuario);

if (!$guardian) {
    echo "<div class='alert alert-danger'>Guardian no encontrado</div>";
    exit;
}
?>

<form id="form-editar-guardian">
    <input type="hidden" name="accion" value="editar">
    <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($guardian['id_usuario']); ?>">

    <!-- Datos del Guardian -->
    <fieldset class="border p-3 mb-4 rounded">
        <legend class="text-center text-secondary fw-semibold" style="text-decoration: underline;">
            Editar Datos del Guardian
        </legend>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="edit_guardian_nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_guardian_nombre" name="nombre"
                    value="<?php echo htmlspecialchars($guardian['nombre']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="edit_guardian_apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_guardian_apellido" name="apellido"
                    value="<?php echo htmlspecialchars($guardian['apellido']); ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="edit_guardian_email" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="edit_guardian_email" name="email"
                    value="<?php echo htmlspecialchars($guardian['email']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="edit_guardian_telefono" class="form-label">Teléfono</label>
                <input type="tel" class="form-control" id="edit_guardian_telefono" name="telefono"
                    value="<?php echo htmlspecialchars($guardian['telefono']); ?>"
                    placeholder="Ejemplo: +57 300 123 4567">
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="edit_guardian_direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="edit_guardian_direccion" name="direccion"
                    value="<?php echo htmlspecialchars($guardian['direccion']); ?>"
                    placeholder="Dirección completa">
            </div>
        </div>
    </fieldset>

    <!-- Información del perfil -->
    <div class="alert alert-info" role="alert">
        <i class="bi bi-info-circle"></i>
        <strong>ID Usuario:</strong> <?php echo htmlspecialchars($guardian['id_usuario']); ?> |
        <strong>ID Perfil:</strong> <?php echo htmlspecialchars($guardian['id_perfil']); ?>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Actualizar Guardian
        </button>
    </div>
</form>