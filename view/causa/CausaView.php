<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Causa\Causa;
use App\Model\Fundacion\Fundacion;
use App\Config\Roles;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$Fundacion = new Fundacion();
$Modelo = new Causa();

$nit_fundacion = null;

$id_usuario = $_SESSION['user']['id_usuario'] ?? null;
$esAdmin = $id_usuario && Roles::esAdmin($id_usuario);
$esFundacion = $id_usuario && Roles::esFundacion($id_usuario);
$nit_sesion = $_SESSION['user']['nit_fundacion'] ?? null;

if (isset($_SESSION["user"]["id_usuario"])) {
    $nit_fundacion = Fundacion::obtenerNitPorUsuario($_SESSION["user"]["id_usuario"]);
}
?>

<div class="container crud-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gestión de Causas</h2>
        <?php if ($esFundacion): ?>
            <button id="btn-abrir-modal-causa" class="btn btn-primary2">
                <i class="uil uil-plus-circle"></i> Registrar causa
            </button>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered mb-0 shadow" id="tabla_causas" style="border-radius: 10px; overflow: hidden;">
            <thead style="background: linear-gradient(135deg, #1a1333 0%, #2d1b4e 100%); color: white;">
                <tr>
                    <th class="py-3 border-0" style="min-width: 80px;">
                        <i class="uil uil-hash me-1"></i>ID
                    </th>
                    <th class="py-3 border-0" style="min-width: 120px;">
                        <i class="uil uil-tag me-1"></i>Nombre
                    </th>
                    <th class="py-3 border-0" style="min-width: 150px;">
                        <i class="uil uil-clipboard-notes me-1"></i>Descripción
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 100px;">
                        <i class="uil uil-money-stack me-1"></i>Meta
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-heart me-1"></i>Estado
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-calendar3 me-1"></i>Fecha
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-building me-1"></i>NIT Fundación
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 100px;">
                        <i class="uil uil-image me-1"></i>Imagen
                    </th>
                    <th class="py-3 border-0" style="min-width: 100px;">
                        <i class="uil uil-bookmark me-1"></i>Tipo
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-gear me-1"></i>Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $tipo_usuario = $_SESSION["tipo_usuario"] ?? null;
                $Causa = $Modelo->getCausa();

                if ($tipo_usuario === "fundacion" && $nit_fundacion !== null) {
                    $Causa = $Modelo->getCausasPorFundacion($nit_fundacion);
                } else {
                    $Causa = $Modelo->getCausa();
                }
                
                if ($Causa !== null) {
                    foreach ($Causa as $causa) {
                ?>
                        <tr class="align-middle">
                            <td class="fw-bold" style="color: #1a1333;"><?= $causa['id_causa'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fw-semibold"><?= htmlspecialchars($causa['nombre']) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="fw-medium"><?= htmlspecialchars($causa['descripcion']) ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info bg-opacity-15 border border-info border-opacity-25 px-3 py-2">
                                    $<?= number_format($causa['meta']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php
                                $estadoClass = '';
                                $estadoIcon = '';
                                switch (strtolower($causa['estado_causa'])) {
                                    case 'activa':
                                        $estadoClass = 'bg-success bg-opacity-15 text-white border border-success border-opacity-25';
                                        $estadoIcon = '🟢';
                                        break;
                                    case 'en pausa':
                                        $estadoClass = 'bg-warning bg-opacity-15 text-white border border-warning border-opacity-25';
                                        $estadoIcon = '🟡';
                                        break;
                                    case 'cumplida':
                                        $estadoClass = 'bg-danger bg-opacity-15 text-white border border-danger border-opacity-25';
                                        $estadoIcon = '❤️';
                                        break;
                                    case 'cancelada':
                                        $estadoClass = 'bg-secondary bg-opacity-15 text-white border border-secondary border-opacity-25';
                                        $estadoIcon = '⚫';
                                        break;
                                    default:
                                        $estadoClass = 'bg-secondary bg-opacity-15 text-white border border-secondary border-opacity-25';
                                        $estadoIcon = '⚪';
                                }
                                ?>
                                <span class="badge px-3 py-2 <?= $estadoClass ?>">
                                    <?= $estadoIcon ?> <?= htmlspecialchars($causa['estado_causa']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <small class="text-muted fw-medium"><?= $causa['fecha_creacion'] ?></small>
                            </td>
                            <td class="text-center">
                                <small class="text-muted fw-medium"><?= $causa['nit_fundacion'] ?></small>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($causa['imagen_url'])): ?>
                                    <div class="position-relative d-inline-block">
                                        <img src="<?= htmlspecialchars($causa['imagen_url']) ?>"
                                            alt="Causa"
                                            class="img-thumbnail border-2 shadow-sm"
                                            style="width: 50px; height: 50px; object-fit: cover; border-color: #1a1333 !important; border-radius: 10px !important;">
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
                            <td>
                                <span class="fw-medium"><?= htmlspecialchars($causa['tipo_causa']) ?></span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm" role="group">
                                    <button class="btn btn-sm btn-editar-causa"
                                        style="background-color: #1a1333; color: white; border-color: #1a1333;"
                                        data-id="<?= $causa['id_causa'] ?>"
                                        data-bs-toggle="tooltip" title="Editar causa">
                                        <i class="uil uil-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger btn-eliminar-causa shadow-sm"
                                        data-id="<?= $causa['id_causa'] ?>"
                                        data-bs-toggle="tooltip" title="Eliminar causa">
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
                        <td colspan="10" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <i class="uil uil-inbox display-4 mb-3" style="color: #1a1333; opacity: 0.3;"></i>
                                <h5 class="text-muted mb-2">No hay causas registradas</h5>
                                <p class="text-muted mb-0">¡Registra la primera causa para comenzar!</p>
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

<!-- Modal Registrar Causa -->
<div class="modal fade" id="modal-causa" tabindex="-1" aria-labelledby="modalCausaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow" style="max-height: 90vh;">
            <form id="form-registrar-causa" method="post" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="registrar">

                <div class="modal-header bg-gradient text-white border-0 py-4" style="background-color: #1a1333;">
                    <div class="d-flex align-items-center">
                        <div>
                            <h4 class="modal-title fw-bold mb-0 text-white" id="modalCausaLabel">Nueva Causa</h4>
                            <small class="text-white-50">Complete la información de la causa</small>
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

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="uil uil-tag text-primary me-1"></i>
                                Nombre
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nombre" class="form-control form-control border-2"
                                placeholder="Nombre de la causa" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="uil uil-money-stack text-primary me-1"></i>
                                Meta
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="meta" class="form-control form-control border-2"
                                min="0" placeholder="0" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="uil uil-clipboard-notes text-primary me-1"></i>
                                Descripción
                                <span class="text-danger">*</span>
                            </label>
                            <textarea name="descripcion" class="form-control form-control border-2" 
                                rows="3" placeholder="Descripción de la causa" required></textarea>
                        </div>
                    </div>

                    <!-- Estado y tipo -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary2 fw-semibold mb-3 border-bottom border-opacity-25 pb-2">
                                <i class="uil uil-heart text-danger me-2"></i>Configuración de la Causa
                            </h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="uil uil-shield-check text-success me-1"></i>
                                Estado
                                <span class="text-danger">*</span>
                            </label>
                            <select name="estado_causa" class="form-select form-select border-2" required>
                                <option value="">Seleccione...</option>
                                <option value="activa" selected>Activa</option>
                                <option value="en pausa">En pausa</option>
                                <option value="cumplida">Cumplida</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="uil uil-bookmark text-primary me-1"></i>
                                Tipo de causa
                                <span class="text-danger">*</span>
                            </label>
                            <select name="tipo_causa" class="form-select form-select border-2" required>
                                <option value="">Seleccione...</option>
                                <option value="alimentación">Alimentación</option>
                                <option value="medicamentos">Medicamentos</option>
                                <option value="esterilizacion">Esterilización</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="uil uil-image text-primary me-1"></i>
                                Imagen de la causa
                            </label>
                            <input type="file" name="imagen" accept="image/*"
                                class="form-control form-control border-2 border-dashed">
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
                                <p class="mb-0 small">Una buena descripción e imagen aumentan las posibilidades de que las personas donen a tu causa.
                                    Procura que sea clara, emotiva y que muestre el impacto positivo que tendrá.</p>
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
                            <i class="uil uil-heart-fill me-2"></i>Registrar Causa
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

<!-- Modal Editar (Contenido por JS) -->
<div class="modal fade mt-4" id="modal-editar-causa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #1a1333 0%, #2d1b4e 100%); color: white;">
                <h5 class="modal-title fw-semibold text-white">
                    <i class="uil uil-edit me-2"></i>Editar Causa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-0" id="contenido-editar">
                <!-- Se carga dinámicamente con JS -->
            </div>
        </div>
    </div>
</div>