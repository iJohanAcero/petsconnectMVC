<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Fundación</title>
    <link rel="stylesheet" href="../../Public/Css/edit.css">
    <!-- Agregar Bootstrap si no lo tienes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">Editar Fundación</h1>
        <form method="POST" action="../Controlador/edit.php" class="border p-4 rounded">
            <input type="hidden" name="Id" value="<?= $fundacion['nit_fundacion']; ?>">

            <!-- Sección Representante -->
            <fieldset class="mb-4 p-3 border rounded">
                <legend class="w-auto px-2 fs-6">Datos del Representante</legend>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre:</label>
                        <input type="text" name="rep_nombre" value="<?= $fundacion['nombre_representante']; ?>" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Apellido:</label>
                        <input type="text" name="rep_apellido" value="<?= $fundacion['apellido_representante']; ?>" class="form-control" required>
                    </div>
                </div>
                <!-- Más campos del representante... -->
            </fieldset>

            <!-- Sección Fundación -->
            <fieldset class="mb-4 p-3 border rounded bg-light">
                <legend class="w-auto px-2 fs-6">Datos de la Fundación</legend>
                
                <div class="mb-3">
                    <label class="form-label">Nombre Fundación:</label>
                    <input type="text" name="fund_nombre" value="<?= $fundacion['nombre_fundacion']; ?>" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">NIT:</label>
                    <input type="text" name="fund_nit" value="<?= $fundacion['nit_fundacion']; ?>" class="form-control" readonly>
                    <small class="text-muted">El NIT no puede modificarse</small>
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
