<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Model\Publicacion\Publicacion;
use App\Model\Fundacion\Fundacion;
use App\Config\Roles;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$Modelo = new Publicacion();

$nit_fundacion = null;
$id_usuario = $_SESSION['user']['id_usuario'] ?? null;
$esAdmin = $id_usuario && Roles::esAdmin($id_usuario);
$esFundacion = $id_usuario && Roles::esFundacion($id_usuario);

if (isset($_SESSION["user"]["id_usuario"])) {
    $nit_fundacion = Fundacion::obtenerNitPorUsuario($_SESSION["user"]["id_usuario"]);
}
?>

<div class="container crud-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gestión de Publicaciones</h2>
        <?php if ($esFundacion): ?>
            <button id="btn-abrir-modal-publicacion" class="btn btn-primary2">
                <i class="uil uil-plus-circle"></i> Nueva Publicación
            </button>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered mb-0 shadow table-mobile-cards" id="tabla_publicacion" style="border-radius: 10px; overflow: hidden;">
            <thead style="background: linear-gradient(135deg, #1a1333 0%, #2d1b4e 100%); color: white;">
                <tr>
                    <th class="py-3 border-0" style="min-width: 80px;">
                        <i class="uil uil-hash me-1"></i>ID
                    </th>
                    <th class="py-3 border-0" style="min-width: 150px;">
                        <i class="uil uil-heading me-1"></i>Título
                    </th>
                    <th class="py-3 border-0" style="min-width: 200px;">
                        <i class="uil uil-clipboard-notes me-1"></i>Contenido
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-image me-1"></i>Imagen
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-calendar me-1"></i>Fecha
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-building me-1"></i>NIT Fundación
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-gear me-1"></i>Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $tipo_usuario = $_SESSION["tipo_usuario"] ?? null;

                if ($tipo_usuario === "fundacion" && $nit_fundacion !== null) {
                    $Publicacion = $Modelo->getPublicacionesPorFundacion($nit_fundacion);
                } else {
                    $Publicacion = $Modelo->getPublicacion();
                }
                
                if ($Publicacion !== null) {
                    foreach ($Publicacion as $publicacion) {
                ?>
                        <tr class="align-middle">
                            <td class="fw-bold" style="color: #1a1333;" data-label="ID:"><?= $publicacion['id_publicacion'] ?></td>
                            <td data-label="Titulo:">
                                <div class="d-flex align-items-center">
                                    <span class="fw-semibold"><?= htmlspecialchars($publicacion['titulo']) ?></span>
                                </div>
                            </td>
                            <td data-label="Contenido:">
                                <span class="fw-medium"><?= htmlspecialchars(substr($publicacion['contenido'], 0, 100)) . (strlen($publicacion['contenido']) > 100 ? '...' : '') ?></span>
                            </td>
                            <td class="text-center" data-label="Imagen:">
                                <?php if (!empty($publicacion['imagen'])): ?>
                                    <div class="position-relative d-inline-block">
                                        <img src="<?= htmlspecialchars($publicacion['imagen']) ?>"
                                            alt="Imagen de publicación"
                                            class="img-thumbnail border-2 shadow-sm"
                                            style="width: 60px; height: 60px; object-fit: cover; border-color: #1a1333 !important; border-radius: 10px !important;">
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background-color: #1a1333; font-size: 0.6em;">
                                            <i class="uil uil-check text-white"></i>
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex flex-column align-items-center text-muted">
                                        <div class="rounded p-2" style="background-color: rgba(26, 19, 51, 0.1);">
                                            <i class="uil uil-image" style="color: #1a1333; opacity: 0.6;"></i>
                                        </div>
                                        <small style="font-size: 0.7rem;">Sin imagen</small>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center" data-label="Fecha:">
                                <small class="text-muted fw-medium"><?= $publicacion['fecha'] ?></small>
                            </td>
                            <td class="text-center" data-label="Fundación:">
                                <small class="text-muted fw-medium"><?= $publicacion['nit_fundacion'] ?></small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm" role="group">
                                    <button class="btn btn-sm btn-editar-publicacion"
                                        style="background-color: #1a1333; color: white; border-color: #1a1333;"
                                        data-id="<?= $publicacion['id_publicacion'] ?>"
                                        data-bs-toggle="tooltip" title="Editar publicación">
                                        <i class="uil uil-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger btn-eliminar-publicacion shadow-sm"
                                        data-id="<?= $publicacion['id_publicacion'] ?>"
                                        data-bs-toggle="tooltip" title="Eliminar publicación">
                                        <i class="uil uil-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <i class="uil uil-inbox display-4 mb-3" style="color: #1a1333; opacity: 0.3;"></i>
                                <h5 class="text-muted mb-2">No hay publicaciones registradas</h5>
                                <p class="text-muted mb-0">¡Crea la primera publicación para comenzar!</p>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Registrar Publicación -->
<div class="modal fade" id="modal-publicacion" tabindex="-1" aria-labelledby="modalPublicacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow" style="max-height: 90vh;">
            <form id="form-registrar-publicacion" method="post" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="registrar">

                <div class="modal-header bg-gradient text-white border-0 py-4" style="background-color: #1a1333;">
                    <div class="d-flex align-items-center">
                        <div>
                            <h4 class="modal-title fw-bold mb-0 text-white" id="modalPublicacionLabel">Nueva Publicación</h4>
                            <small class="text-white-50">Complete la información de la publicación</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Body con scroll -->
                <div class="modal-body p-4" style="overflow-y: auto;">
                    <!-- Información básica -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary2 fw-semibold mb-3 border-bottom border-opacity-25 pb-2">
                                <i class="uil uil-info-circle me-2"></i>Información Básica
                            </h6>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="uil uil-heading text-primary me-1"></i>
                                Título
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="titulo" class="form-control form-control border-2"
                                placeholder="Título de la publicación" required
                                style="border-color: rgba(26, 19, 51, 0.3);">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="uil uil-clipboard-notes text-primary me-1"></i>
                                Contenido
                                <span class="text-danger">*</span>
                            </label>
                            <textarea name="contenido" class="form-control form-control border-2" 
                                rows="4" placeholder="Contenido de la publicación" required
                                style="border-color: rgba(26, 19, 51, 0.3);"></textarea>
                        </div>
                    </div>

                    <!-- Imagen -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary2 fw-semibold mb-3 border-bottom border-opacity-25 pb-2">
                                <i class="uil uil-image text-danger me-2"></i>Imagen de la Publicación
                            </h6>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="uil uil-image text-primary me-1"></i>
                                Seleccionar imagen
                                <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="imagen" accept="image/*"
                                class="form-control form-control border-2 border-dashed"
                                style="border-color: rgba(26, 19, 51, 0.3);" required>
                            <div class="form-text mt-2">
                                <i class="uil uil-info-circle text-primary me-1"></i>
                                Formato recomendado: JPG, PNG. Tamaño máximo: 5MB
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional destacada -->
                    <div class="alert alert-info border-0 bg-opacity-10" style="background-color: #fdaac4;">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="uil uil-lightbulb text-info fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="alert-heading mb-1">¡Consejo!</h6>
                                <p class="mb-0 small">Una imagen atractiva y un contenido claro aumentan el engagement con tu audiencia.
                                    Procura que sea relevante y de calidad para generar mayor impacto.</p>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="nit_fundacion" value="<?= htmlspecialchars($nit_fundacion) ?>">
                </div>

                <!-- Footer fijo en la parte inferior -->
                <div class="modal-footer bg-light border-0 p-4" style="position: sticky; bottom: 0; z-index: 10;">
                    <div class="d-flex gap-2 w-100 justify-content-end">
                        <button type="button" class="btn btn-outline-secondary btn px-4" data-bs-dismiss="modal">
                            <i class="uil uil-x-circle me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary2 btn px-4">
                            <i class="uil uil-upload me-2"></i>Publicar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar (Contenido por JS) -->
<div class="modal fade mt-4" id="modal-editar-publicacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #1a1333 0%, #2d1b4e 100%); color: white;">
                <h5 class="modal-title fw-semibold text-white">
                    <i class="uil uil-edit me-2"></i>Editar Publicación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-0" id="contenido-editar">
                <!-- Se carga dinámicamente con JS -->
            </div>
        </div>
    </div>
</div>