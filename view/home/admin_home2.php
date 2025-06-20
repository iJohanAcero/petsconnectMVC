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

    <link href="https://cdn.lineicons.com/5.0/lineicons.css" rel="stylesheet" />
    <link rel="shortcut icon" href="Public2/images/icono.png" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">


    <link rel="stylesheet" href="Public2/css/style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css"> <!-- Llamamos a la librería de iconos -->

</head>

<body>
    <nav>
        <div class="container">

            <div class="logo">
                <a onClick="history.go(0);">
                    <img id="logo" src="Public2/images/logo.png">
                </a>
            </div>

            <div class="barra-buscador">
                <i class="uil uil-search"></i>
                <input type="search" placeholder="Busca en Public2aciones, perfiles o intereses...">
            </div>

            <div class="crear">
                <a href="index.php?action=logout">
                    <label class="btn btn-primario">Cerrar Sesión</label>
                </a>

            </div>

            <div class="temas">
                <button onclick="cambiarLogo()" class="tema" id="cambio-tema">
                    <i class="uil uil-moon"></i>
                    <i class="uil uil-brightness"></i>
                </button>
            </div>

        </div>
    </nav>

    <!--============================================================MAIN=============================================-->
    <main>
        <div class="container">
            <!--==================================IZQUIERDA================================================-->
            <div class="izquierda">
                <a class="perfil">
                    <div class="foto-perfil">
                        <img src="Public2/images/perfil2.jpg">
                    </div>
                    <div class="hundle">
                        <?php if (isset($_SESSION["user"])): ?>
                            <h4><?php echo htmlspecialchars($_SESSION["user"]["nombre"]); ?></h4>
                        <?php endif; ?>

                        <?php if (isset($_SESSION["user"])): ?>
                            <p class="text-suave">
                                <?php echo htmlspecialchars($_SESSION["user"]["email"]); ?>
                            </p>
                        <?php endif; ?>
                        </p>
                    </div>
                </a>

                <!------------------------------BARRA LATERAL - SIDE BAR-------------------------->
                <div class="sidebar">
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
                                    <img src="Public2/images/perro.JPG">
                                </div>
                                <div class="popup-body">
                                    <b class="text-suave">Perros</b>
                                </div>
                            </div>

                            <div class="popup-item">
                                <div class="foto-perfil">
                                    <img src="Public2/images/gato.jpg">
                                </div>
                                <div class="popup-body">
                                    <b class="text-suave">Gatos</b>
                                </div>
                            </div>
                            <div class="popup-item">
                                <div class="foto-perfil">
                                    <img src="Public2/images/todo-mascotas.jpg">
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
                <label for="crear-Public2acion" class="btn btn-primario">Crear Publicación</label>
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
                            <img src="Public2/images/perfil.jpg">
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
                            <img src="Public2/images/valen.jpg">
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
                            <img src="Public2/images/perfil2.jpg">
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

    <script src="Public2/js/main.js"></script>
    <script src="Public2/js/cruds.js"></script>
</body>

</html>