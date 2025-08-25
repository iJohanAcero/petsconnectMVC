<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Publicacion\Publicacion;

$Modelo = new Publicacion();

// Validación: si no se pasa el ID, se muestra un mensaje y se detiene la ejecución
if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de publicación no especificado.</div>";
    exit;
}

$id = $_GET['id'];
$resultado = $Modelo->getId($id);

// Verificar si se obtuvieron datos
if (!$resultado || empty($resultado)) {
    echo "<div class='alert alert-danger'>Publicación no encontrada.</div>";
    exit;
}

// Verificar el formato de los datos retornados
if (is_array($resultado) && isset($resultado[0])) {
    // Si viene como array de arrays
    $publicacion = $resultado[0];
} elseif (is_array($resultado) && isset($resultado['id_publicacion'])) {
    // Si viene como array asociativo directo
    $publicacion = $resultado;
} else {
    echo "<div class='alert alert-danger'>Formato de datos inválido.</div>";
    exit;
}

// Verificar que tenemos los campos necesarios
if (!isset($publicacion['id_publicacion']) || 
    !isset($publicacion['titulo']) || 
    !isset($publicacion['contenido'])) {
    echo "<div class='alert alert-danger'>Datos de publicación incompletos.</div>";
    exit;
}
?>

<form id="form-editar-publicacion" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="accion" value="editar">
    <input type="hidden" name="id" value="<?= htmlspecialchars($publicacion['id_publicacion']) ?>">

    <!-- Información Básica -->
    <fieldset class="border p-4 mb-4 rounded" style="border-color: rgba(26, 19, 51, 0.25) !important;">
        <legend class="text-center fw-semibold mb-4" style="color: #1a1333; text-decoration: underline;">
            <i class="uil uil-info-circle me-2"></i>Información de la Publicación
        </legend>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="id_publicacion" class="form-label fw-semibold">
                    <i class="uil uil-tag me-1" style="color: #1a1333;"></i>ID Publicación
                </label>
                <input type="text" class="form-control border-2" id="id_publicacion" name="id_publicacion"
                    style="border-color: rgba(26, 19, 51, 0.3);"
                    value="<?= htmlspecialchars($publicacion['id_publicacion']) ?>" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="titulo" class="form-label fw-semibold">
                    <i class="uil uil-heading me-1" style="color: #1a1333;"></i>
                    Título <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control border-2" id="titulo" name="titulo"
                    style="border-color: rgba(26, 19, 51, 0.3);"
                    value="<?= htmlspecialchars($publicacion['titulo'] ?? '') ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="contenido" class="form-label fw-semibold">
                    <i class="uil uil-clipboard-notes me-1" style="color: #1a1333;"></i>
                    Contenido <span class="text-danger">*</span>
                </label>
                <textarea class="form-control border-2" id="contenido" name="contenido" rows="4" 
                    style="border-color: rgba(26, 19, 51, 0.3);" required><?= 
                    htmlspecialchars($publicacion['contenido'] ?? '') 
                ?></textarea>
            </div>
        </div>
    </fieldset>

    <!-- Imagen de la Publicación -->
    <fieldset class="border p-4 mb-4 rounded" style="border-color: rgba(26, 19, 51, 0.25) !important;">
        <legend class="text-center fw-semibold mb-4" style="color: #1a1333; text-decoration: underline;">
            <i class="uil uil-image me-2"></i>Imagen de la Publicación
        </legend>

        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="input-imagen" class="form-label fw-semibold">
                    <i class="uil uil-image me-1" style="color: #1a1333;"></i>
                    Nueva imagen (opcional)
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
            <figure class="publicacion-img-container">
                <?php
                $nombreImagen = !empty($publicacion['imagen']) ? $publicacion['imagen'] : 'no-image.png';
                $rutaImagen = htmlspecialchars($nombreImagen);
                ?>
                <img id="preview-imagen"
                    src="<?= $rutaImagen ?>"
                    alt="Imagen de la publicación"
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
            <i class="uil uil-save me-2"></i>Actualizar Publicación
        </button>
    </div>
</form>

