<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Publicacion\Publicacion;

$Modelo = new Publicacion();

// Validación: si no se pasa el ID, se muestra un mensaje y se detiene la ejecución
if (!isset($_GET['id'])) {
    echo "ID de publicacion no especificado.";
    exit;
}

$id = $_GET['id'];
$resultado = $Modelo->getId($id);

// Verificar si se obtuvieron datos
if (!$resultado || empty($resultado)) {
    echo "Publicacion no encontrada.";
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
    echo "Formato de datos inválido.";
    exit;
}

// Verificar que tenemos los campos necesarios
if (!isset($publicacion['id_publicacion']) || 
    !isset($publicacion['titulo']) || 
    !isset($publicacion['contenido'])) {
    echo "Datos de publicacion incompletos.";
    exit;
}
?>

<form id="form-editar-publicacion" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="accion" value="editar">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($publicacion['id_publicacion']); ?>">

    <!-- Datos de la Publicación -->
    <fieldset class="border p-3 mb-4 rounded">
        <legend class="text-center text-secondary fw-semibold" style="text-decoration: underline;">
            Editar Publicación
        </legend>

        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="titulo" name="titulo" 
                    value="<?php echo htmlspecialchars($publicacion['titulo'] ?? ''); ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="contenido" class="form-label">Contenido <span class="text-danger">*</span></label>
                <textarea class="form-control" id="contenido" name="contenido" rows="4" required><?php 
                    echo htmlspecialchars($publicacion['contenido'] ?? ''); 
                ?></textarea>
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
                    src="<?php echo $rutaImagen; ?>"
                    alt="Imagen de la publicación"
                    style="object-fit: cover; max-height: 300px;"
                    class="img-fluid rounded shadow-sm">
                <figcaption class="mt-2 text-muted">Vista previa de la imagen actual</figcaption>
            </figure>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-arrow-left"></i> Volver a la lista
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Actualizar Publicación
        </button>
    </div>
</form>