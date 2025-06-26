<?php
require_once "../../Model/fundacion/FundacionModel.php";

if (!isset($_GET['id'])) {
    echo "Error: ID no proporcionado";
    exit;
}

$Modelo = new Fundacion();
$datos = $Modelo->getId($_GET['id']);
$fundacion = $datos[0] ?? null;

if (!$fundacion) {
    echo "Error: Fundación no encontrada";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Fundación</title>
    <link rel="stylesheet" href="../../Public/Css/edit.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Editar Fundación</h1>

        <form id="form-editar-fundacion" method="POST" class="border p-4 rounded bg-light">
            <!-- Indica que esta acción es una edición -->
            <input type="hidden" name="accion" value="editar_fundacion">

            <!-- NIT (identificador único) -->
            <input type="hidden" name="nit" value="<?= $fundacion['nit_fundacion']; ?>">

            <!-- Sección Representante -->
            <fieldset class="mb-4 p-3 border rounded">
                <legend class="w-auto px-2 fs-6">Datos del Representante</legend>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre:</label>
                        <input type="text" name="nombre" value="<?= $fundacion['nombre_representante']; ?>" class="form-control" required readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Apellido:</label>
                        <input type="text" name="apellido" value="<?= $fundacion['apellido_representante']; ?>" class="form-control" required readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email:</label>
                        <input type="email" name="email" value="<?= $fundacion['email']; ?>" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono:</label>
                        <input type="text" name="telefono" value="<?= $fundacion['telefono']; ?>" class="form-control" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Dirección:</label>
                        <input type="text" name="direccion" value="<?= $fundacion['direccion']; ?>" class="form-control" required>
                    </div>
                </div>
            </fieldset>

            <!-- Sección Fundación -->
            <fieldset class="mb-4 p-3 border rounded bg-light">
                <legend class="w-auto px-2 fs-6">Datos de la Fundación</legend>

                <div class="mb-3">
                    <label class="form-label">Nombre Fundación:</label>
                    <input type="text" class="form-control" value="<?= $fundacion['nombre_fundacion']; ?>" required readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">NIT:</label>
                    <input type="text" class="form-control" value="<?= $fundacion['nit_fundacion']; ?>" required readonly>
                    <small class="text-muted">El NIT no se puede modificar</small>
                </div>
            </fieldset>

            <div class="d-flex justify-content-between">
                <a href="FundacionView.php" class="btn btn-secondary">← Volver</a>
                <button type="submit" class="btn btn-primary">Actualizar Fundación</button>
            </div>
        </form>
    </div>
</body>
</html>
