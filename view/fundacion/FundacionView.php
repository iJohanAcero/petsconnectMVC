<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Model\Fundacion\Fundacion;

$Modelo = new Fundacion();
$Fundaciones = $Modelo->getFundacion(); 
?>

<div class="container crud-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold" style="color: #1a1333;">Gestión de Fundaciones</h2>
        <button id="btn-abrir-modal-fundacion" class="btn btn-primary2">
            <i class="uil uil-plus-circle me-2"></i> Añadir Fundación
        </button>
    </div>

    <div class="table-responsive shadow-sm" style="border-radius: 10px; overflow: hidden;">
        <table class="table table-hover table-bordered mb-0" id="tabla_fundaciones">
            <thead style="background: linear-gradient(135deg, #1a1333 0%, #2d1b4e 100%); color: white;">
                <tr>
                    <th class="py-3 border-0">
                        <i class="uil uil-document-info me-1"></i>NIT
                    </th>
                    <th class="py-3 border-0">
                        <i class="uil uil-building me-1"></i>Nombre Fundación
                    </th>
                    <th class="py-3 border-0">
                        <i class="uil uil-user me-1"></i>Representante
                    </th>
                    <th class="py-3 border-0">
                        <i class="uil uil-envelope me-1"></i>Correo
                    </th>
                    <th class="py-3 border-0">
                        <i class="uil uil-phone me-1"></i>Teléfono
                    </th>
                    <th class="py-3 border-0 text-center">
                        <i class="uil uil-cog me-1"></i>Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if ($Fundaciones): ?>
                    <?php foreach ($Fundaciones as $fundacion): ?>
                        <tr class="align-middle">
                            <td class="fw-bold" style="color: #1a1333;"><?= htmlspecialchars($fundacion['nit']) ?></td>
                            <td>
                                <span class="fw-semibold"><?= htmlspecialchars($fundacion['nombre_fundacion']) ?></span>
                            </td>
                            <td>
                                <span class="fw-medium"><?= htmlspecialchars($fundacion['nombre_representante']) ?> <?= htmlspecialchars($fundacion['apellido_representante']) ?></span>
                            </td>
                            <td>
                                <span class="fw-medium"><?= htmlspecialchars($fundacion['correo']) ?></span>
                            </td>
                            <td>
                                <span class="fw-medium"><?= htmlspecialchars($fundacion['telefono']) ?></span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm" role="group">
                                    <button class="btn btn-sm btn-editar-fundacion" 
                                        style="background-color: #1a1333; color: white; border-color: #1a1333;"
                                        data-id="<?= $fundacion['nit'] ?>"
                                        data-bs-toggle="tooltip" title="Editar fundación">
                                        <i class="uil uil-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger btn-eliminar-fundacion shadow-sm"
                                        data-id="<?= $fundacion['nit'] ?>"
                                        data-bs-toggle="tooltip" title="Eliminar fundación">
                                        <i class="uil uil-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <i class="uil uil-inbox display-4 mb-3" style="color: #1a1333; opacity: 0.3;"></i>
                                <h5 class="text-muted mb-2">No hay fundaciones registradas</h5>
                                <p class="text-muted mb-0">¡Registra la primera fundación para comenzar!</p>
                            </div>
                        </td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para registrar fundación -->
<div class="modal fade" id="modal-fundacion" tabindex="-1" aria-labelledby="modalFundacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="margin-top: 70px;">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-gradient text-white border-0 py-4" style="background-color: #1a1333;">
                <h5 class="modal-title fw-bold mb-0 text-white" id="modalFundacionLabel">
                    <i class="uil uil-building me-2"></i>Registrar Nueva Fundación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <form id="form-registrar-fundacion">
                    <input type="hidden" name="accion" value="registrar_fundacion">

                    <!-- Sección Representante Legal -->
                    <fieldset class="border p-3 mb-4 rounded">
                        <legend class="text-center text-secondary fw-semibold" style="text-decoration: underline;">
                            <i class="uil uil-user me-1"></i>Datos del Representante legal
                        </legend>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rep_nombre" class="form-label fw-semibold">
                                    <i class="uil uil-user-circle text-primary me-1"></i>Nombre
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control border-2" id="rep_nombre" name="rep_nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="rep_apellido" class="form-label fw-semibold">
                                    <i class="uil uil-user-square text-primary me-1"></i>Apellido
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control border-2" id="rep_apellido" name="rep_apellido" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rep_contrasena" class="form-label fw-semibold">
                                    <i class="uil uil-lock text-primary me-1"></i>Contraseña
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control form-control border-2" id="rep_contrasena" name="rep_contrasena" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="rep_email" class="form-label fw-semibold">
                                    <i class="uil uil-envelope text-primary me-1"></i>Correo electrónico
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control form-control border-2" id="rep_email" name="rep_email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rep_direccion" class="form-label fw-semibold">
                                    <i class="uil uil-map-marker text-primary me-1"></i>Dirección
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control border-2" id="rep_direccion" name="rep_direccion" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="rep_telefono" class="form-label fw-semibold">
                                    <i class="uil uil-phone text-primary me-1"></i>Teléfono
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="tel" class="form-control form-control border-2" id="rep_telefono" name="rep_telefono" required>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Sección Fundación -->
                    <fieldset class="border p-3 mb-4 rounded bg-light">
                        <legend class="text-center text-secondary fw-semibold" style="text-decoration: underline;">
                            <i class="uil uil-building me-1"></i>Datos de la fundación
                        </legend>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fund_nombre" class="form-label fw-semibold">
                                    <i class="uil uil-tag text-primary me-1"></i>Nombre legal de la fundación
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control border-2" id="fund_nombre" name="fund_nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fund_nit" class="form-label fw-semibold">
                                    <i class="uil uil-document-layout-center text-primary me-1"></i>NIT de la fundación
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control border-2" id="fund_nit" name="fund_nit" required>
                                <small class="text-muted">Ejemplo: 1234567890</small>
                            </div>
                        </div>
                    </fieldset>
                    
                    <div class="modal-footer bg-light border-0 p-3">
                        <div class="d-flex gap-2 w-100 justify-content-end">
                            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                                <i class="uil uil-times-circle me-2"></i>Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary2 px-4">
                                <i class="uil uil-building me-2"></i>Registrar Fundación
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal editar fundación -->
<div class="modal fade mt-4" id="modal-editar-fundacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-gradient text-white border-0 py-4" style="background-color: #1a1333;">
                <h5 class="modal-title fw-bold mb-0 text-white">
                    <i class="uil uil-edit me-2"></i>Editar Fundación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="contenido-editar">
                <!-- Aquí va el contenido dinámico con JavaScript -->
            </div>
        </div>
    </div>
</div>