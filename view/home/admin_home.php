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
    <nav class="navbar navbar-expand-lg sticky" id="navbarAdmin">
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
                        <p class="m-1"> Administrador </p>
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
    <div class="wrapper justify-content-between align-items-center">
        <aside id="sidebar">
            <div class="d-flex justify-content-between p-4">
                <div class="sidebar-logo">
                    <h4 class="text-muted">Hola <?php echo htmlspecialchars($_SESSION["user"]["nombre"]); ?>...</h4>
                    <p>
                        <span class="text-muted">¡Explora las opciones!</span>
                    </p>
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
                <li class="sidebar-item has-dropdown">
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
                    <a href="" class="sidebar-link" data-bs-toggle="collapse" data-bs-target="#crud" aria-expanded="false" aria-controls="crud">
                        <i class="uil uil-clipboard-alt"></i>
                        <span class="sidebar-text">Administrador de Cruds</span>
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
                                    <a href="" class="sidebar-link">
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
                                    <a href="" class="sidebar-link">
                                        - Tipo Mascotas
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="" class="sidebar-link">
                                        - Vacunas
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="" class="sidebar-link">
                                        XXXXXX
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
                                    <a href="" class="sidebar-link">
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
                                <li class="sidebar-item">
                                    <a href="" class="sidebar-link">
                                        XXXXXX
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="" class="sidebar-link">
                                        XXXXXX
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="" class="sidebar-link">
                        <i class="uil uil-setting"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="" class="sidebar-link">
                        <i class="uil uil-dashboard"></i>
                        <span class="sidebar-text">Configuración</span>
                    </a>
                </li>
            </ul>
        </aside>
        <div class="main">

        </div>
    </div>
    <!--==============================================CONFIGURACION DE FONDO===========================================-->
    <script src="Public/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="Public/js/cruds.js"></script>
</body>

</html>