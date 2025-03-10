<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Red Social</title>
</head>

<body>

    <h2 class="titulo">CRUD Red Social</h2>
    <div>
        <a href="#addProductModal" class="btn-añadir" data-bs-toggle="modal">
            <i class="uil uil-plus-circle"></i> <span>Añadir Red Social</span>
        </a>
        <a href="#deleteMultipleProductsModal" class="btn-eliminar" data-bs-toggle="modal">
            <i class="uil uil-trash"></i> <span>Eliminar Seleccionados</span>
        </a>
    </div>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>URL</th>
                <th>NIT Fundación</th>
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
                    <h4 class="modal-title">Añadir Red social</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">ID:</label>
                            <input type="number" class="form-control" name="id" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_producto" class="form-label">URL:</label>
                            <input type="link" class="form-control" name="url" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">NIT Fundación:</label>
                            <input type="number" class="form-control" name="nit fundacion" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary" name="btnregistrar">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>