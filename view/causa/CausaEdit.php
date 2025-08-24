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
    <input type="hidden" name="id_causa" value="<?php echo htmlspecialchars($causa['id_causa']); ?>">

    <!-- Datos de la Causa -->
    <fieldset class="border p-3 mb-4 rounded">
        <legend class="text-center text-secondary fw-semibold" style="text-decoration: underline;">
            Editar Datos de la Causa
        </legend>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="id_causa" class="form-label">ID Causa</label>
                <input type="text" class="form-control" id="id_causa" name="id_causa" 
                    value="<?php echo htmlspecialchars($causa['id_causa']); ?>" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nombre" name="nombre" 
                    value="<?php echo htmlspecialchars($causa['nombre']); ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" 
                    value="<?php echo htmlspecialchars($causa['descripcion']); ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="meta" class="form-label">Meta</label>
                <input type="text" class="form-control" id="meta" name="meta" 
                    value="<?php echo htmlspecialchars($causa['meta']); ?>" readonly>
                <small class="text-muted">La meta no se puede modificar</small>
            </div>
            <div class="col-md-6 mb-3">
                <label for="estado_causa" class="form-label">Estado de causa <span class="text-danger">*</span></label>
                <select class="form-select" id="estado_causa" name="estado_causa" required>
                    <option value="">Selecciona estado</option>
                    <option value="activa" <?php echo ($causa['estado_causa'] == 'activa') ? 'selected' : ''; ?>>Activa</option>
                    <option value="en pausa" <?php echo ($causa['estado_causa'] == 'en pausa') ? 'selected' : ''; ?>>En pausa</option>
                    <option value="cumplida" <?php echo ($causa['estado_causa'] == 'cumplida') ? 'selected' : ''; ?>>Cumplida</option>
                    <option value="cancelada" <?php echo ($causa['estado_causa'] == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="fecha_creacion" class="form-label">Fecha de creación</label>
                <input type="text" class="form-control" id="fecha_creacion" name="fecha_creacion" 
                    value="<?php echo htmlspecialchars($causa['fecha_creacion']); ?>" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label for="nit_fundacion" class="form-label">NIT Fundación</label>
                <input type="text" class="form-control" id="nit_fundacion" name="nit_fundacion" 
                    value="<?php echo htmlspecialchars($causa['nit_fundacion']); ?>" readonly>
                <small class="text-muted">El NIT no se puede modificar</small>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="tipo_causa" class="form-label">Tipo de causa</label>
                <input type="text" class="form-control" id="tipo_causa" name="tipo_causa" 
                    value="<?php echo htmlspecialchars($causa['tipo_causa']); ?>" readonly>
                <small class="text-muted">El tipo de causa no se puede modificar</small>
            </div>
            <div class="col-md-6 mb-3">
                <label for="input-imagen" class="form-label">Imagen de la causa</label>
                <input type="file" name="imagen" id="input-imagen" class="form-control" accept="image/*">
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
                    src="<?php echo $rutaImagen; ?>"
                    alt="Imagen de la causa"
                    style="object-fit: cover; max-height: 300px;"
                    class="img-fluid rounded shadow-sm">
                <figcaption class="mt-2 text-muted">Vista previa de la imagen actual</figcaption>
            </figure>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Actualizar Causa
        </button>
    </div>
</form>