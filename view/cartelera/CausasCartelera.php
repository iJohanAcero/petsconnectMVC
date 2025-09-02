<?php
require_once __DIR__ . '/../../vendor/autoload.php';

// Usar vlucas/phpdotenv si lo tienes instalado
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$stripePublicKey = $_ENV['STRIPE_PUBLIC_KEY'] ?? '';

use App\Model\Causa\Causa;
use App\Model\Fundacion\Fundacion;
use App\Config\Roles;

if (session_status() === PHP_SESSION_NONE) {
    session_start();

    // No cachear esta página
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

$causa = new Causa();

$Fundacion = new Fundacion();

$nit_fundacion = null;

$id_usuario = $_SESSION['user']['id_usuario'] ?? null;
$esAdmin = $id_usuario && Roles::esAdmin($id_usuario);
$esFundacion = $id_usuario && Roles::esFundacion($id_usuario);
$nit_sesion = $_SESSION['user']['nit_fundacion'] ?? null;

if (isset($_SESSION["user"]["id_usuario"])) {
    $nit_fundacion = Fundacion::obtenerNitPorUsuario($_SESSION["user"]["id_usuario"]);
}

$causas = $causa->getAllCausasCarrusel();
?>
<!DOCTYPE html>

<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        window.STRIPE_PUBLIC_KEY = "<?php echo $stripePublicKey; ?>";
    </script>
</head>

<body>
    <div class="container my-5">
        <!-- Encabezado -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="display-4 mb-3">Causas</h1>
                <p class="lead text-muted">Conoce las causas que apoyan a nuestros compañeros peludos</p>
            </div>
        </div>

        <!-- Loading -->
        <div class="row" id="loadingCausa">
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3 text-muted">Cargando causas...</p>
            </div>
        </div>

        <!-- Contenedor de cartas -->
        <div class="row g-4" id="causasContainer" style="display: none;">
            <!-- Las cartas se generarán aquí -->
        </div>

        <!-- Mensaje si no hay causas -->
        <div class="row" id="mensajeVacioCausa" style="display: none;">
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-paw fa-3x mb-3 text-info"></i>
                    <h4>No hay causas registradas</h4>
                    <p class="mb-0">Aún no se han registrado causas en el sistema.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar detalle completo de causa -->
<div class="modal fade" id="modal-causa-detalle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header bg-gradient position-relative" style="background-color:hsl(252, 30%, 17%) ; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-heart me-2"></i>
                    Detalle de la Causa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="contenido-causa" style="max-height: 70vh; overflow-y: auto;">
                <!-- Se carga dinámicamente con JavaScript -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-3 text-muted">Cargando información...</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>