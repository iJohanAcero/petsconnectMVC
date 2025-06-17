<?php
require_once("../../Model/producto/ProductoModel.php");

$Modelo = new Productos();

// Validación: si no se pasa el ID, se muestra un mensaje y se detiene la ejecución
if (!isset($_GET['id'])) {
    echo "ID de producto no especificado.";
    exit;
}

$id = $_GET['id']; // Ojo: era `$Id`, pero luego se usa `$id` en getId. Uniformamos.
$producto = $Modelo->getId($id);

// Si no se encuentra el producto con ese ID, se avisa y se detiene
if (!$producto || empty($producto)) {
    echo "Producto no encontrado.";
    exit;
}

$producto = $producto[0]; // Tomamos el primer registro si viene en forma de arreglo
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar producto</title>

    <!-- Bootstrap 5 CDN (CSS) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Contenedor principal de Bootstrap -->
    <div class="container mt-5">

        <!-- Título -->
        <h2 class="mb-4"></h2>

        <!-- Formulario con clases de Bootstrap -->
        <form id="form-editar-producto" method="POST" action="../../controller/producto/ProductoController.php">

            <!-- Campo oculto para el ID del producto -->
            <input type="hidden" name="id" value="<?= $producto['id_producto']; ?>">

            <!-- Nombre del producto -->
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= $producto['nombre']; ?>" required>
            </div>

            <!-- Tipo de producto -->
            <div class="mb-3">
                <label class="form-label">Tipo de producto</label>
                <select name="tipo_producto" class="form-select" required>
                    <option value="">Seleccione</option>
                    <option value="ComidaGato" <?= $producto['tipo_producto'] == 'ComidaGato' ? 'selected' : '' ?>>Comida para gato</option>
                    <option value="ComidaPerro" <?= $producto['tipo_producto'] == 'ComidaPerro' ? 'selected' : '' ?>>Comida para perro</option>
                    <option value="ArenaGato" <?= $producto['tipo_producto'] == 'ArenaGato' ? 'selected' : '' ?>>Arena para gatos</option>
                </select>
            </div>

            <!-- Descripción -->
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <input type="text" name="descripcion" class="form-control" value="<?= $producto['descripcion']; ?>" required>
            </div>

            <!-- Precio -->
            <div class="mb-3">
                <label class="form-label">Precio</label>
                <input type="number" name="precio" class="form-control" value="<?= $producto['precio']; ?>" step="0.01" required>
            </div>

            <!-- Cantidad disponible -->
            <div class="mb-3">
                <label class="form-label">Cantidad disponible</label>
                <input type="number" name="cantidad_dosponible" class="form-control" value="<?= $producto['cantidad_disponible']; ?>" required>
            </div>

            <input type="hidden" name="accion" value="editar">

            <!-- Botón de acción -->
            <button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>

            <!-- Enlace para regresar -->
            <a href="ProductoView.php" class="btn btn-secondary ms-2">← Volver a la lista</a>
        </form>
    </div>

    <!-- Bootstrap JS (para futuros modales, alerts, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>