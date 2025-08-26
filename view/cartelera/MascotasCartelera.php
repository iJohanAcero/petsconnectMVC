<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Mascota\Mascota;

if (session_status() === PHP_SESSION_NONE) {
    session_start();

    // No cachear esta página
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

$mascota = new Mascota();

$id_usuario = $_SESSION['user']['id_usuario'] ?? null;

$mascotas = $mascota->getAllMascotasCarrusel();
?>
<!DOCTYPE html>
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="">
    <div class="container my-5">
        <!-- Encabezado -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="display-4 mb-3">Nuestras Mascotas</h1>
                <p class="lead text-muted">Conoce a los compañeros peludos que buscan un hogar lleno de amor</p>
            </div>
        </div>

        <!-- Loading -->
        <div class="row" id="loading">
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3 text-muted">Cargando mascotas...</p>
            </div>
        </div>

        <!-- Contenedor de cartas -->
        <div class="row g-4" id="mascotasContainer" style="display: none;">
            <!-- Las cartas se generarán aquí -->
        </div>

        <!-- Mensaje si no hay mascotas -->
        <div class="row" id="mensajeVacio" style="display: none;">
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-paw fa-3x mb-3 text-info"></i>
                    <h4>No hay mascotas registradas</h4>
                    <p class="mb-0">Aún no se han registrado mascotas en el sistema.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalles de la mascota -->
    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesLabel">
                        <i class="fas fa-paw me-2"></i>
                        Detalles de la Mascota
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalDetallesBody">
                    <!-- Contenido del modal se carga aquí -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para adopción -->
    <div class="modal fade" id="modalAdopcion" tabindex="-1" aria-labelledby="modalAdopcionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAdopcionLabel">
                        <i class="fas fa-heart me-2"></i>
                        Información de Adopción
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalAdopcionBody">
                    <!-- Contenido del modal se carga aquí -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnSolicitarAdopcion">
                        <i class="fas fa-heart me-2"></i>
                        Solicitar Adopción
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar perfil completo de mascota -->
    <div class="modal fade" id="modal-perfil-mascota" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-dog me-2"></i>
                        Perfil de la Mascota
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" id="contenido-perfil-mascota" style="max-height: 80vh; overflow-y: auto;">
                    <!-- Se carga dinámicamente con JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary2" id="btnIniciarAdopcion">
                        <i class="fas fa-home me-2"></i>
                        Iniciar Proceso de Adopción
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para filtros -->
    <div class="modal fade" id="modalFiltros" tabindex="-1" aria-labelledby="modalFiltrosLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFiltrosLabel">
                        <i class="fas fa-filter me-2"></i>
                        Filtrar Mascotas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="filtroEspecie" class="form-label">Especie</label>
                            <select class="form-select" id="filtroEspecie">
                                <option value="">Todas las especies</option>
                                <option value="perro">Perros</option>
                                <option value="gato">Gatos</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="filtroEdad" class="form-label">Edad</label>
                            <select class="form-select" id="filtroEdad">
                                <option value="">Todas las edades</option>
                                <option value="cachorro">Cachorro/Cría</option>
                                <option value="joven">Joven</option>
                                <option value="adulto">Adulto</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="filtroGenero" class="form-label">Género</label>
                            <select class="form-select" id="filtroGenero">
                                <option value="">Todos</option>
                                <option value="macho">Macho</option>
                                <option value="hembra">Hembra</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" id="btnLimpiarFiltros">Limpiar Filtros</button>
                    <button type="button" class="btn btn-primary" id="btnAplicarFiltros" data-bs-dismiss="modal">Aplicar Filtros</button>
                </div>
            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-4" style="z-index: 1050;">
        <button type="button" class="btn btn-primary btn-lg rounded-circle shadow" data-bs-toggle="modal" data-bs-target="#modalFiltros" title="Filtrar mascotas">
            <i class="fas fa-filter"></i>
        </button>
    </div>
</body>
</html>