<?php
require_once __DIR__ . '/../../Models/ProductoModel.php';

$modelo = new ProductoModel();
$productos = $modelo->obtenerProductos();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../Public/Css/index.css">
    <title>PRODUCTOS</title>
</head>

<body>
    <h1>Productos Registrados</h1>
    <!-- menu de navegacion -->
    <nav class="navbar">
        <a href="index.php">Inicio</a>
        <a href="create.php">Crear producto</a>
        <a href="../login.php">Salir</a>
    </nav>

    <div class="container">

        <table class="tabla">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Categoria</th>
                <th>Marca</th>
                <th>Acciones</th>
            </tr>
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= $producto['id']?></td>
                        <td><?= $producto['nombre']?></td>
                        <td><?= $producto['descripcion']?></td>
                        <td><?= $producto['precio']?></td>
                        <td><?= $producto['cantidad']?></td>
                        <td><?= $producto['categoria']?></td>
                        <td><?= $producto['marca']?></td>
                        <td>
                            <a href="edit.php?id=<?=$producto['id']?>" id="edit"><i class="bi bi-pencil-square"></i></a>
                            <a href="../../Controllers/DeleteController.php?id=<?=$producto['id']?>" id="delete"><i class="bi bi-trash-fill"></i></a>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No hay productos registrados</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>

</html>