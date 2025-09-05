<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Donacion\Donacion;
use App\Model\Fundacion\Fundacion;
use App\Config\Roles;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$Modelo = new Donacion();

$nit_fundacion = null;
$id_usuario = $_SESSION['user']['id_usuario'] ?? null;
$esAdmin = $id_usuario && Roles::esAdmin($id_usuario);
$esFundacion = $id_usuario && Roles::esFundacion($id_usuario);

if (isset($_SESSION["user"]["id_usuario"])) {
    $nit_fundacion = Fundacion::obtenerNitPorUsuario($_SESSION["user"]["id_usuario"]);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - PetsConnect</title>
</head>

<body>
    <div id="dashboardAdminContent">
        <h1 class="display-4 mb-3"> Dashboard Fundaciónes</h1>
        <p class="lead text-muted mb-3">Bienvenido al panel de control de las fundaciones. Aquí puedes visualizar las estadísticas de tu fundación.</p>
    </div>

    <div class="col-md-10 col-lg-10">
        <!-- Primera fila - Estadísticas por mes -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header">
                            <h5 class="card-title">Evolución de donaciones</h5><small class="text-muted">Por mes</small>
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
                            <h5 class="card-title">Adopciones exitosas</h5><small class="text-muted">Por mes</small>
                        </div>
                        <div class="mt-2">
                            <canvas id="chartMascotasAdoptadasMes" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta estadística 3 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header">
                            <h5 class="card-title">Publicaciones registradas</h5><small class="text-muted">Por mes</small>
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
                        <h5 class="card-title">Adopciones por especie</h5><small class="text-muted">Total de adopciones</small>
                    </div>
                    <div class="mt-2">
                        <canvas id="chartAdopcionesEspecie" height="100"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Tipos de Causas</h5><small class="text-muted">Total de causas registradas</small>
                    </div>
                    <div class="mt-2">
                        <canvas id="chartCausasTipo" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tercera fila - Gráficos adicionales -->
            <div class="row mb-4 mt-4">

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Mascotas por estado</h5><small class="text-muted">Total mascotas registradas</small>
                        </div>
                        <div class="mt-2">
                            <canvas id="chartMascotasEstado" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            
                            <h5 class="card-title">Donaciones por causa</h5><small class="text-muted">Total recaudado</small>
                        </div>
                        <div class="mt-2">
                            <canvas id="chartDonacionesCausa" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Futuro grafico</h5><small class="text-muted">En desarrollo...</small>
                        </div>
                        <div class="mt-2">
                            <canvas id="chartUsuariosRegistrados" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
</body>

</html>