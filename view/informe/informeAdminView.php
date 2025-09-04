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
                <i class="uil uil-bag"></i> Reportes de Administrador
            </h1>
        </div>

        <!-- Mascotas adultas en adopción -->
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header text-white py-3" style="background-color: #a3bced;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-heart-fill me-2"></i>
                    <h5 class="mb-0 text-dark">Donaciones por fundación</h5>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tablaDonacionesFundacion" class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Total Donaciones</th>
                                <th>Total Recaudado</th>
                                <th>Promedio Donación</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Especies mas adoptadas en la plataforma  -->
    <div class="container py-4">
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header text-white py-3" style="background-color: hsl(252, 30%, 17%);">
                <div class="d-flex align-items-center">
                    <i class="bi bi-heart-fill me-2"></i>
                    <h5 class="mb-0 text-white">Especies más adoptadas</h5>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tablaAdopcionesEspecie" class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Especie</th>
                                <th>Adopciones Completadas</th>
                                <th>Adopciones Pendientes</th>
                                <th>En Adopción</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--  -->
    <div class="container py-4">
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header text-white py-3" style="background-color: #fdaac4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-heart-fill me-2"></i>
                    <h5 class="mb-0 text-dark">Publicaciones por fundación</h5>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tablaPublicacionesFundacion" class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Fundación</th>
                                <th>Total Publicaciones</th>
                                <th>Última Publicación</th>
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