<?php
require_once "config/roles.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();

    // No cachear esta página
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

if (!isset($_SESSION["user"]) || $_SESSION["tipo_usuario"] !== "admin") {

    exit;
}


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
        href="Public/images/icono2.png"
        type="image/png" />
    <!-- ===== All CSS files ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="Public/css/styles.css" />
    <link rel="stylesheet" href="Public/css/animate.css" />
    <link rel="stylesheet" href="Public/css/ud-styles.css" /> <!-- Llamamos a la librería de iconos -->

</head>

<body id="bodyAdmin">
    <!-- Navbar Bootstrap -->
    <nav class="navbar navbar-expand-lg sticky" id="navbarAdmin">
        <div class="container-fluid m-2">
            <button class="toggle-btn-mobile d-lg-none border-0 me-2" type="button">
                <i class="uil uil-bars"></i>
            </button>
            <a class="navbar-brand" href="#" onclick="history.go(0);">
                <img src="Public/images/logo/logo.png" alt="Logo" id="logo" class="d-inline-block align-text-top">
            </a>

            <div class="collapse navbar-collapse">
                <form class="d-flex justify-content-center flex-grow-1" role="search" style="max-width: 600px; margin: 0 auto;">
                    <input class="form-control me-2 flex-grow-1 " type="search" name="busqueda"
                        id="busqueda" placeholder="Busca en publicaciones, perfiles o intereses..." aria-label="Buscar" style="min-width: 300px;">
                    <button class="btn btn-outline-dark" type="submit"><i class="uil uil-search"></i></button>
                </form>

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
                        <p class="m-1 "> Administrador </p>
                        <img
                            src="Public/images/perfil/perfil2.jpg"
                            class="rounded-circle"
                            height="40"
                            width="40"
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
                            <a class="dropdown-item" href="index.php?action=perfil">
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
                    <a href="" class="sidebar-link">
                        <i class="uil uil-user"></i>
                        <span class="sidebar-text">Perfil</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="" class="sidebar-link">
                        <i class="uil uil-building"></i>
                        <span class="sidebar-text">Fundaciones</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="" class="sidebar-link">
                        <i class="uil uil-credit-card"></i>
                        <span class="sidebar-text">Donaciones</span>
                    </a>
                </li>
                <li class="sidebar-item has-dropdown" id="mascotas">
                    <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#mascota" aria-expanded="false" aria-controls="mascota">
                        <i class="uil uil-heart"></i>
                        <span class="sidebar-text">Mascotas</span>
                    </a>
                    <ul id="mascota" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="" class="sidebar-link">✔ Perros</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="" class="sidebar-link">✔ Gatos</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="" class="sidebar-link">✔ Todos</a>
                        </li>

                    </ul>
                </li>
                <li class="sidebar-item has-dropdown">
                    <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#crud" aria-expanded="false" aria-controls="crud">
                        <i class="uil uil-clipboard-alt"></i>
                        <span class="sidebar-text">Gestiones </span>
                    </a>

                    <ul id="crud" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item has-dropdown">
                            <a href="" class="sidebar-link" data-bs-toggle="collapse" data-bs-target="#crud-2" aria-expanded="false" aria-controls="crud-2">
                                ✔ Modulo Usuarios
                            </a>
                            <ul id="crud-2" class="sidebar-dropdown list-unstyled collapse">
                                <li class="sidebar-item">
                                    <a href="" class="sidebar-link">
                                        - Usuarios
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="" class="sidebar-link">
                                        - Guardianes
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link" id="btn-cargar-fundaciones">
                                        - Fundaciones
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="" class="sidebar-link">
                                        - Administradores
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item collapsed has-dropdown">
                            <a href="" class="sidebar-link " data-bs-toggle="collapse" data-bs-target="#crud-3" aria-expanded="false" aria-controls="crud-3">
                                ✔ Modulo Mascotas
                            </a>
                            <ul id="crud-3" class="sidebar-dropdown list-unstyled collapse">
                                <li class="sidebar-item">
                                    <a href="" class="sidebar-link">
                                        - Mascotas
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link" onclick="cargarCrudTipoMascota()">
                                        - Tipo Mascotas
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="" class="sidebar-link">
                                        - Vacunas
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="sidebar-item has-dropdown">
                            <a href="" class="sidebar-link has-dropdown" data-bs-toggle="collapse" data-bs-target="#crud-4" aria-expanded="false" aria-controls="crud-4">
                                ✔ Modulo Productos
                            </a>
                            <ul id="crud-4" class="sidebar-dropdown list-unstyled collapse">
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link" id="btn-cargar-productos">
                                        - Productos
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item has-dropdown">
                            <a href="" class="sidebar-link has-dropdown" data-bs-toggle="collapse" data-bs-target="#crud-5" aria-expanded="false" aria-controls="crud-5">
                                ✔ Modulo Donaciones
                            </a>
                            <ul id="crud-5" class="sidebar-dropdown list-unstyled collapse">
                                <li class="sidebar-item">
                                    <a href="" class="sidebar-link">
                                        - Donaciones
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item has-dropdown">
                            <a href="" class="sidebar-link has-dropdown" data-bs-toggle="collapse" data-bs-target="#crud-4" aria-expanded="false" aria-controls="crud-4">
                                ✔ Modulo Eventos
                            </a>
                            <ul id="crud-4" class="sidebar-dropdown list-unstyled collapse">
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link" onclick="cargarCrudPublicacion()">
                                        <i class="fas fa-list"></i>- Publicaciones
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link" onclick="cargarDashboard()">
                        <i class="uil uil-dashboard"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="" class="sidebar-link">
                        <i class="uil uil-setting "></i>
                        <span class="sidebar-text">Configuración</span>
                    </a>
                </li>
            </ul>
        </aside>
        <!-- ============================================ MAIN CONTENT ============================================ -->
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

    <!-- SCRIPTS DE JS CRUDS Y RUTAS -->
    <script src="Public/js/main.js"></script>
    <script src="Public/js/crud/crud_producto.js"></script>
    <script src="Public/js/crud/crud_fundacion.js"></script>
    <script src="Public/js/crud/crud_publicacion.js"></script>
    <script src="Public/js/routes/routes.js"></script>

    <script>
        let page = 1;
        let loading = false;
        let finished = false;

        function cargarPublicaciones() {
            if (loading || finished) return;
            loading = true;
            $('#loader').show();

            $.ajax({
                url: 'index.php?action=publicaciones_recientes&accion=recientes', // Ajusta la ruta a tu endpoint
                method: 'GET',
                data: {
                    page: page
                },
                dataType: 'json',
                success: function(res) {
                    if (res && res.length > 0) {
                        res.forEach(pub => {
                            $('#publicaciones-container').append(`
  <div class="card mb-4 shadow-sm border-0 rounded-4 bg-white">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
        <small class="text-primary fw-semibold">NIT Fundación: ${pub.nit_fundacion}</small>
        <h5 class="card-title mb-0 fw-bold">${pub.titulo}</h5>
      </div>
      
      ${pub.imagen ? `
        <div class="text-center mb-3">
          <img src="Public/images/eventos_fundacion/${pub.imagen}" 
            class="img-fluid rounded-3" 
            alt="Imagen publicación" 
            style="max-height:500px; object-fit:cover; width:100%;">
        </div>
      ` : ''}
      
      <p class="card-text">${pub.contenido}</p>
      
      <div class="text-end">
        <small class="text-muted">📅 ${pub.fecha}</small>
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
                complete: function() {
                    loading = false;
                    $('#loader').hide();
                }
            });
        }

        // Cargar publicaciones al inicio
        $(document).ready(function() {
            cargarPublicaciones();

            $(window).on('scroll', function() {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                    cargarPublicaciones();
                }
            });
        });
    </script>

</body>

</html>