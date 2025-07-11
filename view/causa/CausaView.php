<?php
session_start();
require_once("../../Model/causa/CausaModel.php");
require_once("../../Model/fundacion/FundacionModel.php");
$Modelo = new Causa();

$nit_fundacion = null;
if (isset($_SESSION["user"]["id_usuario"])) {
    $nit_fundacion = Fundacion::obtenerNitPorUsuario($_SESSION["user"]["id_usuario"]);
}
?>
<body>
    <!-- Contenedor principal del CRUD con ID para JS -->
    <div class="container crud-container main-content" id="crud-container" style="padding: 40px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Gestión de Causas</h2>
            <?php if (isset($_SESSION["tipo_usuario"]) && $_SESSION["tipo_usuario"] === "fundacion"): ?>
   <button id="btn-abrir-modal-causa" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Añadir Causa
    </button>
<?php endif; ?>
        </div>

        <!-- Tabla de causas -->
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered" id="tabla_causa">
                <thead class="table-dark">
                    <tr>
                        <th>ID Causa</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Meta</th>
                        <th>Estado de causa</th>
                        <th>Fecha de creación</th>
                        <th>Nit fundación</th>
                        <th>Imagen</th>
                        <th>Tipo de causa</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $Causa = $Modelo->getCausa();
                    if ($Causa !== null) {
                        foreach ($Causa as $Causa) {
                    ?>
                            <tr>
                                <td><?php echo $Causa['id_causa']; ?></td>
                                <td><?php echo htmlspecialchars($Causa['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($Causa['descripcion']); ?></td>
                                <td><?php echo $Causa['meta']; ?></td>
                                <td><?php echo htmlspecialchars($Causa['estado_causa']); ?></td>
                                <td><?php echo $Causa['fecha_creacion']; ?></td>
                                <td><?php echo htmlspecialchars($Causa['nit_fundacion']); ?></td>
                                <td>
                                    <?php if (!empty($Causa['imagen_url'])): ?>
                                        <img
                                            src="Public/images/causa/<?php echo htmlspecialchars($Causa['imagen_url']); ?>"
                                            alt="Imagen"
                                            class="img-thumbnail img-clickable"
                                            style="max-width: 200px; max-height: 200px;"
                                            data-src="Public/images/causa/<?php echo htmlspecialchars($Causa['imagen_url']); ?>">
                                    <?php else: ?>
                                        Sin imagen
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($Causa['tipo_causa']); ?></td>
                                <td>
                                
                                    <button class="btn btn-sm btn-warning btn-editar-causa" data-id="<?php echo $Causa['id_causa']; ?>">
                                        <i class="uil uil-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger btn-eliminar-causa" data-id="<?php echo $Causa['id_causa']; ?>">
                                        <i class="uil uil-trash"></i>
                                    </button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay causa registrada</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para crear causa -->
    <div class="modal fade" id="modal-causa" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Registrar Nueva Causa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- ✅ Formulario sin method ni action -->
                    <form id="form-registrar-causa" method="POST" enctype="multipart/form-data" action="../../controller/causa/CausaController.php">
    <input type="hidden" name="accion" value="registrar">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <input type="text" class="form-control" id="descripcion" name="descripcion" required>
    </div>
    <div class="mb-3">
        <label for="meta" class="form-label">Meta</label>
        <input type="text" class="form-control" id="meta" name="meta" required>
    </div>
    <div class="mb-3">
        <label for="estado_causa" class="form-label">Estado de causa</label>
        <select class="form-select" id="estado_causa" name="estado_causa" required>
            <option value="">Selecciona estado</option>
            <option value="activa">Activa</option>
            <option value="en pausa">En pausa</option>
            <option value="cumplida">Cumplida</option>
            <option value="cancelada">Cancelada</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="tipo_causa" class="form-label">Tipo de causa</label>
        <select class="form-select" id="tipo_causa" name="tipo_causa" required>
            <option value="">Selecciona tipo</option>
            <option value="alimentación">Alimentación</option>
            <option value="medicamentos">Medicamentos</option>
            <option value="esterilizacion">Esterilización</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="imagen" class="form-label">Imagen</label>
        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
    </div>
    <input type="hidden" name="nit_fundacion" value="<?php echo htmlspecialchars($nit_fundacion); ?>">
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar causa -->
    <div class="modal fade" id="modal-editar-causa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar causa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contenido-editar">
                    <!-- Se carga dinámicamente con JS -->
                </div>
            </div>
        </div>
    </div>
        <script src="../../Public/js/main.js"></script>
</body>