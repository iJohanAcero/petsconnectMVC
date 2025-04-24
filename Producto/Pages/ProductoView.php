<?php
////////////////////VALIDAR SESIÓN//////////////////
require_once ("../Modelo/ProductoModel.php");
$Modelo = new Productos();

if (isset($_POST['btnregistrar'])) {
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo_producto'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    $Modelo->add($nombre, $tipo, $descripcion, $cantidad, $precio);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/1b41a5ad09.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <script>
        function eliminar() {
            return confirm("¿Está seguro de querer eliminar el registro?");
        }
    </script>

    <div class="container-xl mt-4">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title bg-dark text-white p-3 rounded">
                    <div class="row">
                        <div class="col-sm-6">
                            <h2 class="mb-0">Registro de <b>Productos</b></h2>
                        </div>
                        <div class="col-sm-6 text-end">
                            <a href="#addProductModal" class="btn btn-success" data-bs-toggle="modal">
                                <i class="material-icons">&#xE147;</i> <span>Añadir Producto</span>
                            </a>
                            <a href="#deleteMultipleProductsModal" class="btn btn-danger" data-bs-toggle="modal">
                                <i class="material-icons">&#xE872;</i> <span>Eliminar Seleccionados</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <table class="table table-striped table-hover mt-3">
                        <thead class="table-dark">
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Tipo de producto</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $Productos = $Modelo->getProducto();
                            if($Productos!==null){
                                foreach($Productos as $Producto){
                            ?>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td><?php echo $Producto ['id_producto']; ?></td>
                                <td><?php echo $Producto ['nombre']; ?></td>
                                <td><?php echo $Producto ['tipo_producto']; ?></td>
                                <td><?php echo $Producto ['descripcion']; ?></td>
                                <td>$<?php echo $Producto ['precio']; ?></td>
                                <td><?php echo $Producto ['cantidad_disponible']; ?></td>
                                <td>
                                    <a href="#editProductModal" class="edit" data-bs-toggle="modal">
                                        <i class="material-icons text-warning">&#xE254;</i>
                                    </a>
                                    <a href="#deleteProductModal" class="delete" data-bs-toggle="modal">
                                        <i class="material-icons text-danger">&#xE872;</i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para añadir producto -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>