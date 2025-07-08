<?php
require_once("../../Model/causa/CausaModel.php");
$Modelo = new Causa();
$Causas = $Modelo->getAll();
?>

<!-- Solo el contenido necesario: sin head, html, ni body -->
<div class="container crud-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gestión de Causas</h2>
        <button id="btn-abrir-modal-causa" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Añadir Causa
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered" id="tabla_causas">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Meta</th>
                    <th>Estado</th>
                    <th>Fecha creación</th>
                    <th>NIT Fundación</th>
                    <th>Imagen</th>
                    <th>Tipo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($Causas): ?>
                    <?php foreach ($Causas as $Causa): ?>
                        <tr>
                            <td><?= $Causa['id_causa'] ?></td>
                            <td><?= htmlspecialchars($Causa['nombre']) ?></td>
                            <td><?= htmlspecialchars($Causa['descripcion']) ?></td>
                            <td><?= htmlspecialchars($Causa['meta']) ?></td>
                            <td><?= htmlspecialchars($Causa['estado']) ?></td>
                            <td><?= htmlspecialchars($Causa['fecha_creacion']) ?></td>
                            <td><?= htmlspecialchars($Causa['nit_fundacion']) ?></td>
                            <td>
                                <?php if ($Causa['imagen']): ?>
                                    <img src="<?= htmlspecialchars($Causa['imagen']) ?>" alt="Imagen" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    Sin imagen
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($Causa['tipo']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-editar-causa" data-id="<?= $Causa['id_causa'] ?>">
                                    <i class="uil uil-pen"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-eliminar-causa" data-id="<?= $Causa['id_causa'] ?>">
                                    <i class="uil uil-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">No hay causas registradas</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para crear causa -->
<div class="modal fade" id="modal-causas" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-registrar-causa" method="POST" action="../../controller/causa/CausaController.php">
                <input type="hidden" name="accion" value="registrar">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Nueva Causa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Meta</label>
                        <input type="number" step="0.01" name="meta" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Estado</label>
                        <input type="text" name="estado_causa" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>NIT Fundación</label>
                        <input type="text" name="nit_fundacion" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Imagen URL</label>
                        <input type="text" name="imagen_url" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Tipo de causa</label>
                        <input type="text" name="tipo_causa" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal para editar causa -->
<div class="modal fade" id="modal-editar-causa" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="margin-top:50px">
            <div class="modal-header">
                <h5 class="modal-title">Editar Causa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenido-editar" style="margin-top: -15px;">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>
</body>

