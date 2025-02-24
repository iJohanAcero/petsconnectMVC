<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Vacunación</title>
</head>

<body>

    <h2 class="titulo">CRUD de Vacunación</h2>
    <div>
        <a href="#addProductModal" class="btn-añadir" data-bs-toggle="modal">
            <i class="uil uil-plus-circle"></i> <span>Añadir Producto</span>
        </a>
        <a href="#deleteMultipleProductsModal" class="btn-eliminar" data-bs-toggle="modal">
            <i class="uil uil-trash"></i> <span>Eliminar Seleccionados</span>
        </a>
    </div>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha de Vacunación</th>
                <th>Nombre de Vacuna</th>
                <th>Dirección Veterinaria</th>
                <th>Acciones</th>

            </tr>

            <tr>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>
                    <a href="#editProductModal" class="edit" data-bs-toggle="modal">
                        <i class="uil uil-pen"></i>
                    </a>
                    <a href="#deleteProductModal" class="delete" data-bs-toggle="modal">
                        <i class="uil uil-trash-alt"></i>
                    </a>
                </td>
            </tr>
        </thead>

    </table>

    <div id="addProductModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Añadir Producto</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_producto" class="form-label">Tipo de producto:</label>
                            <input type="text" class="form-control" name="tipo_producto" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción:</label>
                            <input type="text" class="form-control" name="descripcion" required>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio:</label>
                            <input type="number" class="form-control" name="precio" required>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad:</label>
                            <input type="number" class="form-control" name="cantidad" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary" name="btnregistrar">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>