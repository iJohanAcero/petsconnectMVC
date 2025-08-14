<?php 
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Model\Guardian\Guardian;

$Modelo = new Guardian();
$guardianes = $Modelo->getGuardian();
?>

<div class="container crud-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gestión de Guardianes</h2>
        <button id="btn-abrir-modal-guardian" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Añadir Guardián
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered text-center" id="tabla_guardianes">
            <thead class= "table" style="background-color: #1a1333; color: white;">
                <tr>
                    <th>ID_usuario</th>
                    <th>ID_perfil</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($guardianes): ?>
                    <?php foreach ($guardianes as $guardian): ?>
                        <tr>
                            <td><?= htmlspecialchars($guardian['id_usuario']) ?></td>
                            <td><?= htmlspecialchars($guardian['id_perfil']) ?></td>
                            <td><?= htmlspecialchars($guardian['nombre_guardian']) ?> <?= htmlspecialchars($guardian['apellido_guardian']) ?></td>
                            <td><?= htmlspecialchars($guardian['correo']) ?></td>
                            <td><?= htmlspecialchars($guardian['telefono_guardian']) ?></td>
                            <td><?= htmlspecialchars($guardian['direccion_guardian']) ?></td>
                            <td>
                                    <img
                                        src="Public/images/perfil/<?php echo htmlspecialchars($guardian['imagen_guardian']); ?>"
                                        alt="Imagen"
                                        class="img-thumbnail img-clickable"
                                        style="max-width: 200px; max-height: 200px;"
                                        data-src="Public/images/eventos_fundacion/<?php echo htmlspecialchars($guardian['imagen_guardian']); ?>">
                                </td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-editar-guardian" data-id="<?= $guardian['id_usuario'] ?>">
                                    <i class="uil uil-pen"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-eliminar-guardian" data-id="<?= $guardian['id_usuario'] ?>">
                                    <i class="uil uil-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No hay fundaciones registradas</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para crear guardian -->
<div class="modal fade" id="modal-guardian" tabindex="-1" aria-labelledby="modalGuardianLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="margin-top: 70px;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1a1333; color: white;">
                <h5 class="modal-title" id="modalGuardianLabel" style="color: white;">Registrar Nuevo Guardian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="form-registrar-guardian">
                    <input type="hidden" name="accion" value="registrar_guardian">

                    <!-- Sección Datos del Guardian -->
                    <fieldset class="border p-3 mb-4 rounded">
                        <legend class="text-center text-secondary fw-semibold" style="text-decoration: underline;">
                            Datos del Guardian
                        </legend>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="guardian_nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="guardian_nombre" name="nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="guardian_apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="guardian_apellido" name="apellido" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="guardian_contrasena" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="guardian_contrasena" name="contrasena" required minlength="6">
                                <small class="text-muted">Mínimo 6 caracteres</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="guardian_email" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="guardian_email" name="email" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="guardian_direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="guardian_direccion" name="direccion" placeholder="Dirección completa">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="guardian_telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="guardian_telefono" name="telefono" placeholder="Ejemplo: +57 300 123 4567">
                            </div>
                        </div>
                    </fieldset>

                    <!-- Información adicional -->
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle"></i>
                        <strong>Información:</strong> Al registrar a un guardian, se creará automáticamente su perfil de usuario y podrá comenzar a adoptar mascotas.
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Registrar Guardian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar guardian -->
<div class="modal fade" id="modal-editar-guardian" tabindex="-1" aria-labelledby="modalEditarGuardianLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="margin-top: 70px;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1a1333; color: white;">
                <h5 class="modal-title" id="modalEditarGuardianLabel" style="color: white;">Editar Guardian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div id="contenido-editar">
                    <!-- Aquí se cargará el contenido dinámicamente -->
                </div>
            </div>
        </div>
    </div>
</div>
