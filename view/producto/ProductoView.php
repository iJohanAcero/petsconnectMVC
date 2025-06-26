<?php
require_once("../../Model/producto/ProductoModel.php");
$Modelo = new Productos();
?>

<body>
    <!-- Contenedor principal del CRUD con ID para JS -->
    <div class="container crud-container main-content" id="crud-container" style="padding: 40px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Gestión de Productos</h2>
            <button id="btn-abrir-modal-producto" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Añadir Producto
            </button>
        </div>

        <!-- Tabla de productos -->
        <div class="table-responsive" >
            <table class="table table-striped table-hover table-bordered" id="tabla_productos">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $Productos = $Modelo->getProducto();
                    if ($Productos !== null) {
                        foreach ($Productos as $Producto) {
                    ?>
                        <tr>
                            <td><?php echo $Producto['id_producto']; ?></td>
                            <td><?php echo htmlspecialchars($Producto['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($Producto['tipo_producto']); ?></td>
                            <td><?php echo htmlspecialchars($Producto['descripcion']); ?></td>
                            <td>$<?php echo number_format($Producto['precio'], 2); ?></td>
                            <td><?php echo $Producto['cantidad_disponible']; ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-editar-producto" data-id="<?php echo $Producto['id_producto']; ?>">
                                    <i class="uil uil-pen"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-eliminar-producto" data-id="<?php echo $Producto['id_producto']; ?>">
                                    <i class="uil uil-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay productos registrados</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para crear producto -->
    <div class="modal fade" id="modal-productos" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Registrar Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- ✅ Formulario sin method ni action -->
                    <form id="form-registrar-producto">
                        <input type="hidden" name="accion" value="registrar">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_producto" class="form-label">Tipo de Producto</label>
                            <select class="form-select" id="tipo_producto" name="tipo_producto" required>
                                <option value="">Seleccione...</option>
                                <option value="ComidaGato">Comida para gato</option>
                                <option value="ComidaPerro">Comida para perro</option>
                                <option value="ArenaGato">Arena para gatos</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad_disponible" class="form-label">Cantidad Disponible</label>
                            <input type="number" class="form-control" id="cantidad_disponible" name="cantidad_disponible" required>
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

    <!-- Modal para editar producto -->
    <div class="modal fade" id="modal-editar-producto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contenido-editar">
                    <!-- Se carga dinámicamente con JS -->
                </div>
            </div>
        </div>
    </div>
</body>

