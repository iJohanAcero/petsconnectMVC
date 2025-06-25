<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Fundación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="card-title">Eliminar Fundación</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="../../controller/fundacion/FundationController.php">
                    <input type="hidden" name="Id" value="<?php echo $Id; ?>">
                    <input type="hidden" name="action" value="delete">
                    
                    <p class="fs-5">¿Estás seguro que deseas eliminar esta fundación?</p>
                    <p class="text-muted">Esta acción no se puede deshacer.</p>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="FundacionView.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-danger">Confirmar Eliminación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>