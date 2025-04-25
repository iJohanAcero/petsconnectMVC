<?php
////////////////////VALIDAR SESIÓN//////////////////
require_once("../Modelo/ProductoModel.php");
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
    <title>CRUD Productos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Podés linkear tus estilos aquí -->
    <link rel="stylesheet" href="../css/estilos.css"> <!-- opcional -->
</head>

<body>
    <div class="crud">
        <h2 class="titulo">CRUD de Productos</h2>

        <a class="btn-añadir" id="openModal">
            <i class="uil uil-plus-circle"></i> <span>Añadir producto</span>
        </a>
    </div>

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
                            <a id="OpenModal" class="edit" data-id="<?php echo $Producto['id_producto']; ?>">
                                <i class="uil uil-pen" style="cursor: pointer;"></i>
                            </a>
                            <a href="delete.php?Id=<?php echo $Producto['id_producto']; ?>" class="delete">
                                <i class="uil uil-trash-alt" style="cursor: pointer;"></i>
                            </a>
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

    <!-- MODAL PARA REGISTRO -->
    <div id="modal-productos" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="titulo-modal">¡Registrar nuevo producto!</h2>

            <form action="../Controlador/add.php" method="POST" class="form-modal">
                <label for="nombre">Nombre:</label>
                <input class="input-modal" type="text" id="nombre" name="nombre" autocomplete="off" required>

                <label for="tipo_producto">Tipo:</label>
                <select class="input-modal" name="TipoProducto" required>
                    <option>Seleccione</option>
                    <option value="ComidaGato">Comida para gato</option>
                    <option value="ComidaPerro">Comida para perro</option>
                    <option value="ArenaGato">Arena para gatos</option>
                </select><br><br>

                <label for="descripcion">Descripción:</label>
                <input class="input-modal" type="text" id="descripcion" name="descripcion" autocomplete="off" required><br><br>

                <label for="precio">Precio:</label>
                <input class="input-modal" type="number" id="precio" name="precio" step="0.01" autocomplete="off" required><br><br>

                <label for="cantidad_disponible">Cantidad:</label>
                <input class="input-modal" type="number" id="cantidad_disponible" name="cantidad_disponible" autocomplete="off" required><br><br>

                <button class="btn-añadir" type="submit">Guardar</button>
            </form>
        </div>
    </div>

    <div id="modal-edit-productos" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="titulo-modal">Editar producto!</h2>

            <form action="../Controlador/add.php" method="POST" class="form-modal">
                <label for="nombre">Nombre:</label>
                <input class="input-modal" type="text" id="nombre" name="nombre" autocomplete="off" required>

                <label for="tipo_producto">Tipo:</label>
                <select class="input-modal" name="TipoProducto" required>
                    <option>Seleccione</option>
                    <option value="ComidaGato">Comida para gato</option>
                    <option value="ComidaPerro">Comida para perro</option>
                    <option value="ArenaGato">Arena para gatos</option>
                </select><br><br>

                <label for="descripcion">Descripción:</label>
                <input class="input-modal" type="text" id="descripcion" name="descripcion" autocomplete="off" required><br><br>

                <label for="precio">Precio:</label>
                <input class="input-modal" type="number" id="precio" name="precio" step="0.01" autocomplete="off" required><br><br>

                <label for="cantidad_disponible">Cantidad:</label>
                <input class="input-modal" type="number" id="cantidad_disponible" name="cantidad_disponible" autocomplete="off" required><br><br>

                <button class="btn-añadir" type="submit">Guardar</button>
            </form>
        </div>
    </div>

</body>

</html>