<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Causa\Causa;

$Modelo = new Causa();

// Validación: si no se pasa el ID, se muestra un mensaje y se detiene la ejecución
if (!isset($_GET['id'])) {
    echo "ID de causa no especificado.";
    exit;
}

$id = $_GET['id']; // Ojo: era `$Id`, pero luego se usa `$id` en getId. Uniformamos.
$causa = $Modelo->getId($id);

// Si no se encuentra el Causa con ese ID, se avisa y se detiene
if (!$causa || empty($causa)) {
    echo "Causa no encontrada.";
    exit;
}
?>

<form id="form-editar-causa" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="accion" value="editar">
    <input type="hidden" name="id_causa" value="<?= htmlspecialchars($causa['id_causa']) ?>">

    <!-- Información Básica -->
    <fieldset class="border p-4 mb-4 rounded" style="border-color: rgba(26, 19, 51, 0.25) !important;">
        <legend class="text-center fw-semibold mb-4" style="color: #1a1333; text-decoration: underline;">
            <i class="uil uil-info-circle me-2"></i>Información Básica de la Causa
        </legend>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="id_causa" class="form-label fw-semibold">
                    <i class="uil uil-tag me-1" style="color: #1a1333;"></i>ID Causa
                </label>
                <input type="text" class="form-control border-2" id="id_causa" name="id_causa"
                    style="border-color: rgba(26, 19, 51, 0.3);"
                    value="<?= htmlspecialchars($causa['id_causa']) ?>" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="nombre" class="form-label fw-semibold">
                    <i class="uil uil-tag me-1" style="color: #1a1333;"></i>
                    Nombre <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control border-2" id="nombre" name="nombre"
                    style="border-color: rgba(26, 19, 51, 0.3);"
                    value="<?= htmlspecialchars($causa['nombre']) ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="descripcion" class="form-label fw-semibold">
                    <i class="uil uil-align-left me-1" style="color: #1a1333;"></i>
                    Descripción <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control border-2" id="descripcion" name="descripcion"
                    style="border-color: rgba(26, 19, 51, 0.3);"
                    value="<?= htmlspecialchars($causa['descripcion']) ?>" required>
            </div>
        </div>
    </fieldset>

    <!-- Estado y Metas -->
    <fieldset class="border p-4 mb-4 rounded" style="border-color: rgba(26, 19, 51, 0.25) !important;">
        <legend class="text-center fw-semibold mb-4" style="color: #1a1333; text-decoration: underline;">
            <i class="uil uil-chart-bar me-2"></i>Estado y Metas
        </legend>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="meta" class="form-label fw-semibold">
                    <i class="uil uil-bill me-1" style="color: #1a1333;"></i>
                    Meta
                </label>
                <input type="text" class="form-control border-2" id="meta" name="meta"
                    style="border-color: rgba(26, 19, 51, 0.3);"
                    value="<?= htmlspecialchars($causa['meta']) ?>" readonly>
                <div class="form-text mt-2">
                    <i class="uil uil-info-circle me-1" style="color: #1a1333;"></i>
                    La meta no se puede modificar
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="estado_causa" class="form-label fw-semibold">
                    <i class="uil uil-shield-check me-1" style="color: #1a1333;"></i>
                    Estado de causa <span class="text-danger">*</span>
                </label>
                <select class="form-select border-2" id="estado_causa" name="estado_causa"
                    style="border-color: rgba(26, 19, 51, 0.3);" required>
                    <option value="">Seleccione...</option>
                    <option value="activa" <?= ($causa['estado_causa'] ?? '') == 'activa' ? 'selected' : '' ?>>Activa</option>
                    <option value="en pausa" <?= ($causa['estado_causa'] ?? '') == 'en pausa' ? 'selected' : '' ?>>En pausa</option>
                    <option value="cumplida" <?= ($causa['estado_causa'] ?? '') == 'cumplida' ? 'selected' : '' ?>>Cumplida</option>
                    <option value="cancelada" <?= ($causa['estado_causa'] ?? '') == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                </select>
            </div>
        </div>
    </fieldset>

    <!-- Información Adicional -->
    <fieldset class="border p-4 mb-4 rounded" style="border-color: rgba(26, 19, 51, 0.25) !important;">
        <legend class="text-center fw-semibold mb-4" style="color: #1a1333; text-decoration: underline;">
            <i class="uil uil-file-info-alt me-2"></i>Información Adicional
        </legend>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="fecha_creacion" class="form-label fw-semibold">
                    <i class="uil uil-calendar-alt me-1" style="color: #1a1333;"></i>
                    Fecha de creación
                </label>
                <input type="text" class="form-control border-2" id="fecha_creacion" name="fecha_creacion"
                    style="border-color: rgba(26, 19, 51, 0.3);"
                    value="<?= htmlspecialchars($causa['fecha_creacion']) ?>" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="nit_fundacion" class="form-label fw-semibold">
                    <i class="uil uil-building me-1" style="color: #1a1333;"></i>
                    NIT Fundación
                </label>
                <input type="text" class="form-control border-2" id="nit_fundacion" name="nit_fundacion"
                    style="border-color: rgba(26, 19, 51, 0.3);"
                    value="<?= htmlspecialchars($causa['nit_fundacion']) ?>" readonly>
                <div class="form-text mt-2">
                    <i class="uil uil-info-circle me-1" style="color: #1a1333;"></i>
                    El NIT no se puede modificar
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="tipo_causa" class="form-label fw-semibold">
                    <i class="uil uil-bookmark me-1" style="color: #1a1333;"></i>
                    Tipo de causa
                </label>
                <input type="text" class="form-control border-2" id="tipo_causa" name="tipo_causa"
                    style="border-color: rgba(26, 19, 51, 0.3);"
                    value="<?= htmlspecialchars($causa['tipo_causa']) ?>" readonly>
                <div class="form-text mt-2">
                    <i class="uil uil-info-circle me-1" style="color: #1a1333;"></i>
                    El tipo de causa no se puede modificar
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="input-imagen" class="form-label fw-semibold">
                    <i class="uil uil-image me-1" style="color: #1a1333;"></i>
                    Imagen de la causa
                </label>
                <input type="file" name="imagen" id="input-imagen" class="form-control border-2"
                    style="border-color: rgba(26, 19, 51, 0.3);" accept="image/*">
                <div class="form-text mt-2">
                    <i class="uil uil-info-circle me-1" style="color: #1a1333;"></i>
                    Formato recomendado: JPG, PNG. Tamaño máximo: 5MB
                </div>
            </div>
        </div>
    </fieldset>

    <!-- Vista previa de la imagen -->
    <div class="row mb-4">
        <div class="col-md-12 text-center">
            <figure class="causa-img-container">
                <?php
                $nombreImagen = !empty($causa['imagen_url']) ? $causa['imagen_url'] : 'default.jpg';
                $rutaImagen = htmlspecialchars($nombreImagen);
                ?>
                <img id="preview-imagen"
                    src="<?= $rutaImagen ?>"
                    alt="Imagen de la causa"
                    style="object-fit: cover; max-height: 300px; border: 3px solid #1a1333;"
                    class="img-fluid rounded shadow-sm">
                <figcaption class="mt-2 text-muted fw-semibold">Vista previa de la imagen actual</figcaption>
            </figure>
        </div>
    </div>

    <!-- Información adicional destacada -->
    <div class="alert border-0" style="background-color: rgba(26, 19, 51, 0.05); border-left: 4px solid #1a1333 !important;">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <i class="uil uil-lightbulb fs-4" style="color: #1a1333;"></i>
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="alert-heading mb-1" style="color: #1a1333;">¡Recordatorio!</h6>
                <p class="mb-0 small">Si no selecciona una nueva imagen, se mantendrá la actual.
                    Los cambios se aplicarán inmediatamente al guardar.</p>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light border-0 p-4">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="uil uil-times me-2"></i>Cancelar
        </button>
        <button type="submit" class="btn btn-primary" style="background-color: #1a1333; border-color: #1a1333;">
            <i class="uil uil-save me-2"></i>Actualizar Causa
        </button>
    </div>
</form>