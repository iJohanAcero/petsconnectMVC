<?php
require_once("../../Model/producto/ProductoModel.php");

$Modelo = new Productos();

if (!isset($_GET['id'])) {
    echo "ID de producto no especificado.";
    exit;
}

$id = $_GET['id'];
$producto = $Modelo->getId($id);
if (!$producto || empty($producto)) {
    echo "Producto no encontrado.";
    exit;
}

$producto = $producto[0];
?>

<body>
    <!-- Contenedor principal de Bootstrap -->
    <div class="container mt-1">
        <!-- Formulario con clases de Bootstrap -->
        <form  id="form-editar-producto" method="POST" action="../../controller/producto/ProductoController.php">

            <!-- Campo oculto para el ID del producto -->
            <input type="hidden" name="id" value="<?= $producto['id_producto']; ?>">
            <input type="hidden" name="accion" value="editar">

            <!-- Sección de Datos del Producto -->
            <fieldset class="mb-4 p-3 border rounded bg-light">
                <legend class="w-auto px-2 fs-6">Datos del Producto</legend>

                <!-- Nombre -->
                <div class="mb-3">
                    <label class="form-label">Nombre:</label>
                    <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']); ?>" required>
                </div>

                <!-- Tipo de producto -->
                <div class="mb-3">
                    <label class="form-label">Tipo de producto:</label>
                    <select name="tipo_producto" class="form-select" required>
                        <option value="">Seleccione</option>
                        <option value="ComidaGato" <?= $producto['tipo_producto'] == 'ComidaGato' ? 'selected' : '' ?>>Comida para gato</option>
                        <option value="ComidaPerro" <?= $producto['tipo_producto'] == 'ComidaPerro' ? 'selected' : '' ?>>Comida para perro</option>
                        <option value="ArenaGato" <?= $producto['tipo_producto'] == 'ArenaGato' ? 'selected' : '' ?>>Arena para gatos</option>
                    </select>
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label class="form-label">Descripción:</label>
                    <input type="text" name="descripcion" class="form-control" value="<?= htmlspecialchars($producto['descripcion']); ?>" required>
                </div>

                <!-- Precio -->
                <div class="mb-3">
                    <label class="form-label">Precio:</label>
                    <input type="number" name="precio" step="0.01" class="form-control" value="<?= $producto['precio']; ?>" required>
                </div>

                <!-- Cantidad -->
                <div class="mb-3">
                    <label class="form-label">Cantidad disponible:</label>
                    <input type="number" name="cantidad_disponible" class="form-control" value="<?= $producto['cantidad_disponible']; ?>" required>
                </div>
            </fieldset>


            <input type="hidden" name="accion" value="editar">

            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal" aria-label="Close">← Volver a la lista</button>
                <button type="submit" class="btn btn-primary">Actualizar Producto</button>
            </div>
        </form>
    </div>
</body>
