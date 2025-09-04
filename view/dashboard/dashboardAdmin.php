<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - PetsConnect</title>
</head>

<body>
    <div id="dashboardAdminContent">
        <h1 class="display-4 mb-3"> Dashboard Administrador</h1>
        <p class="lead text-muted mb-3">Bienvenido al panel de control del administrador. Aquí puedes visualizar las estadísticas administrativas de PetsConnect.</p>
    </div>

    <div class="col-md-10 col-lg-10">
        <!-- Primera fila - Estadísticas por mes -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header">
                            <h5 class="card-title">Donaciones totales</h5><small class="text-muted">por mes</small>
                        </div>
                        <div class="mt-2">
                            <canvas id="chartDonacionesMes" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta estadística 2 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header">
                            <h5 class="card-title">Guardianes registrados</h5><small class="text-muted">por mes</small>
                        </div>
                        <div class="mt-2">
                            <canvas id="chartGuardianesMes" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta estadística 3 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header">
                            <h5 class="card-title">Publicaciones registradas</h5><small class="text-muted">por mes</small>
                        </div>
                        <div class="mt-2">
                            <canvas id="chartPublicacionesMes" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda fila - Gráficos principales -->
        <div class="row mb-4">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Distribución de Mascotas (Felinos vs Caninos)</h5><small class="text-muted">Total de mascotas registradas</small>
                    </div>
                    <div class="mt-2">
                        <canvas id="chartMascotasEspecie" height="100"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Tipos de Causas</h5><small class="text-muted">Total de causas registradas</small>
                    </div>
                    <div class="mt-2">
                        <canvas id="chartTiposCausas" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tercera fila - Gráficos adicionales -->
            <div class="row mb-4 mt-4">

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Mascotas por Estado</h5><small class="text-muted">Total de mascotas por estado</small>
                        </div>
                        <div class="mt-2">
                            <canvas id="chartMascotasEstado" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Ranking de Fundaciones</h5><small class="text-muted">Recaudación total</small>
                        </div>
                        <div class="mt-2">
                            <canvas id="chartRankingFundaciones" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Usuarios Registrados</h5><small class="text-muted">Total de usuarios registrados</small>
                        </div>
                        <div class="mt-2">
                            <canvas id="chartUsuariosRegistrados" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
</body>

</html>