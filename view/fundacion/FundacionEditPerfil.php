<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Perfil\Perfil;

$perfil = new Perfil();

// Validación: si no se pasa el ID, se muestra un mensaje y se detiene la ejecución
$perfilModel = new Perfil();
if (!isset($_GET['id'])) {
    echo "ID de Perfil no especificado.";
    exit;
}



$id = (int)$_GET['id'];
$perfil = $perfilModel->getPerfilPorUsuario($id);


if (!$perfil || empty($perfil)) {
    echo "Perfil no encontrado.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <section id="about-section-modal" class="pt-2 pb-5">
        <div class="custom-wrapper">
            <div class="container-fluid px-5 wrapabout">

                <!-- Alertas -->
                <div id="alertas-container"></div>

                <!-- Loading Spinner -->
                <div id="loading-spinner" class="text-center d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Actualizando perfil...</p>
                </div>

                <form id="form-editar-perfilFundacion" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($perfil['id_usuario']); ?>">
                    <input type="hidden" name="accion" value="editar">

                    <div class="row">
                        <!-- Columna de datos -->
                        <div class="col-lg-6 align-items-center justify-content-center d-flex mb-5 mb-lg-0">
                            <div class="blockabout">
                                <div class="blockabout-inner text-center text-sm-start">

                                    <!-- Nombre -->
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label fw-bold">
                                            <i class="uil uil-building me-1"></i>Nombre de la Fundación
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="nombre" id="nombre"
                                            value="<?= htmlspecialchars($perfil['nombre']); ?>"
                                            required minlength="3" maxlength="100">
                                        <div class="invalid-feedback">
                                            El nombre debe tener entre 3 y 100 caracteres.
                                        </div>
                                    </div>

                                    <!-- Descripción -->
                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label fw-bold">
                                            <i class="uil uil-file-alt me-1"></i>Descripción
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control" name="descripcion" id="descripcion"
                                            rows="4" maxlength="1000" required minlength="10"
                                            placeholder="Describe la misión y actividades de tu fundación..."><?= htmlspecialchars($perfil['descripcion']); ?></textarea>
                                        <div class="form-text d-flex justify-content-between">
                                            <small class="text-muted">Mínimo 10 caracteres</small>
                                            <small id="contador-caracteres" class="text-muted">
                                                <span id="caracteres-actuales"><?= strlen($perfil['descripcion']) ?></span>/1000
                                            </small>
                                        </div>
                                        <div class="invalid-feedback">
                                            La descripción debe tener entre 10 y 1000 caracteres.
                                        </div>
                                    </div>

                                    <!-- Preferencia -->
                                    <div class="mb-4">
                                        <label for="preferencia" class="form-label fw-bold">
                                            <i class="uil uil-heart me-1"></i>Mascotas más buscadas
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="preferencia" id="preferencia" class="form-select" required>
                                            <option value="">Seleccionar preferencia</option>
                                            <option value="Perros" <?= $perfil['preferencia'] == 'Perros' ? 'selected' : '' ?>>
                                                Perros
                                            </option>
                                            <option value="Gatos" <?= $perfil['preferencia'] == 'Gatos' ? 'selected' : '' ?>>
                                                Gatos
                                            </option>
                                            <option value="Todos los animales" <?= $perfil['preferencia'] == 'Todos los animales' ? 'selected' : '' ?>>
                                                Todos los animales
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor selecciona una preferencia.
                                        </div>
                                    </div>

                                    <!-- Redes Sociales -->
                                    <div class="card border-0 bg-light mb-4">
                                        <div class="card-header bg-transparent border-0 pb-0">
                                            <label class="form-label fw-bold mb-3">
                                                <i class="uil uil-share-alt me-1"></i>Redes Sociales
                                                <small class="text-muted fw-normal">(Opcional)</small>
                                            </label>
                                        </div>
                                        <div class="card-body pt-2">
                                            <div id="redes-sociales-container">
                                                <?php if (isset($perfil['redes_sociales']) && !empty($perfil['redes_sociales'])): ?>
                                                    <?php foreach ($perfil['redes_sociales'] as $index => $red): ?>

                                                        <div class="red-social-item border p-3 mb-3 rounded bg-white" data-index="<?= $index ?>">
                                                            <div class="row align-items-center">
                                                                <div class="col-4">
                                                                    <select name="redes_sociales[<?= $index ?>][tipo_red]"
                                                                        class="form-select form-select-sm tipo-red-select">
                                                                        <option value="">Seleccionar</option>
                                                                        <option value="facebook" <?= $red['tipo_red'] == 'facebook' ? 'selected' : '' ?>>
                                                                            Facebook
                                                                        </option>
                                                                        <option value="instagram" <?= $red['tipo_red'] == 'instagram' ? 'selected' : '' ?>>
                                                                            Instagram
                                                                        </option>
                                                                        <option value="pagina_web" <?= $red['tipo_red'] == 'pagina_web' ? 'selected' : '' ?>>
                                                                            Página Web
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-6">
                                                                    <input type="url" name="redes_sociales[<?= $index ?>][url_red]"
                                                                        class="form-control form-control-sm url-red-input"
                                                                        placeholder="https://..."
                                                                        value="<?= htmlspecialchars($red['url_red']) ?>">
                                                                </div>
                                                                <div class="col-2 text-end">
                                                                    <button type="button" class="btn btn-danger btn-sm btn-eliminar-red">
                                                                        <i class="uil uil-trash-alt"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div class="text-muted text-center py-3" id="mensaje-sin-redes">
                                                        <i class="uil uil-link-add fs-2"></i>
                                                        <p class="mb-0">No hay redes sociales configuradas</p>
                                                        <small class="text-muted">Haz clic en "Agregar Red Social" para comenzar</small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- BOTÓN AGREGAR RED SOCIAL -->
                                            <div class="text-center mt-3">
                                                <button type="button" class="btn btn-success btn-sm" id="btn-agregar-red">
                                                    <i class="uil uil-plus"></i> Agregar Red Social
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Vista previa de redes sociales -->
                                    <div class="text-center mb-4">
                                        <h6 class="text-muted mb-2">Vista previa:</h6>
                                        <div id="preview-redes">
                                            <?php if (isset($perfil['redes_sociales']) && !empty($perfil['redes_sociales'])): ?>
                                                <?php foreach ($perfil['redes_sociales'] as $red): ?>
                                                    <a href="<?= htmlspecialchars($red['url_red']) ?>" target="_blank" class="text-decoration-none me-3 fs-4">
                                                        <?php
                                                        switch ($red['tipo_red']) {
                                                            case 'facebook':
                                                                echo '<i class="uil uil-facebook-f text-primary"></i>';
                                                                break;
                                                            case 'instagram':
                                                                echo '<i class="uil uil-instagram-alt text-danger"></i>';
                                                                break;
                                                            case 'pagina_web':
                                                                echo '<i class="uil uil-globe text-info"></i>';
                                                                break;
                                                        }
                                                        ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span class="text-muted">
                                                    <i class="uil uil-link-broken"></i> Sin redes sociales
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Botones de acción -->
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                                        <button type="submit" class="btn btn-primary2 btn-lg me-md-2" id="btn-guardar">
                                            <i class="uil uil-save"></i> Guardar cambios
                                        </button>
                                        <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal" aria-label="Close">← Volver</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna de imagen -->
                        <div class="col-lg-6 mt-5 mt-lg-0 align-items-center justify-content-center d-flex">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center p-4">
                                    <h5 class="card-title mb-3">
                                        <i class="uil uil-camera me-1"></i>Imagen del perfil
                                    </h5>

                                    <figure class="perfil-img-container text-center mb-4">
                                        <?php
                                        $nombreImagen = !empty($perfil['imagen']) ? $perfil['imagen'] : 'default.jpg';
                                        $rutaImagen = htmlspecialchars($nombreImagen);
                                        ?>
                                        <img id="preview-imagen"
                                            src="<?= $rutaImagen ?>"
                                            alt="Foto de perfil"
                                            style="object-fit: cover; max-height: 50vh; width: 100%; border-radius: 15px;"
                                            class="img-fluid border">
                                    </figure>

                                    <div class="mb-3">
                                        <label for="input-imagen" class="form-label">
                                            <i class="uil uil-image-upload me-1"></i>Actualizar imagen
                                        </label>
                                        <input type="file" name="imagen" id="input-imagen"
                                            class="form-control" accept="image/*"
                                            onchange="previewImagen(this)">
                                        <div class="form-text">
                                            <small class="text-muted">
                                                Formatos: JPG, PNG, GIF | Tamaño máximo: 5MB
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Información adicional -->
                                    <div class="alert alert-info border-0 bg-light">
                                        <small>
                                            <i class="uil uil-info-circle me-1"></i>
                                            <strong>Tip:</strong> Usa una imagen cuadrada para mejores resultados
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>