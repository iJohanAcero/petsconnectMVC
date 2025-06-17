<?php
require_once("../../Model/producto/ProductoModel.php");
$Modelo = new Productos();
?>

<div class="crud" id="crud">
    <h2 class="titulo">CRUD de Productos</h2>

    <table>
        <thead>
            <tr>
                <th>ID Producto</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Cantidad Disponible</th>
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
                        <td><?php echo $Producto['nombre']; ?></td>
                        <td><?php echo $Producto['tipo_producto']; ?></td>
                        <td><?php echo $Producto['descripcion']; ?></td>
                        <td>$<?php echo $Producto['precio']; ?></td>
                        <td><?php echo $Producto['cantidad_disponible']; ?></td>
                        <td>
                            <a class="btn-editar-producto" data-id="<?php echo $Producto['id_producto']; ?>">
                                <i class="uil uil-pen" style="cursor: pointer;"></i>
                            </a>
                            <form action="../../controller/producto/ProductoController.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $Producto['id_producto']; ?>">
                                <button type="submit" name="eliminar" class="delete" onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                    <i class="uil uil-trash-alt" style="cursor: pointer;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='8'>No hay productos registrados.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- ✅ BOTÓN para abrir el modal -->
    <a class="btn-añadir" id="btn-abrir-modal-producto">
        <i class="uil uil-plus-circle"></i> <span>Añadir producto</span>
    </a>

    <!-- ✅ MODAL Bootstrap para registrar producto -->
    <div class="modal fade" id="modal-productos" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Registrar nuevo producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="form-registrar-producto">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_producto" class="form-label">Tipo</label>
                            <select class="form-select" name="tipo_producto" required>
                                <option value="">Seleccione</option>
                                <option value="ComidaGato">Comida para gato</option>
                                <option value="ComidaPerro">Comida para perro</option>
                                <option value="ArenaGato">Arena para gatos</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control" name="descripcion" required>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad_disponible" class="form-label">Cantidad disponible</label>
                            <input type="number" class="form-control" name="cantidad_disponible" required>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" class="form-control" name="precio" required>
                        </div>
                        <input type="hidden" name="accion" value="registrar">
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Añadir producto</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>