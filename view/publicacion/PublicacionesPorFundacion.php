<?php
session_start();
require_once("../../Model/publicacion/PublicacionModel.php");
require_once("../../Model/fundacion/FundacionModel.php");
$Modelo = new Publicacion();

$nit_fundacion = null;
if (isset($_SESSION["user"]["id_usuario"])) {
    $nit_fundacion = Fundacion::obtenerNitPorUsuario($_SESSION["user"]["id_usuario"]);
}
?>
<body>
    <!-- Contenedor principal del CRUD con ID para JS -->
    <div class="container crud-container main-content" id="crud-container" style="padding: 40px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Gestión de Publicaciones</h2>
            <?php if (isset($_SESSION["tipo_usuario"]) && $_SESSION["tipo_usuario"] === "fundacion"): ?>
    <button id="btn-abrir-modal-publicacion" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Añadir Publicacion
    </button>
<?php endif; ?>
        </div>

        <!-- Tabla de publicacion -->
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered" id="tabla_publicacion">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Titulo</th>
                        <th>Contenido</th>
                        <th>Imagen</th>
                        <th>fecha subida</th>
                        <th>Nit fundacion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $Publicacion = $Modelo->getPublicacion();
                    if ($Publicacion !== null) {
                        foreach ($Publicacion as $Publicacion) {
                    ?>
                            <tr>
                                <td><?php echo $Publicacion['id_publicacion']; ?></td>
                                <td><?php echo htmlspecialchars($Publicacion['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($Publicacion['contenido']); ?></td>
                                <td>
                                    <img
                                        src="Public/images/eventos_fundacion/<?php echo htmlspecialchars($Publicacion['imagen']); ?>"
                                        alt="Imagen"
                                        class="img-thumbnail img-clickable"
                                        style="max-width: 200px; max-height: 200px;"
                                        data-src="Public/images/eventos_fundacion/<?php echo htmlspecialchars($Publicacion['imagen']); ?>">
                                </td>
                                <td><?php echo htmlspecialchars($Publicacion['fecha']); ?></td>
                                <td><?php echo $Publicacion['nit_fundacion']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning btn-editar-publicacion" data-id="<?php echo $Publicacion['id_publicacion']; ?>">
                                        <i class="uil uil-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger btn-eliminar-publicacion" data-id="<?php echo $Publicacion['id_publicacion']; ?>">
                                        <i class="uil uil-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay publicacion registrados</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para crear publicacion -->
    <div class="modal fade" id="modal-publicacion" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Registrar Nueva Publicacion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- ✅ Formulario sin method ni action -->
                    <form id="form-registrar-publicacion" enctype="multipart/form-data">
                        <input type="hidden" name="accion" value="registrar">
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Titulo</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="contenido" class="form-label">Contenido</label>
                            <input type="text" class="form-control" id="contenido" name="contenido" required>
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                        </div>
                        <div class="mb-3">
            
                            <input type="hidden" class="form-control" id="nit_fundacion" name="nit_fundacion" value="<?php echo htmlspecialchars($nit_fundacion); ?>" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar publicacion -->
    <div class="modal fade" id="modal-editar-publicacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Publicacion</h5>
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