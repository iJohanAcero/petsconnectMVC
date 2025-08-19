<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Fundacion\Fundacion;

if (session_status() === PHP_SESSION_NONE) {
    session_start();

    // No cachear esta página
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

$fundacion = new Fundacion();

$id_usuario = $_SESSION['user']['id_usuario'] ?? null;

$fundaciones = $fundacion->getAllFundacionesCarrusel();
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
                <h1 class="display-4 mb-3">Nuestras Fundaciones</h1>
                <p class="lead text-muted">Conoce las organizaciones que están transformando vidas</p>
            </div>
        </div>

        <!-- Loading -->
        <div class="row" id="loading">
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3 text-muted">Cargando fundaciones...</p>
            </div>
        </div>

        <!-- Contenedor de cartas -->
        <div class="row g-4" id="fundacionesContainer" style="display: none;">
            <!-- Las cartas se generarán aquí -->
        </div>

        <!-- Mensaje si no hay fundaciones -->
        <div class="row" id="mensajeVacio" style="display: none;">
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-info-circle fa-3x mb-3 text-info"></i>
                    <h4>No hay fundaciones registradas</h4>
                    <p class="mb-0">Aún no se han registrado fundaciones en el sistema.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalles (opcional) -->
    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesLabel">Detalles de la Fundación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalDetallesBody">
                    <!-- Contenido del modal se carga aquí -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalContacto" tabindex="-1" aria-labelledby="modalContactoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalContactoLabel">
                        <i class="fas fa-envelope me-2"></i>
                        Información de Contacto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalContactoBody">
                    <!-- Contenido del modal se carga aquí -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar perfil de fundación -->
    <div class="modal fade" id="modal-perfil-fundacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-building me-2"></i>
                        Perfil de la Fundación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" id="contenido-perfil-fundacion" style="max-height: 80vh; overflow-y: auto;">
                    <!-- Se carga dinámicamente con JavaScript -->
                </div>
            </div>
        </div>
    </div>