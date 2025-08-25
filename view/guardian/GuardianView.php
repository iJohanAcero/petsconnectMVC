<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Guardian\Guardian;

$Modelo = new Guardian();
$guardianes = $Modelo->getGuardian();
?>

<div class="container crud-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold" style="color: #1a1333;">Gestión de Guardianes</h2>
        <button id="btn-abrir-modal-guardian" class="btn btn-primary2">
            <i class="uil uil-plus-circle me-2"></i> Añadir Guardián
        </button>
    </div>

    <div class="table-responsive shadow-sm" style="border-radius: 10px; overflow: hidden;">
        <table class="table table-hover table-bordered mb-0" id="tabla_guardianes">
            <thead style="background: linear-gradient(135deg, #1a1333 0%, #2d1b4e 100%); color: white;">
                <tr>
                    <th class="py-3 border-0">
                        <i class="uil uil-user me-1"></i>ID Usuario
                    </th>
                    <th class="py-3 border-0">
                        <i class="uil uil-user-circle me-1"></i>ID Perfil
                    </th>
                    <th class="py-3 border-0">
                        <i class="uil uil-user-square me-1"></i>Nombre
                    </th>
                    <th class="py-3 border-0">
                        <i class="uil uil-envelope me-1"></i>Correo
                    </th>
                    <th class="py-3 border-0">
                        <i class="uil uil-phone me-1"></i>Teléfono
                    </th>
                    <th class="py-3 border-0">
                        <i class="uil uil-map-marker me-1"></i>Dirección
                    </th>
                    <th class="py-3 border-0 text-center">
                        <i class="uil uil-image me-1"></i>Imagen
                    </th>
                    <th class="py-3 border-0 text-center">
                        <i class="uil uil-cog me-1"></i>Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if ($guardianes): ?>
                    <?php foreach ($guardianes as $guardian): ?>
                        <tr class="align-middle">
                            <td class="fw-bold" style="color: #1a1333;"><?= htmlspecialchars($guardian['id_usuario']) ?></td>
                            <td><?= htmlspecialchars($guardian['id_perfil']) ?></td>
                            <td>
                                <span class="fw-semibold"><?= htmlspecialchars($guardian['nombre_guardian']) ?> <?= htmlspecialchars($guardian['apellido_guardian']) ?></span>
                            </td>
                            <td>
                                <span class="fw-medium"><?= htmlspecialchars($guardian['correo']) ?></span>
                            </td>
                            <td>
                                <span class="fw-medium"><?= htmlspecialchars($guardian['telefono_guardian']) ?></span>
                            </td>
                            <td>
                                <span class="fw-medium"><?= htmlspecialchars($guardian['direccion_guardian']) ?></span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($guardian['imagen_guardian'])): ?>
                                    <div class="position-relative d-inline-block">
                                        <img src="<?php echo htmlspecialchars($guardian['imagen_guardian']); ?>"
                                            alt="Imagen de perfil"
                                            class="img-thumbnail border-2 shadow-sm"
                                            style="width: 50px; height: 50px; object-fit: cover; border-color: #1a1333 !important; border-radius: 10px !important;">
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background-color: #1a1333; font-size: 0.6em;">
                                            <i class="uil uil-check text-white"></i>
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <div class="d-flex flex-column align-items-center text-muted">
                                        <div class="rounded p-2" style="background-color: rgba(26, 19, 51, 0.1);">
                                            <i class="uil uil-user" style="color: #1a1333; opacity: 0.6;"></i>
                                        </div>
                                        <small style="font-size: 0.7rem;">Sin imagen</small>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm" role="group">
                                    <button class="btn btn-sm btn-editar-guardian"
                                        style="background-color: #1a1333; color: white; border-color: #1a1333;"
                                        data-id="<?= $guardian['id_usuario'] ?>"
                                        data-bs-toggle="tooltip" title="Editar guardián">
                                        <i class="uil uil-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger btn-eliminar-guardian shadow-sm"
                                        data-id="<?= $guardian['id_usuario'] ?>"
                                        data-bs-toggle="tooltip" title="Eliminar guardián">
                                        <i class="uil uil-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <i class="uil uil-users-alt display-4 mb-3" style="color: #1a1333; opacity: 0.3;"></i>
                                <h5 class="text-muted mb-2">No hay guardianes registrados</h5>
                                <p class="text-muted mb-0">¡Registra el primer guardián para comenzar!</p>
                            </div>
                        </td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para crear guardian -->
<div class="modal fade" id="modal-guardian" tabindex="-1" aria-labelledby="modalGuardianLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-gradient text-white border-0 py-4" style="background-color: #1a1333;">
                <h5 class="modal-title fw-bold mb-0 text-white" id="modalGuardianLabel">
                    <i class="uil uil-user-plus me-2"></i>Registrar Nuevo Guardián
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-4">
                <form id="form-registrar-guardian">
                    <input type="hidden" name="accion" value="registrar_guardian">

                    <!-- Sección Datos del Guardian -->
                    <fieldset class="border p-3 mb-4 rounded">
                        <legend class="text-center text-secondary fw-semibold" style="text-decoration: underline;">
                            <i class="uil uil-user me-1"></i>Datos del Guardián
                        </legend>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="guardian_nombre" class="form-label fw-semibold">
                                    <i class="uil uil-user-circle text-primary me-1"></i>Nombre
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control border-2" id="guardian_nombre" name="nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="guardian_apellido" class="form-label fw-semibold">
                                    <i class="uil uil-user-square text-primary me-1"></i>Apellido
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control border-2" id="guardian_apellido" name="apellido" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="guardian_contrasena" class="form-label fw-semibold">
                                    <i class="uil uil-lock text-primary me-1"></i>Contraseña
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control form-control border-2" id="guardian_contrasena" name="contrasena" required minlength="6">
                                <small class="text-muted">Mínimo 6 caracteres</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="guardian_email" class="form-label fw-semibold">
                                    <i class="uil uil-envelope text-primary me-1"></i>Correo electrónico
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control form-control border-2" id="guardian_email" name="email" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="guardian_direccion" class="form-label fw-semibold">
                                    <i class="uil uil-map-marker text-primary me-1"></i>Dirección
                                </label>
                                <input type="text" class="form-control form-control border-2" id="guardian_direccion" name="direccion" placeholder="Dirección completa">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="guardian_telefono" class="form-label fw-semibold">
                                    <i class="uil uil-phone text-primary me-1"></i>Teléfono
                                </label>
                                <input type="tel" class="form-control form-control border-2" id="guardian_telefono" name="telefono" placeholder="Ejemplo: +57 300 123 4567">
                            </div>
                        </div>
                    </fieldset>

                    <!-- Información adicional -->
                    <div class="alert alert-info border-0 bg-opacity-10" style="background-color: #fdaac4;">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="uil uil-info-circle text-info fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="alert-heading mb-1">Información importante</h6>
                                <p class="mb-0 small">Al registrar a un guardián, se creará automáticamente su perfil de usuario y podrá comenzar a adoptar mascotas.</p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-0 p-3">
                        <div class="d-flex gap-2 w-100 justify-content-end">
                            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                                <i class="uil uil-times-circle me-2"></i>Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary2 px-4">
                                <i class="uil uil-user-plus me-2"></i>Registrar Guardián
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar guardian -->
<div class="modal fade mt-4" id="modal-editar-guardian" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-gradient text-white border-0 py-4" style="background-color: #1a1333;">
                <h5 class="modal-title fw-bold mb-0 text-white">
                    <i class="uil uil-edit me-2"></i>Editar Guardián
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="contenido-editar">
                <!-- Aquí se cargará el contenido dinámicamente -->
            </div>
        </div>
    </div>
</div>