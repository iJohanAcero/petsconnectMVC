<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Mascota\Mascota;

try {
    if (!isset($_GET['id'])) {
        echo "<div class='alert alert-danger'>ID de mascota no especificado.</div>";
        exit;
    }

    $id = $_GET['id'];
    $Modelo = new Mascota();
    $resultado = $Modelo->getId($id);

    // Verificar si se obtuvieron datos
    if (!$resultado || empty($resultado)) {
        echo "<div class='alert alert-danger'>Mascota no encontrada.</div>";
        exit;
    }

    // Verificar el formato de los datos retornados
    if (is_array($resultado) && isset($resultado[0])) {
        // Si viene como array de arrays
        $mascota = $resultado[0];
    } elseif (is_array($resultado) && isset($resultado['id_mascota'])) {
        // Si viene como array asociativo directo
        $mascota = $resultado;
    } else {
        echo "<div class='alert alert-danger'>Formato de datos inválido.</div>";
        exit;
    }

    // Verificar que tenemos los campos necesarios
    if (
        !isset($mascota['id_mascota']) ||
        !isset($mascota['nombre']) ||
        !isset($mascota['edad_meses'])
    ) {
        echo "<div class='alert alert-danger'>Datos de mascota incompletos.</div>";
        exit;
    }

    $tipos = $Modelo->getTiposMascota();
    $estados = $Modelo->getEstadosAdopcion();

    session_start();
    $esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
    $nits = $esAdmin ? $Modelo->getNitsFundacion() : [];
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    exit;
}
?>

<form id="form-editar-mascota" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="accion" value="editar">
    <input type="hidden" name="id_mascota" value="<?= htmlspecialchars($mascota['id_mascota']) ?>">

    <!-- Información Básica -->
    <fieldset class="border p-4 mb-4 rounded" style="border-color: rgba(26, 19, 51, 0.25) !important;">
        <legend class="text-center fw-semibold mb-4" style="color: #1a1333; text-decoration: underline;">
            <i class="uil uil-info-circle me-2"></i>Información Básica de la Mascota
        </legend>

        <div class="row">
            <!-- ✅ Nuevo campo Número de Chip -->
            <div class="col-md-6 mb-3">
                <label for="numero_chip" class="form-label fw-semibold">
                    <i class="uil uil-microchip me-1" style="color: #1a1333;"></i>
                    Número de Chip
                </label>
                <input type="text" class="form-control border-2" id="numero_chip" name="numero_chip"
                    style="border-color: rgba(26, 19, 51, 0.3);"
                    value="<?= htmlspecialchars($mascota['numero_chip'] ?? '') ?>"
                    placeholder="Ej: 123456789">
            </div>

            <!-- Nombre -->
            <div class="col-md-6 mb-3">
                <label for="nombre" class="form-label fw-semibold">
                    <i class="uil uil-tag me-1" style="color: #1a1333;"></i>
                    Nombre <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control border-2" id="nombre" name="nombre"
                    style="border-color: rgba(26, 19, 51, 0.3);"
                    value="<?= htmlspecialchars($mascota['nombre'] ?? '') ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="edad_meses" class="form-label fw-semibold">
                    <i class="uil uil-calendar-alt me-1" style="color: #1a1333;"></i>
                    Edad (en meses) <span class="text-danger">*</span>
                </label>
                <input type="number" class="form-control border-2" id="edad_meses" name="edad_meses"
                    style="border-color: rgba(26, 19, 51, 0.3);" min="0"
                    value="<?= htmlspecialchars($mascota['edad_meses'] ?? '') ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="sexo" class="form-label fw-semibold">
                    <i class="uil uil-venus-mars me-1" style="color: #1a1333;"></i>
                    Sexo <span class="text-danger">*</span>
                </label>
                <select class="form-select border-2" id="sexo" name="sexo" style="border-color: rgba(26, 19, 51, 0.3);" required>
                    <option value="">Seleccione...</option>
                    <option value="macho" <?= ($mascota['sexo'] ?? '') == 'macho' ? 'selected' : '' ?>>Macho</option>
                    <option value="hembra" <?= ($mascota['sexo'] ?? '') == 'hembra' ? 'selected' : '' ?>>Hembra</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="id_tipo_mascota" class="form-label fw-semibold">
                    <i class="uil uil-bookmark me-1" style="color: #1a1333;"></i>
                    Tipo de Mascota <span class="text-danger">*</span>
                </label>
                <select class="form-select border-2" id="id_tipo_mascota" name="id_tipo_mascota"
                    style="border-color: rgba(26, 19, 51, 0.3);" required>
                    <option value="">Seleccione...</option>
                    <?php if (!empty($tipos)): ?>
                        <?php foreach ($tipos as $tipo): ?>
                            <option value="<?= htmlspecialchars($tipo['id_tipo_mascota']) ?>"
                                <?= ($tipo['id_tipo_mascota'] ?? '') == ($mascota['id_tipo_mascota'] ?? '') ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tipo['especie'] ?? 'N/A') ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
    </fieldset>

    <!-- Estado de Adopción -->
    <fieldset class="border p-4 mb-4 rounded" style="border-color: rgba(26, 19, 51, 0.25) !important;">
        <legend class="text-center fw-semibold mb-4" style="color: #1a1333; text-decoration: underline;">
            <i class="uil uil-heart text-danger me-2"></i>Estado de Adopción
        </legend>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="id_estado_adopcion" class="form-label fw-semibold">
                    <i class="uil uil-shield-check me-1" style="color: #1a1333;"></i>
                    Estado Actual <span class="text-danger">*</span>
                </label>

                <!-- Select deshabilitado (solo visual) -->
                <select class="form-select border-2" id="id_estado_adopcion_disabled"
                    style="border-color: rgba(26, 19, 51, 0.3);" disabled>
                    <option value="">Seleccione...</option>
                    <?php if (!empty($estados)): ?>
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?= htmlspecialchars($estado['id_estado_adopcion']) ?>"
                                <?= ($estado['id_estado_adopcion'] ?? '') == ($mascota['id_estado_adopcion'] ?? '') ? 'selected' : '' ?>>
                                <?= htmlspecialchars($estado['tipo_estado'] ?? '') ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <!-- Hidden que sí envía el valor real -->
                <input type="hidden" name="id_estado_adopcion" value="<?= htmlspecialchars($mascota['id_estado_adopcion']) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label for="input-imagen" class="form-label fw-semibold">
                    <i class="uil uil-image me-1" style="color: #1a1333;"></i>
                    Imagen de la mascota
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
            <figure class="mascota-img-container">
                <?php
                $nombreImagen = !empty($mascota['imagen']) ? $mascota['imagen'] : 'default.jpg';
                $rutaImagen = htmlspecialchars($nombreImagen);
                ?>
                <img id="preview-imagen" src="<?= $rutaImagen ?>" alt="Imagen de la mascota"
                    style="object-fit: cover; max-height: 300px; border: 3px solid #1a1333;"
                    class="img-fluid rounded shadow-sm">
                <figcaption class="mt-2 text-muted fw-semibold">Vista previa de la imagen actual</figcaption>
            </figure>
        </div>
    </div>

    <!-- Información adicional destacada -->
    <div class="alert border-0"
        style="background-color: rgba(26, 19, 51, 0.05); border-left: 4px solid #1a1333 !important;">
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
            <i class="uil uil-save me-2"></i>Actualizar Mascota
        </button>
    </div>
</form>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputImagen = document.getElementById('input-imagen');
        const previewImagen = document.getElementById('preview-imagen');

        if (inputImagen && previewImagen) {
            inputImagen.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImagen.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>