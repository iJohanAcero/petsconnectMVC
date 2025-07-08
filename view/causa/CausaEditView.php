<?php
require_once("../../Model/causa/CausaModel.php");

$Modelo = new Causa();

if (!isset($_GET['id_causa'])) {
    echo "ID de causa no especificado.";
    exit;
}

$id_causa = $_GET['id_causa'];
$causa = $Modelo->getById($id_causa);
if (!$causa || empty($causa)) {
    echo "Causa no encontrada.";
    exit;
}

$causa = $causa[0];
?>

<body>
    <!-- Contenedor principal de Bootstrap -->
    <div class="container mt-1">
        <!-- Formulario con clases de Bootstrap -->
        <form  id="form-editar-causa" method="POST" action="../../controller/causa/CausaController.php">
            <input type="hidden" name="id_causa" value="<?= $causa['id_causa']; ?>">
            <input type="hidden" name="accion" value="editar">

            <!-- Sección de Datos de la Causa -->
            <fieldset class="mb-4 p-3 border rounded bg-light">
                <legend class="w-auto px-2 fs-6">Datos de la Causa</legend>

                <!-- Nombre -->
                <div class="mb-3">
                    <label class="form-label">Nombre:</label>
                    <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($causa['nombre']); ?>" required>
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label class="form-label">Descripción:</label>
                    <input type="text" name="descripcion" class="form-control" value="<?= htmlspecialchars($causa['descripcion']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Meta:</label>
                    <input type="number" step="0.01" name="meta" class="form-control" value="<?= $causa['meta']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Estado de la causa:</label>
                    <input type="text" name="estado_causa" class="form-control" value="<?= $causa['estado_causa']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">NIT Fundación:</label>
                    <input type="text" name="nit_fundacion" class="form-control" value="<?= $causa['nit_fundacion']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Imagen URL:</label>
                    <input type="text" name="imagen_url" class="form-control" value="<?= $causa['imagen_url']; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipo de causa:</label>
                    <input type="text" name="tipo_causa" class="form-control" value="<?= $causa['tipo_causa']; ?>">
                </div>
            </fieldset>

            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal" aria-label="Close">← Volver a la lista</button>
                <button type="submit" class="btn btn-primary">Actualizar Causa</button>
            </div>
        </form>
    </div>
</body>
