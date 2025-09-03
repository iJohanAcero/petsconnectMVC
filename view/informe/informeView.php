<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reportes - PetsConnect</title>
    <style>
        /* Solo estilos mínimos necesarios para mejorar la presentación */
        .dataTables_wrapper .dt-buttons {
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dt-buttons .btn {
            margin-right: 0.3rem;
            margin-bottom: 0.3rem;
        }

        .table-responsive {
            border-radius: 0 0 0.375rem 0.375rem;
        }

        .card-header {
            border-radius: 0.375rem 0.375rem 0 0 !important;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2 mb-0">
                <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Reportes de Fundación
            </h1>
        </div>
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header text-white py-3" style="background-color: #a3bced;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-heart-fill me-2"></i>
                    <h5 class="mb-0 text-dark">Mascotas adultas en adopción</h5>
                    <span class="badge bg-light text-dark ms-2" id="contadorMascotas">0</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tablaAdultas" class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Nombre</th>
                                <th>Especie</th>
                                <th class="text-center">Sexo</th>
                                <th class="text-center">Edad (meses)</th>
                                <th>Fundación</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-4">
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header text-white py-3" style="background-color: hsl(252, 30%, 17%);">
                <div class="d-flex align-items-center">
                    <i class="bi bi-heart-fill me-2"></i>
                    <h5 class="mb-0 text-white">Mascotas con mas procesos de adopción</h5>
                    <span class="badge bg-light text-dark ms-2" id="contadorMascotasPopulares">0</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tablaPopulares" class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Nombre</th>
                                <th>Especie</th>
                                <th>Fundación</th>
                                <th>total_solicitudes</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>