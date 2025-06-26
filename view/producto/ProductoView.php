<?php
require_once("../../Model/producto/ProductoModel.php");
$Modelo = new Productos();
$Productos = $Modelo->getProducto(); // ¡Esta línea faltaba!
// Mostrar mensajes de sesión
// if (isset($_SESSION['mensaje'])) {
//     $tipo = $_SESSION['tipo_mensaje'] ?? 'info';
//     echo '<div class="alert alert-'.$tipo.'">'.$_SESSION['mensaje'].'</div>';
//     // Limpiar los mensajes después de mostrarlos
//     unset($_SESSION['mensaje']);
//     unset($_SESSION['tipo_mensaje']);
// }
?>

<!-- Solo el contenido necesario: sin head, html, ni body -->
<div class="container crud-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gestión de Productos</h2>
        <button id="btn-abrir-modal-producto" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Añadir Producto
        </button>
    </div>

    <div class="table-responsive">
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
                <?php if ($Productos): ?>
                    <?php foreach ($Productos as $Producto): ?>
                        <tr>
                            <td><?= $Producto['id_producto'] ?></td>
                            <td><?= htmlspecialchars($Producto['nombre']) ?></td>
                            <td><?= htmlspecialchars($Producto['tipo_producto']) ?></td>
                            <td><?= htmlspecialchars($Producto['descripcion']) ?></td>
                            <td>$<?= number_format($Producto['precio'], 2) ?></td>
                            <td><?= $Producto['cantidad_disponible'] ?></td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-editar-producto" data-id="<?= $Producto['id_producto'] ?>">
                                    <i class="uil uil-pen"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-eliminar-producto" data-id="<?= $Producto['id_producto'] ?>">
                                    <i class="uil uil-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No hay productos registrados</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para crear producto -->
<div class="modal fade" id="modal-productos" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-registrar-producto">
                <input type="hidden" name="accion" value="registrar">

                <div class="modal-header">
                    <h5 class="modal-title">Registrar Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- campos -->
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
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
<div class="modal fade" id="modal-editar-producto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="margin-top:50px">
            <div class="modal-header">
                <h5 class="modal-title">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenido-editar" style="margin-top: -15px;">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>
</div>