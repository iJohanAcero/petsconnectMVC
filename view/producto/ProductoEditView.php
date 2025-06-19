<?php
require_once("../../Model/producto/ProductoModel.php");

$Modelo = new Productos();

// Validación: si no se pasa el ID, se muestra un mensaje y se detiene la ejecución
if (!isset($_GET['id'])) {
    echo "ID de producto no especificado.";
    exit;
}

$id = $_GET['id'];
$producto = $Modelo->getId($id);

// Si no se encuentra el producto con ese ID, se avisa y se detiene
if (!$producto || empty($producto)) {
    echo "Producto no encontrado.";
    exit;
}

$producto = $producto[0]; // Tomamos el primer registro
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Actualizar producto</title>

    <!-- Bootstrap 5 CDN (CSS) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: #f8f9fa;"> <!-- Añadido estilo inline para el fondo -->
    <!-- Contenedor principal con estilos inline -->
    <div class="container mt-5" style="padding: 30px;">

        <!-- Título con estilo inline -->
        <h2 class="mb-4" style="padding-bottom: 10px;">Editar Producto</h2>

        <!-- Formulario de edición - Cambios importantes: -->
        <!-- 1. Cambiado el action para apuntar directamente al controlador -->
        <!-- 2. Reemplazado name="editar" por name="accion" value="editar" para coincidir con el controlador -->
        <!-- 3. Corregido el name="cantidad_dosponible" a "cantidad_disponible" -->
        <form method="POST" action="../../controller/producto/ProductoController.php" style="margin-top: 20px;">

            <!-- Campo oculto para el ID del producto -->
            <input type="hidden" name="id" value="<?= $producto['id_producto']; ?>">
            <!-- Añadido campo accion para que el controlador identifique la acción -->
            <input type="hidden" name="accion" value="actualizar">

            <!-- Nombre del producto -->
            <div class="mb-3" style="margin-bottom: 15px;">
                <label class="form-label" style="font-weight: bold;">Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']); ?>" required
                    style="padding: 10px;">
            </div>

            <!-- Tipo de producto -->
            <div class="mb-3" style="margin-bottom: 15px;">
                <label class="form-label" style="font-weight: bold;">Tipo de producto</label>
                <select name="tipo_producto" class="form-select" required
                    style="padding: 10px;">
                    <option value="">Seleccione</option>
                    <option value="ComidaGato" <?= $producto['tipo_producto'] == 'ComidaGato' ? 'selected' : '' ?>>Comida para gato</option>
                    <option value="ComidaPerro" <?= $producto['tipo_producto'] == 'ComidaPerro' ? 'selected' : '' ?>>Comida para perro</option>
                    <option value="ArenaGato" <?= $producto['tipo_producto'] == 'ArenaGato' ? 'selected' : '' ?>>Arena para gatos</option>
                </select>
            </div>

            <!-- Descripción -->
            <div class="mb-3" style="margin-bottom: 15px;">
                <label class="form-label" style="font-weight: bold;">Descripción</label>
                <input type="text" name="descripcion" class="form-control" value="<?= htmlspecialchars($producto['descripcion']); ?>" required
                    style="padding: 10px;">
            </div>

            <!-- Precio -->
            <div class="mb-3" style="margin-bottom: 15px;">
                <label class="form-label" style="font-weight: bold;">Precio</label>
                <input type="number" name="precio" class="form-control" value="<?= $producto['precio']; ?>" step="0.01" required
                    style="padding: 10px;">
            </div>

            <!-- Cantidad disponible -->
            <div class="mb-3">
                <label class="form-label">Cantidad disponible</label>
                <input type="number" name="cantidad_disponible" class="form-control" value="<?= $producto['cantidad_disponible']; ?>" required>
            </div>

            <input type="hidden" name="accion" value="editar">

            <!-- Botón de acción -->
            <button type="submit"   class="btn btn-primary">Actualizar</button>

            <!-- Enlace para regresar -->
            <a href="ProductoView.php" class="btn btn-secondary ms-2">← Volver a la lista</a>
        </form>
    </div>

    <!-- Bootstrap JS (para futuros modales, alerts, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>