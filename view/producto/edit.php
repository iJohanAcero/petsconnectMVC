<?php
require_once("../Modelo/ProductoModel.php");

$Modelo = new Productos();

if (!isset($_GET['Id'])) {
    echo "ID de producto no especificado.";
    exit;
}

$Id = $_GET['Id'];
$producto = $Modelo->getId($Id);

if (!$producto || empty($producto)) {
    echo "Producto no encontrado.";
    exit;
}

$producto = $producto[0]; // Obtener el primer (y único) resultado si devuelve array de 1 producto
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar producto</title>
    <link rel="stylesheet" href="../../Public/Css/edit.css">
</head>

<body>
    <h1>Editar producto</h1>
    <form method="POST" action="../../controller/producto/ProductoController.php">
        <input type="hidden" name="Id" value="<?= $producto['id_producto']; ?>">

        <label>Nombre:</label><br>
        <input type="text" name="Nombre" value="<?= $producto['nombre']; ?>" required><br><br>

        <label>Tipo de producto:</label><br>
        <select name="TipoProducto" required>
            <option value="">Seleccione</option>
            <option value="ComidaGato" <?= $producto['tipo_producto'] == 'ComidaGato' ? 'selected' : '' ?>>Comida para gato</option>
            <option value="ComidaPerro" <?= $producto['tipo_producto'] == 'ComidaPerro' ? 'selected' : '' ?>>Comida para perro</option>
            <option value="ArenaGato" <?= $producto['tipo_producto'] == 'ArenaGato' ? 'selected' : '' ?>>Arena para gatos</option>
        </select><br><br>

        <label>Descripción:</label><br>
        <input type="text" name="Descripcion" value="<?= $producto['descripcion']; ?>" required><br><br>

        <label>Precio:</label><br>
        <input type="number" name="Precio" value="<?= $producto['precio']; ?>" step="0.01" required><br><br>

        <label>Cantidad disponible:</label><br>
        <input type="number" name="Cantidad" value="<?= $producto['cantidad_disponible']; ?>" required><br><br>

        <button type="submit" name="actualizar">Actualizar</button>
    </form><br><br>

    <div>
        <a href="ProductoView.php">← Volver a la lista de productos</a>
    </div>
</body>

</html>
