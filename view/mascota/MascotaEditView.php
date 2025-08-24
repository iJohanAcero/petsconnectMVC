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


<div class="modal-content border-0 shadow-">
    <form id="form-editar-mascota" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="accion" value="editar">
        <input type="hidden" name="id_mascota" value="<?= htmlspecialchars($mascota['id_mascota']) ?>">

        <fieldset>


            <!-- Body con mejor organización -->
            <div class="modal-body p-4">
                <!-- Información básica -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3 border-bottom pb-2" style="color: #1a1333; border-color: rgba(26, 19, 51, 0.25) !important;">
                            <i class="uil uil-info-circle me-2"></i>Información Básica
                        </h6>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">
                            <i class="uil uil-tag me-1" style="color: #1a1333;"></i>
                            Nombre
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nombre" class="form-control form-control- border-2"
                            style="border-color: rgba(26, 19, 51, 0.3);"
                            value="<?= htmlspecialchars($mascota['nombre'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">
                            <i class="uil uil-calendar-alt me-1" style="color: #1a1333;"></i>
                            Edad (en meses)
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="edad_meses" class="form-control form-control- border-2"
                            style="border-color: rgba(26, 19, 51, 0.3);"
                            min="0" value="<?= htmlspecialchars($mascota['edad_meses'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">
                            <i class="uil uil-venus-mars me-1" style="color: #1a1333;"></i>
                            Sexo
                            <span class="text-danger">*</span>
                        </label>
                        <select name="sexo" class="form-select border-2"
                            style="border-color: rgba(26, 19, 51, 0.3);" required>
                            <option value="">Seleccione...</option>
                            <option value="macho" <?= ($mascota['sexo'] ?? '') == 'macho' ? 'selected' : '' ?>> Macho</option>
                            <option value="hembra" <?= ($mascota['sexo'] ?? '') == 'hembra' ? 'selected' : '' ?>> Hembra</option>
                        </select>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-semibold">
                            <i class="uil uil-bookmark me-1" style="color: #1a1333;"></i>
                            Tipo de Mascota
                            <span class="text-danger">*</span>
                        </label>
                        <select name="id_tipo_mascota" class="form-select border-2"
                            style="border-color: rgba(26, 19, 51, 0.3);" required>
                            <option value="">Seleccione...</option>
                            <?php if (!empty($tipos)): ?>
                                <?php foreach ($tipos as $tipo): ?>
                                    <option value="<?= htmlspecialchars($tipo['id_tipo_mascota']) ?>"
                                        <?= ($tipo['id_tipo_mascota'] ?? '') == ($mascota['id_tipo_mascota'] ?? '') ? 'selected' : '' ?>>
                                        <?php
                                        if (stripos($tipo['especie'], 'perro') !== false);
                                        elseif (stripos($tipo['especie'], 'gato') !== false);
                                        ?>
                                        <?= htmlspecialchars($tipo['especie'] ?? 'N/A') ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Estado y imagen -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3 border-bottom pb-2" style="color: #1a1333; border-color: rgba(26, 19, 51, 0.25) !important;">
                            <i class="uil uil-heart text-danger me-2"></i>Estado de Adopción
                        </h6>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">
                            <i class="uil uil-shield-check me-1" style="color: #1a1333;"></i>
                            Estado Actual
                            <span class="text-danger">*</span>
                        </label>
                        <select name="id_estado_adopcion" class="form-select border-2"
                            style="border-color: rgba(26, 19, 51, 0.3);" required>
                            <option value="">Seleccione...</option>
                            <?php if (!empty($estados)): ?>
                                <?php foreach ($estados as $estado): ?>
                                    <option value="<?= htmlspecialchars($estado['id_estado_adopcion']) ?>"
                                        <?= ($estado['id_estado_adopcion'] ?? '') == ($mascota['id_estado_adopcion'] ?? '') ? 'selected' : '' ?>>
                                        <?php
                                        if (stripos($estado['tipo_estado'], 'ADOPCIÓN') !== false);
                                        elseif (stripos($estado['tipo_estado'], 'PROCESO') !== false);
                                        elseif (stripos($estado['tipo_estado'], 'ADOPTADO') !== false);
                                        ?>
                                        <?= htmlspecialchars($estado['tipo_estado'] ?? '') ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="input-imagen" class="form-label">Imagen de la mascota</label>
                        <input type="file" name="imagen" id="input-imagen" class="form-control" accept="image/*">
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
                    <img id="preview-imagen"
                        src="<?php echo $rutaImagen; ?>"
                        alt="Imagen de la mascota"
                        style="object-fit: cover; max-height: 300px;"
                        class="img-fluid rounded shadow-sm">
                    <figcaption class="mt-2 text-muted">Vista previa de la imagen actual</figcaption>
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


        <!-- Footer con botones mejorados -->
        <div class="modal-footer bg-light border-0 p-4">
            <div class="d-flex gap-2 w-100 justify-content-end">
                <button type="button" class="btn-close" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Actualizar Mascota</button>
            </div>
        </div>
    </form>
</div>