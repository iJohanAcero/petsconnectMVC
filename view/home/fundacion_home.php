<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Config\Config;
use App\Model\Perfil\Perfil;
use App\Model\estadistica\estadistica;

if (session_status() === PHP_SESSION_NONE) {
    session_start();

    // No cachear esta página
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

if (!isset($_SESSION["user"]) || $_SESSION["tipo_usuario"] !== "fundacion") {

    exit;
}

$perfil = new Perfil();
$id = $_SESSION["user"]["id_usuario"];
$perfil = $perfil->getPerfilPorUsuario($id);

$estadisticas = new estadistica();
$stats = $estadisticas->obtenerEstadisticasNavbar();


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetsConnect</title>

    <link href="https://cdn.datatables.net/v/bs5/dt-2.3.2/af-2.7.0/b-3.2.3/b-html5-3.2.3/r-3.0.4/sc-2.4.3/datatables.min.css" rel="stylesheet" integrity="sha384-8tQlkR8djyJUdfrhc0Kd04kh88LIdMNOTD/a8r6mZUTFujZwzUXutJ7xHyQSGer5" crossorigin="anonymous">

    <link
        rel="shortcut icon"
        href="<?= Config::get('IMG_URL') ?>/icono2.png"
        type="image/png" />
    <!-- ===== All CSS files ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="<?= Config::get('CSS_URL') ?>/styles.css" />
    <link rel="stylesheet" href="<?= Config::get('CSS_URL') ?>/animate.css" />
    <link rel="stylesheet" href="<?= Config::get('CSS_URL') ?>/ud-styles.css" /> <!-- Llamamos a la librería de iconos -->

</head>

<body id="bodyAdmin">
    <!-- Navbar Bootstrap -->
    <nav class="navbar navbar-expand-lg sticky" id="navbarAdmin">
        <div class="container-fluid m-2">
            <button class="toggle-btn-mobile d-lg-none border-0 me-2" type="button">
                <i class="uil uil-bars"></i>
            </button>
            <a class="navbar-brand" href="#" onclick="history.go(0);">
                <img src="<?= Config::get('IMG_URL') ?>/logo/logo.png" alt="Logo" id="logo" class="d-inline-block align-text-top">
            </a>

            <div class="collapse navbar-collapse">
                <div class="d-flex justify-content-center flex-grow-1 align-items-center">

                    <!-- Solo mostrar las 2-3 estadísticas más importantes -->
                    <div class="d-flex align-items-center bg-light rounded-pill px-3 py-1 me-3">
                        <div class="bg-success rounded-circle me-2 pulse" style="width: 8px; height: 8px;"></div>
                        <small class="text-muted">
                            <strong><?php echo $stats['mascotas_disponibles']; ?></strong> mascotas disponibles
                        </small>
                    </div>

                    <?php if ($stats['solicitudes_tramite'] > 0): ?>
                        <div class="d-flex align-items-center bg-light rounded-pill px-3 py-1 me-3">
                            <div class="bg-warning rounded-circle me-2 pulse" style="width: 8px; height: 8px;"></div>
                            <small class="text-muted">
                                <strong><?php echo $stats['solicitudes_tramite']; ?></strong> solicitudes pendientes
                            </small>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex align-items-center bg-light rounded-pill px-3 py-1">
                        <div class="bg-info rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                        <small class="text-muted">
                            <strong><?php echo $stats['adopciones_exitosas']; ?></strong> adopciones exitosas
                        </small>
                    </div>

                </div>

                <div class="d-flex align-items-center">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    </ul>
                </div>
                <div class="dropdown">

                    <a
                        class="d-flex align-items-center font-weight-bold"
                        href="#"
                        id="navbarDropdownMenuAvatar"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        style="text-decoration: none;">
                        <p class="m-1 "> Fundación </p>
                        <!-- Foto de Perfil -->
                        <?php
                        $nombreImagen = !empty($perfil['imagen']) ? $perfil['imagen'] : 'default.png';

                        // Si es una URL completa de Cloudinary, agregar transformaciones
                        if (strpos($nombreImagen, 'res.cloudinary.com') !== false) {
                            // Insertar transformaciones después de '/upload/'
                            $rutaImagen = str_replace('/upload/', '/upload/w_40,h_40,c_fill,g_face/', $nombreImagen);
                        } else {
                            // Si no es URL de Cloudinary, usar como está
                            $rutaImagen = htmlspecialchars($nombreImagen);
                        }
                        ?>
                        <img
                            src="<?= $rutaImagen ?>"
                            class="rounded-circle"
                            alt="Foto de perfil"
                            loading="lazy" />
                    </a>
                    <ul
                        class="dropdown-menu dropdown-menu-end"
                        aria-labelledby="navbarDropdownMenuAvatar">

                        <li>
                            <?php if (isset($_SESSION["user"])): ?>
                                <p class=" user-select-all dropdown-item">
                                    <?php
                                    echo htmlspecialchars($_SESSION["user"]["nombre"] . ' ' . $_SESSION["user"]["apellido"]);
                                    ?>
                                </p>
                            <?php endif; ?>
                        </li>
                        <li>
                            <?php if (isset($_SESSION["user"])): ?>
                                <p class=" user-select-all dropdown-item">
                                    <?php echo htmlspecialchars($_SESSION["user"]["email"]); ?>
                                </p>
                            <?php endif; ?>
                        </li>
                        <li>
                            <a class="dropdown-item btn-cargar-perfilFundacion" href="#">
                                <i class="uil uil-user"></i> Perfil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="bg-body rounded text-muted dropdown-item" href="index.php?action=logout">
                                <i class="uil uil-signout"></i> Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- Fin Navbar Bootstrap -->

    <!--============================================================MAIN=============================================-->
    <div class="wrapper align-items-center">
        <aside id="sidebar">
            <div class="d-flex justify-content-between" style="padding: 24px 24px 0 24px;" id="menu_toggle">
                <div class="sidebar-logo">
                    <h4 class="text-muted mx-auto">Menú<h4>

                </div>
                <button class="toggle-btn border-0" type="button">
                    <i id="icon" class="uil uil-angle-double-right"></i>
                </button>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="" class="sidebar-link">
                        <i class="uil uil-home"></i>
                        <span class="sidebar-text">Inicio</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link btn-cargar-perfilFundacion">
                        <i class="uil uil-user"></i>
                        <span class="sidebar-text">Perfil</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" id="btn-cargar-dashboardFundacion">
                        <i class="uil uil-dashboard"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" id="btn-cargar-informe">
                        <i class="uil uil-info-circle"></i>
                        <span class="sidebar-text">Informes</span>
                    </a>
                </li>
                <li class="sidebar-item has-dropdown">
                    <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#crud" aria-expanded="false" aria-controls="crud">
                        <i class="uil uil-clipboard-alt"></i>
                        <span class="sidebar-text">Gestiones </span>
                    </a>
                    <ul id="crud" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" id="btn-cargar-publicacion">✔ Mis publicaciones</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" id="btn-cargar-causa">✔ Mis Causas</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" id="btn-cargar-mascota">✔ Mis Mascotas</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" id="btn-cargar-adopcion">✔ Mis Adopciones</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" id="btn-cargar-donaciones">✔ Mis Donaciones</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link btn-cargar-cartelFundacion">
                        <i class="uil uil-building"></i>
                        <span class="sidebar-text">Fundaciones</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link btn-cargar-cartelCausa">
                        <i class="uil uil-credit-card"></i>
                        <span class="sidebar-text">Donaciones</span>
                    </a>
                </li>
                <li class="sidebar-item has-dropdown btn-cargar-cartelMascota">
                    <a href="#" class="sidebar-link">
                        <i class="uil uil-heart"></i>
                        <span class="sidebar-text">Mascotas</span>
                    </a>
                </li>
                <!-- <li class="sidebar-item">
                    <a href="" class="sidebar-link">
                        <i class="uil uil-setting "></i>
                        <span class="sidebar-text">Configuración</span>
                    </a>
                </li> -->
            </ul>
        </aside>
        <!--============================================ MAIN =============================================-->
        <div class="main" id="main-content">
            <div id="publicaciones-container"></div>
            <div id="loader" class="text-center my-3" style="display:none;">
                <div class="spinner-border text-primary"></div>
            </div>
        </div>
    </div>
    <!--==============================================CONFIGURACION DE FONDO===========================================-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.3.2/af-2.7.0/b-3.2.3/b-html5-3.2.3/r-3.0.4/sc-2.4.3/datatables.min.js" integrity="sha384-4VpbDpy9RZDSYGLIgJCxbBN42Ze5hcM/B+OOSuW3hSAukOTfsuar7+79mYTohU6M" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <!-- SCRIPTS DE JS CRUDS Y RUTAS -->
    <script src="<?= Config::get('JS_URL') ?>/main.js"></script>
    <script src="<?= Config::get('JS_URL') ?>/config.js"></script>


    <!-- CRUDS DEL APLICATIVO -->
    <script src="<?= Config::get('JS_URL') ?>/crud/crud_donacion.js"></script>
    <script src="<?= Config::get('JS_URL') ?>/crud/crud_causa.js"></script>
    <script src="<?= Config::get('JS_URL') ?>/crud/crud_fundacion.js"></script>
    <script src="<?= Config::get('JS_URL') ?>/crud/crud_mascota.js"></script>
    <script src="<?= Config::get('JS_URL') ?>/crud/crud_publicacion.js"></script>
    <script src="<?= Config::get('JS_URL') ?>/crud/crud_proceso.js"></script>

    <!-- PERFIL DEL ROL -->
    <script src="<?= Config::get('JS_URL') ?>/routes/perfilFundacion.js"></script>

    <!-- CARTELERAS -->
    <script src="<?= Config::get('JS_URL') ?>/routes/carteleraFundacion.js"></script>
    <script src="<?= Config::get('JS_URL') ?>/routes/carteleraMascotas.js"></script>
    <script src="<?= Config::get('JS_URL') ?>/routes/carteleraCausa.js"></script>

    <!-- SCRIPT DE LOS INFORMES - DASHBOARD -->
    <script src="<?= Config::get('JS_URL') ?>/routes/informe.js"></script>
    <script src="<?= Config::get('JS_URL') ?>/routes/dashboardFundacion.js"></script>

    <script>
        let page = 1;
let loading = false;
let finished = false;

function cargarPublicaciones() {
    if (loading || finished) return;
    loading = true;
    $('#loader').show();
    $.ajax({
        url: '/petsconnectmvc/index.php?action=recientes&page=' + page,
        method: 'GET',
        dataType: 'json',
        success: function(res) {
            if (Array.isArray(res) && res.length > 0) {
                res.forEach(pub => {
                    $('#publicaciones-container').append(`
                        <div class="row justify-content-center mb-5">
                            <div class="col-12 col-md-10 col-lg-8">
                                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                                    
                                    <!-- Imagen principal -->
                                    ${pub.imagen ? `
                                        <div class="position-relative bg-light" style="min-height: 300px;">
                                            <img src="${pub.imagen}"
                                                 class="w-100 h-100"
                                                 style="object-fit: contain; height: 300px;"
                                                 alt="Imagen publicación">
                                        </div>
                                    ` : ''}
                                    
                                    <!-- Contenido de la tarjeta -->
                                    <div class="card-body p-0">
                                        
                                        <!-- Header con fundación -->
                                        <div class="border-bottom p-4">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <img src="${pub.imagen_fundacion}" 
                                                         class="rounded-circle border" 
                                                         style="width: 50px; height: 50px; object-fit: cover;" 
                                                         alt="Imagen fundación">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 fw-bold text-primary">${pub.nombre_fundacion}</h6>
                                                    <small class="text-muted">Fundación</small>
                                                </div>
                                                <div class="text-end">
                                                    <small class="text-muted d-flex align-items-center">
                                                        <i class="far fa-clock me-2"></i>
                                                        ${pub.fecha}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Contenido principal -->
                                        <div class="p-4">
                                            <h4 class="fw-bold text-dark mb-3">${pub.titulo}</h4>
                                            <p class="text-secondary lh-lg mb-0">${pub.contenido}</p>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                });
                page++;
            } else {
                finished = true;
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar publicaciones:', status, error);
            console.warn('Detalles:', xhr.responseText);
        },
        complete: function() {
            loading = false;
            $('#loader').hide();
        }
    });
}

// Inicializar carga y scroll infinito
$(document).ready(function() {
    cargarPublicaciones();
    $(window).on('scroll', function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 150) {
            cargarPublicaciones();
        }
    });
});
    </script>

</body>

</html>