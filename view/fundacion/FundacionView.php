<?php
require_once("../../Model/fundacion/FundacionModel.php");
$Modelo = new Fundacion();
$Fundaciones = $Modelo->getFundacion(); // Suponiendo que tienes este método
?>

<div class="container crud-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gestión de Fundaciones</h2>
        <button id="btn-abrir-modal-fundacion" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Añadir Fundación
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered" id="tabla_fundaciones">
            <thead class="table-dark">
                <tr>
                    <th>NIT</th>
                    <th>Nombre Fundación</th>
                    <th>Representante</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($Fundaciones): ?>
                    <?php foreach ($Fundaciones as $fundacion): ?>
                        <tr>
                            <td><?= htmlspecialchars($fundacion['nit']) ?></td>
                            <td><?= htmlspecialchars($fundacion['nombre_fundacion']) ?></td>
                            <td><?= htmlspecialchars($fundacion['nombre_representante']) ?> <?= htmlspecialchars($fundacion['apellido_representante']) ?></td>
                            <td><?= htmlspecialchars($fundacion['correo']) ?></td>
                            <td><?= htmlspecialchars($fundacion['telefono']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-editar-fundacion" data-id="<?= $fundacion['nit'] ?>">
                                    <i class="uil uil-pen"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-eliminar-fundacion" data-id="<?= $fundacion['nit'] ?>">
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

<!-- Modal para registrar fundación -->
<?=
/** Aquí puedes pegar directamente tu formulario completo de la fundación */
"" ?>
<div class="modal fade" id="modal-fundacion" tabindex="-1" aria-labelledby="modalFundacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="margin-top: 70px;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1a1333; color: white;">
                <h5 class="modal-title" id="modalFundacionLabel" style="color: white;">Registrar Nueva Fundación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="form-registrar-fundacion">
                    <input type="hidden" name="accion" value="registrar_fundacion">

                    <!-- Sección Representante Legal -->
                    <fieldset class="border p-3 mb-4 rounded">
                        <legend class="text-center text-secondary fw-semibold" style="text-decoration: underline;">
                            Datos del Representante legal
                        </legend>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rep_nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="rep_nombre" name="rep_nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="rep_apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="rep_apellido" name="rep_apellido" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rep_contrasena" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="rep_contrasena" name="rep_contrasena" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="rep_email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="rep_email" name="rep_email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rep_direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="rep_direccion" name="rep_direccion" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="rep_telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="rep_telefono" name="rep_telefono" required>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Sección Fundación -->
                    <fieldset class="border p-3 mb-4 rounded bg-light">
                        <legend class="text-center text-secondary fw-semibold" style="text-decoration: underline;">
                            Datos de la fundación
                        </legend>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fund_nombre" class="form-label">Nombre legal de la fundación</label>
                                <input type="text" class="form-control" id="fund_nombre" name="fund_nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fund_nit" class="form-label">NIT de la fundación</label>
                                <input type="text" class="form-control" id="fund_nit" name="fund_nit" required>
                                <small class="text-muted">Ejemplo: 1234567890</small>
                            </div>
                        </div>
                    </fieldset>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Registrar Fundación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal editar fundación -->
<div class="modal fade" id="modal-editar-fundacion" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="margin-top: 80px;">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Editar Fundación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenido-editar">
                <!-- Aquí va el contenido dinámico con JavaScript -->
            </div>
        </div>
    </div>
</div>