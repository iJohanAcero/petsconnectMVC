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

    <link
        rel="shortcut icon"
        href="Public/images/icono2.png"
        type="image/png" />
    <!-- ===== All CSS files ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="Public/css/styles.css" />
    <link rel="stylesheet" href="Public/css/animate.css" />
    <link rel="stylesheet" href="Public/css/ud-styles.css" /> <!-- Llamamos a la librería de iconos -->

</head>

<body id="bodyAdmin">
    <!-- Navbar Bootstrap -->
    <nav class="navbar navbar-expand-lg" id="navbarAdmin">
        <div class="container-fluid m-2">
            <a class="navbar-brand" href="#" onclick="history.go(0);">
                <img src="Public/images/logo/logo.png" alt="Logo" id="logo" class="d-inline-block align-text-top">
            </a>

            <div class="collapse navbar-collapse">
                <form class="d-flex justify-content-center flex-grow-1" role="search" style="max-width: 600px; margin: 0 auto;">
                    <input class="form-control me-2 flex-grow-1 " type="search" placeholder="Busca en publicaciones, perfiles o intereses..." aria-label="Buscar" style="min-width: 300px;">
                    <button class="btn btn-outline-dark" type="submit"><i class="uil uil-search"></i></button>
                </form>

                <div class="d-flex align-items-center">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <!-- <li class="nav-item">
                            <a class="nav-link btn " href="index.php?action=logout" id="logout-btn">Cerrar Sesión</a>
                        </li> -->
                        <!-- <li class="nav-item">
                            <button onclick="cambiarLogo()" class="btn btn-link nav-link " id="cambio-tema" title="Cambiar tema">
                                <i class="uil uil-moon"></i>
                                <i class="uil uil-brightness"></i>
                            </button>
                        </li> -->
                    </ul>
                </div>
                <div class="dropdown">
                    <a
                        class="d-flex align-items-center"
                        href="#"
                        id="navbarDropdownMenuAvatar"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        style="text-decoration: none;">
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
                            <p class="dropdown-item " href="index.php?action=perfil">
                                <i class="uil uil-keyhole-square"></i> Administrador
                            </p>
                        </li>
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
    <main id="mainAdmin">
        <div>
            <!--==================================IZQUIERDA================================================-->


            <!------------------------------BARRA LATERAL - SIDE BAR-------------------------->
            <div class="sidebar collapse show" id="sidebarAdmin">
                <a class="menu-item">
                    <span><i class="uil uil-house-user"></i></span>
                    <h3>Inicio</h3>
                </a>

                <a class="menu-item">
                    <span><i class="uil uil-user-circle"></i></span>
                    <h3>Perfil</h3>
                </a>

                <a class="menu-item" id="guardianes-box">
                    <span><i class="uil uil-smile-squint-wink"></i></span>
                    <h3>Guardianes</h3>
                </a>

                <a class="menu-item">
                    <span><i class="uil uil-shop"></i></span>
                    <h3>Fundaciones</h3>
                </a>

                <a class="menu-item">
                    <span><i class="uil uil-credit-card"></i></span>
                    <h3>Donaciones</h3>
                </a>

                <a class="menu-item" id="popup-mascotas">
                    <span><i class="uil uil-heartbeat"></i></span>
                    <h3>Mascotas</h3>

                    <!---------------------------------------------------POPUP DE MASCOTAS --------------------------------------------->
                    <div class="mascotas-popup">
                        <div class="popup-item">
                            <div class="foto-perfil">
                                <img src="Public/images/sidebar/perro.JPG">
                            </div>
                            <div class="popup-body">
                                <b class="text-suave">Perros</b>
                            </div>
                        </div>

                        <div class="popup-item">
                            <div class="foto-perfil">
                                <img src="Public/images/sidebar/gato.jpg">
                            </div>
                            <div class="popup-body">
                                <b class="text-suave">Gatos</b>
                            </div>
                        </div>
                        <div class="popup-item">
                            <div class="foto-perfil">
                                <img src="Public/images/sidebar/todo-mascotas.jpg">
                            </div>
                            <div class="popup-body">
                                <b class="text-suave">Todos</b>
                            </div>
                        </div>
                    </div>
                    <!---------------------FIN DEL POPUP DE MASCOTAS----------------------------->
                </a>

                <!---------------------CRUDS ADMIN DISPONIBLES ----------------------------->
                <a class="menu-item" onclick="cargarCruds()">
                    <span><i class="uil uil-constructor"></i></span>
                    <h3>CRUD Admin</h3>
                </a>

                <a class="menu-item">
                    <span><i class="uil uil-setting"></i></span>
                    <h3>Configuración</h3>
                </a>
            </div>
            <!------------------------FIN DEL SIDEBAR---------------------->
            <label for="crear-publicacion" class="btn btn-primario">Crear publicación</label>
        </div>
        <!------------------------------FIN DEL LADO IZQUIERDO-------------------------->

        <!--====================================================MEDIO=======================================================-->
        <div class="medio">
            <div id="cruds">
                <!-- AQUI SE CARGARAN LAS CRUDS DISPONIBLES POR EL FETCH EN JAVASCRIPT -->
            </div>
        </div>
        <!--==============================================DERECHA===========================================-->
        <div class="derecha">
            <!------------------------ OTROS GUARDIANES------------------------>
            <div class="guardianes">
                <div class="head">
                    <h4>Otros guardianes</h4><i class="uil uil-users-alt"></i>
                </div>
                <!------------------------ BARRA DE BUSCADOR --------------------->
                <div class="barra-buscador">
                    <i class="uil uil-search"></i>
                    <input type="search" placeholder="Buscar guardianes" id="guardian-buscador">
                </div>
                <!------------------------ CATEGORIA DE GUARDIANES --------------------->
                <div class="categoria">
                    <a class="enlinea">En Linea</a>
                    <a class="offline">Offline</a>
                </div>
                <!------------------------ GUARDIAN EN LINEA--------------------->
                <div class="guardian-enlinea">
                    <div class="foto-perfil">
                        <img src="Public/images/perfil.jpg">
                        <div class="enlinea"></div>
                    </div>
                    <div class="guardian-body">
                        <h5>Juank Pera</h5>
                        <p class="text-suave">Me encantan los gatos uwu</p>
                        <div class="accion">
                            <button class="btn btn-primario">
                                Ver perfil
                            </button>
                        </div>
                    </div>
                </div>

                <div class="guardian-enlinea">
                    <div class="foto-perfil">
                        <img src="Public/images/valen.jpg">
                        <div class="enlinea"></div>
                    </div>
                    <div class="guardian-body">
                        <h5>Valentina Urrego</h5>
                        <p class="text-suave">Me encantan los michis :3</p>
                        <div class="accion">
                            <button class="btn btn-primario">
                                Ver perfil
                            </button>
                        </div>
                    </div>
                </div>
                <!------------------------ GUARDIAN OFFLINE--------------------->
                <div class="guardian-offline">
                    <div class="foto-perfil">
                        <img src="Public/images/perfil2.jpg">
                        <div class="offline"></div>
                    </div>
                    <div>
                        <h5>Manuel Moncada</h5>
                        <p class="text-suave">
                            Activo hace 2 dias
                        </p>
                        <div class="accion">
                            <button class="btn btn-primario">
                                Ver perfil
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!--==============================================FIN DE LA DERECHA===========================================-->
    </main>
    <!--==============================================CONFIGURACION DE FONDO===========================================-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="Public/js/main.js"></script>
    <script src="Public/js/cruds.js"></script>
</body>

</html>