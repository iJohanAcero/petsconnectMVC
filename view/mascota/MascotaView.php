<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Mascota\Mascota;
use App\Model\Fundacion\Fundacion;
use App\Config\Roles;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$Modelo = new Mascota();
$FundacionModelo = new Fundacion();

$mascotas = $Modelo->getMascota();
$tipos = $Modelo->getTiposMascota();
$nits = $FundacionModelo->getNitsFundacion();
$estados = $Modelo->getEstadosAdopcion();

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
        <h2 class="mb-0">Gestión de Mascotas</h2>
        <?php if ($esFundacion): ?>
            <button id="btn-abrir-modal-mascota" class="btn btn-primary2">
                <i class=" uil uil-plus-circle"></i> Registrar mascota
            </button>
        <?php endif; ?>
    </div>



    <div class="table-responsive">
        <table class="table table-hover table-bordered mb-0 shadow" id="tabla_mascotas" style="border-radius: 10px; overflow: hidden;">
            <thead style="background: linear-gradient(135deg, #1a1333 0%, #2d1b4e 100%); color: white;">
                <tr>
                    <th class="py-3 border-0" style="min-width: 80px;">
                        <i class="uil uil-hash me-1"></i>ID
                    </th>
                    <th class="py-3 border-0" style="min-width: 120px;">
                        <i class="uil uil-tag me-1"></i>Nombre
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 100px;">
                        <i class="uil uil-calendar3 me-1"></i>Edad (meses)
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 80px;">
                        <i class="uil uil-gender-ambiguous me-1"></i>Sexo
                    </th>
                    <th class="py-3 border-0" style="min-width: 100px;">
                        <i class="uil uil-bookmark me-1"></i>Especie
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-heart me-1"></i>Estado
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-building me-1"></i>Nit Fundación
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-microchip me-1"></i>Chip
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 100px;">
                        <i class="uil uil-image me-1"></i>Imagen
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-gear me-1"></i>Acciones
                    </th>
                </tr>

            </thead>
            <tbody>
                <?php
                $tipo_usuario = $_SESSION["tipo_usuario"] ?? null;
                $mascotas = $Modelo->getMascota();
                if ($tipo_usuario === "fundacion" && $nit_fundacion !== null) {
                    $mascotas = $Modelo->getMascotasPorFundacion($nit_fundacion);
                } else {
                    $mascotas = $Modelo->getMascota();
                }
                if (!empty($mascotas)) {
                    foreach ($mascotas as $mascota) {
                ?>
                        <tr class="align-middle">
                            <td class="fw-bold" style="color: #1a1333;"><?= $mascota['id_mascota'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fw-semibold"><?= htmlspecialchars($mascota['nombre']) ?></span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info bg-opacity-15 border border-info border-opacity-25 px-3 py-2">
                                    <?= $mascota['edad_meses'] ?> meses
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge px-3 py-2" style="background-color: rgba(26, 19, 51, 0.1); color: #1a1333; border: 1px solid rgba(26, 19, 51, 0.25);">
                                    <?= $mascota['sexo'] === 'macho' ? '🐕' : '🐱' ?> <?= ucfirst($mascota['sexo']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="fw-medium"><?= htmlspecialchars($mascota['especie']) ?></span>
                            </td>
                            <td class="text-center">
                                <?php
                                $estadoClass = '';
                                $estadoIcon = '';
                                switch (strtoupper($mascota['tipo_estado'])) {
                                    case 'EN ADOPCIÓN':
                                        $estadoClass = 'bg-success bg-opacity-15 text-white border border-success border-opacity-25';
                                        $estadoIcon = '💚';
                                        break;
                                    case 'EN PROCESO':
                                        $estadoClass = 'bg-warning bg-opacity-15 text-white border border-warning border-opacity-25';
                                        $estadoIcon = '💛';
                                        break;
                                    case 'ADOPTADO':
                                        $estadoClass = 'bg-danger bg-opacity-15 text-white border border-danger border-opacity-25';
                                        $estadoIcon = '❤️';
                                        break;
                                    default:
                                        $estadoClass = 'bg-secondary bg-opacity-15 text-white border border-secondary border-opacity-25';
                                        $estadoIcon = '⚪';
                                }
                                ?>
                                <span class="badge px-3 py-2 <?= $estadoClass ?>">
                                    <?= $estadoIcon ?> <?= htmlspecialchars($mascota['tipo_estado']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <small class="text-muted fw-medium"><?= $mascota['nit_fundacion'] ?></small>
                            </td>

                            <td class="text-center">
                                <?php if (!empty($mascota['numero_chip'])): ?>
                                    <span class="fw-semibold text-dark"><?= htmlspecialchars($mascota['numero_chip']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Sin chip</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <?php if (!empty($mascota['imagen'])): ?>
                                    <div class="position-relative d-inline-block">
                                        <img src="<?=($mascota['imagen']) ?>"
                                            alt="Mascota"
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
                            <td class="text-center">
                                <div class="btn-group shadow-sm" role="group">
                                    <button class="btn btn-sm btn-editar-mascota"
                                        style="background-color: #1a1333; color: white; border-color: #1a1333;"
                                        data-id="<?php echo $mascota['id_mascota']; ?>"
                                        data-bs-toggle="tooltip" title="Editar mascota">
                                        <i class="uil uil-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger btn-eliminar-mascota shadow-sm"
                                        data-id="<?php echo $mascota['id_mascota']; ?>"
                                        data-bs-toggle="tooltip" title="Eliminar mascota">
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
                        <td colspan="9" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <i class="uil uil-inbox display-4 mb-3" style="color: #1a1333; opacity: 0.3;"></i>
                                <h5 class="text-muted mb-2">No hay mascotas registradas</h5>
                                <p class="text-muted mb-0">¡Registra la primera mascota para comenzar!</p>
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

<!-- Modal Registrar Mascota -->
<div class="modal fade" id="modal-mascota" tabindex="-1" aria-labelledby="modalMascotaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <form id="form-registrar-mascota" method="post" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="registrar">

                <div class="modal-header bg-gradient text-white border-0 py-4" style="background-color: #1a1333;">
                    <div class="d-flex align-items-center">

                        <div>
                            <h4 class="modal-title fw-bold mb-0 text-white" id="modalMascotaLabel">Nueva Mascota</h4>
                            <small class="text-white-50">Complete la información de la mascota</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Body con mejor organización -->
                <div class="modal-body p-4">
                    <!-- Información básica -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary2 fw-semibold mb-3 border-bottom border-opacity-25 pb-2">
                                <i class="uil uil-info-circle me-2"></i>Información Básica
                            </h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="uil uil-microchip text-primary me-1"></i>
                                Número de chip
                            </label>
                            <input type="text" name="numero_chip" class="form-control form-control border-2"
                                placeholder="Ej: 123456789">
                        </div>

                        <!-- Nombre -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="uil uil-tag text-primary me-1"></i>
                                Nombre
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="nombre" class="form-control form-control border-2"
                                placeholder="Nombre de la mascota" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="uil uil-calendar3 text-primary me-1"></i>
                                Edad (meses)
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="edad_meses" class="form-control form-control border-2"
                                min="0" placeholder="0" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="uil uil-gender-ambiguous text-primary me-1"></i>
                                Sexo
                                <span class="text-danger">*</span>
                            </label>
                            <select name="sexo" class="form-select form-select border-2" required>
                                <option value="">Seleccione...</option>
                                <option value="macho"> Macho</option>
                                <option value="hembra"> Hembra</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="uil uil-bookmark text-primary me-1"></i>
                                Tipo de Mascota
                                <span class="text-danger">*</span>
                            </label>
                            <select name="id_tipo_mascota" class="form-select form-select border-2" required>
                                <option value="">Seleccione...</option>
                                <?php if (!empty($tipos) && is_array($tipos)): ?>
                                    <?php foreach ($tipos as $tipo): ?>
                                        <option value="<?= $tipo['id_tipo_mascota'] ?>">
                                            <?= $tipo['especie'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option disabled>No hay tipos de mascota registrados</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Estado y imagen -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary2 fw-semibold mb-3 border-bottom border-opacity-25 pb-2">
                                <i class="uil uil-heart text-danger me-2"></i>Estado de Adopción
                            </h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="uil uil-shield-check text-success me-1"></i>
                                Estado Actual
                                <span class="text-danger">*</span>
                            </label>
                            <select name="id_estado_adopcion" class="form-select form-select border-2" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($estados as $estado): ?>
                                    <option value="<?= $estado['id_estado_adopcion'] ?>" <?= $estado['tipo_estado'] === 'EN ADOPCIÓN' ? 'selected' : '' ?>>
                                        <?= $estado['tipo_estado'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="uil uil-image text-primary me-1"></i>
                                Fotografía
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
                                <p class="mb-0 small">Una buena fotografía aumenta las posibilidades de adopción.
                                    Procura que sea clara, con buena iluminación y que muestre la personalidad de la mascota.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden inputs -->
                    <input type="hidden" name="nit_fundacion" value="<?php echo htmlspecialchars($nit_fundacion); ?>">
                    <input type="hidden" name="id_mascota" value="">
                </div>


                <!-- Footer con botones mejorados -->
                <div class="modal-footer bg-light border-0 p-4">
                    <div class="d-flex gap-2 w-100 justify-content-end">
                        <button type="button" class="btn btn-outline-secondary btn px-4" data-bs-dismiss="modal">
                            <i class="uil uil-x-circle me-2"></i>Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary2 btn px-4">
                            <i class="uil uil-heart-fill me-2"></i>Registrar Mascota
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

<!-- Modal Editar (Contenido por JS) -->
<div class="modal fade mt-4" id="modal-editar-mascota" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header " style="background: linear-gradient(135deg, #1a1333 0%, #2d1b4e 100%); color: white;">
                <h5 class="modal-title fw-semibold text-white">
                    <i class="fas fa-edit me-2"></i>Editar Mascota
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0" id="contenido-editar">

            </div>
        </div>
    </div>
</div>