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
    <title>Actualizar perfil</title>
</head>

<body>
    <section id="about-section-modal" class="py-4">
        <div class="container">
            <!-- Header del formulario -->
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8 text-center">
                    <h3 class="mb-2">
                        <i class="uil uil-edit-alt text-primary me-2"></i>
                        Editar mi perfil
                    </h3>
                    <p class="text-muted">Actualiza tu información personal y preferencias</p>
                </div>
            </div>

            <form id="form-editar-perfilGuardian" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($perfil['id_usuario']); ?>">
                <input type="hidden" name="accion" value="editar">

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="row g-4">
                            <!-- Información personal -->
                            <div class="col-lg-8">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white border-0 pb-0">
                                        <h5 class="card-title mb-0">
                                            <i class="uil uil-user text-primary me-2"></i>
                                            Información personal
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Nombre -->
                                        <div class="mb-4">
                                            <label for="nombre" class="form-label fw-semibold">
                                                <i class="uil uil-user me-1"></i>
                                                Nombre completo
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text"
                                                class="form-control form-control-lg"
                                                name="nombre"
                                                id="nombre"
                                                value="<?= htmlspecialchars($perfil['nombre']); ?>"
                                                required
                                                minlength="3"
                                                maxlength="100"
                                                placeholder="Ingresa tu nombre completo">
                                            <div class="form-text">
                                                <small class="text-muted">
                                                    <i class="uil uil-info-circle me-1"></i>
                                                    Entre 3 y 100 caracteres
                                                </small>
                                            </div>
                                            <div class="invalid-feedback">
                                                El nombre debe tener entre 3 y 100 caracteres.
                                            </div>
                                        </div>

                                        <!-- Descripción -->
                                        <div class="mb-4">
                                            <label for="descripcion" class="form-label fw-semibold">
                                                <i class="uil uil-file-alt me-1"></i>
                                                Descripción personal
                                                <span class="text-danger">*</span>
                                            </label>
                                            <textarea class="form-control"
                                                name="descripcion"
                                                id="descripcion"
                                                rows="4"
                                                maxlength="300"
                                                required
                                                minlength="10"
                                                placeholder="Cuéntanos un poco sobre ti, tu experiencia con mascotas y qué buscas en una adopción..."><?= htmlspecialchars($perfil['descripcion']); ?></textarea>
                                            <div class="form-text d-flex justify-content-between">
                                                <small class="text-muted">
                                                    <i class="uil uil-lightbulb me-1"></i>
                                                    Comparte tu pasión por los animales
                                                </small>
                                                <small id="contador-caracteres" class="text-muted">
                                                    <span id="caracteres-actuales"><?= strlen($perfil['descripcion']) ?></span>/300
                                                </small>
                                            </div>
                                            <div class="invalid-feedback">
                                                La descripción debe tener entre 10 y 300 caracteres.
                                            </div>
                                        </div>

                                        <!-- Preferencia -->
                                        <div class="mb-4">
                                            <label for="preferencia" class="form-label fw-semibold">
                                                <i class="uil uil-heart me-1"></i>
                                                Preferencia para adoptar
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="preferencia" id="preferencia" class="form-select form-select-lg" required>
                                                <option value="">Selecciona tu preferencia</option>
                                                <option value="Perros" <?= $perfil['preferencia'] == 'Perros' ? 'selected' : '' ?>>
                                                    🐕 Perros
                                                </option>
                                                <option value="Gatos" <?= $perfil['preferencia'] == 'Gatos' ? 'selected' : '' ?>>
                                                    🐱 Gatos
                                                </option>
                                                <option value="Todos los animales" <?= $perfil['preferencia'] == 'Todos los animales' ? 'selected' : '' ?>>
                                                    🐾 Todos los animales
                                                </option>
                                            </select>
                                            <div class="form-text">
                                                <small class="text-muted">
                                                    <i class="uil uil-question-circle me-1"></i>
                                                    Esto nos ayuda a mostrarte mascotas más relevantes
                                                </small>
                                            </div>
                                            <div class="invalid-feedback">
                                                Por favor selecciona una preferencia.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Imagen de perfil -->
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white border-0 pb-0">
                                        <h5 class="card-title mb-0">
                                            <i class="uil uil-camera text-primary me-2"></i>
                                            Foto de perfil
                                        </h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <!-- Preview de imagen -->
                                        <div class="position-relative mb-4">
                                            <?php
                                            $nombreImagen = !empty($perfil['imagen']) ? $perfil['imagen'] : 'default.jpg';
                                            $rutaImagen = htmlspecialchars($nombreImagen);
                                            ?>
                                            <img id="preview-imagen"
                                                src="<?= $rutaImagen ?>"
                                                alt="Foto de perfil"
                                                class="rounded-circle border border-3 border-light shadow-sm"
                                                style="width: 150px; height: 150px; object-fit: cover;">
                                        </div>

                                        <!-- Input de archivo -->
                                        <div class="mb-3">
                                            <label for="input-imagen" class="btn btn-outline-primary btn-sm">
                                                <i class="uil uil-image-upload me-1"></i>
                                                Cambiar foto
                                            </label>
                                            <input type="file"
                                                name="imagen"
                                                id="input-imagen"
                                                class="form-control d-none"
                                                accept="image/*"
                                                onchange="previewImagen(this)">
                                        </div>

                                        <!-- Información sobre la imagen -->
                                        <div class="alert alert-info border-0 bg-info-subtle">
                                            <small class="text-info">
                                                <i class="uil uil-info-circle me-1"></i>
                                                <strong>Recomendación:</strong><br>
                                                Usa una imagen cuadrada para mejores resultados.<br>
                                                Formatos: JPG, PNG, GIF<br>
                                                Tamaño máximo: 5MB
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="uil uil-shield-check me-1"></i>
                                                Todos los campos marcados con * son obligatorios
                                            </small>
                                            <div class="d-flex gap-2">
                                                <button type="button"
                                                    class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                    <i class="uil uil-arrow-left me-1"></i>
                                                    Cancelar
                                                </button>
                                                <button type="submit"
                                                    class="btn btn-primary"
                                                    id="btn-guardar">
                                                    <i class="uil uil-save me-1"></i>
                                                    Guardar cambios
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</body>

</html>