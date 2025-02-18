<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link rel="stylesheet" href="../Public/css/style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css"> <!-- Llamamos a la librería de iconos --> 

</head>
<body>
    <nav>
        <div class="container">

            <h1 class="nombre">PetsConnect</h1>

            <div class="barra-buscador">
                <i class="uil uil-search"></i>
                <input type="search" placeholder="Busca en publicaciones, perfiles o intereses...">
            </div>

            
            <a  class="crear" href="../index.php">
                <label class="btn btn-primario" >Cerrar Sesión</label>
            </a>
            

            <div class="temas">
                <button class="tema" id="cambio-tema">
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
                    <img src="../Public/images/perfil2.jpg">
                </div>
                <div class="hundle">
                    <h4> Johan Acero</h4>
                    <p class="text-suave">
                        @Acero24
                    </p>
                </div>
            </a>
                    
<!------------------------------BARRA LATERAL - SIDE BAR-------------------------->
            <div class="sidebar">
                <a class="menu-item activo">
                    <span><i class="uil uil-house-user"></i></span> <h3>Inicio</h3>
                </a>
                
                <a class="menu-item">
                    <span><i class="uil uil-user-circle"></i></span> <h3>Perfil</h3>
                </a>

                <a class="menu-item" id="guardianes-box">
                    <span><i class="uil uil-smile-squint-wink"></i></span> <h3>Guardianes</h3>
                </a>

                <a class="menu-item">
                    <span><i class="uil uil-shop"></i></span> <h3>Fundaciones</h3>
                </a>

                <a class="menu-item">
                    <span><i class="uil uil-credit-card"></i></span> <h3>Donaciones</h3>
                </a>

                <a class="menu-item" id="popup-mascotas">
                    <span><i class="uil uil-heartbeat"></i></span> <h3>Mascotas</h3>

<!---------------------------------------------------POPUP DE MASCOTAS --------------------------------------------->
                    <div class="mascotas-popup">
                        <div class="popup-item">
                            <div class="foto-perfil">
                                <img src="../Public/images/perro.JPG">
                            </div>
                            <div class="popup-body">
                                <b class="text-suave">Perros</b>
                            </div>
                        </div>

                        <div class="popup-item">
                            <div class="foto-perfil">
                                <img src="../Public/images/gato.jpg">
                            </div>
                            <div class="popup-body">
                                <b class="text-suave">Gatos</b>
                            </div>
                        </div>
                        <div class="popup-item">
                            <div class="foto-perfil">
                                <img src="../Public/images/todo-mascotas.jpg">
                            </div>
                            <div class="popup-body">
                                <b class="text-suave">Todos</b>
                            </div>
                        </div>
                    </div>
                    <!---------------------FIN DEL POPUP DE MASCOTAS----------------------------->
                </a>

                <a class="menu-item" href="crud_admin.php" >
                    <span><i class="uil uil-constructor"></i></span> <h3>CRUD Admin</h3>
                </a>

                <a class="menu-item">
                    <span><i class="uil uil-setting"></i></span> <h3>Configuración</h3>
                </a>
            </div>
            <!------------------------FIN DEL SIDEBAR---------------------->
    <label for="crear-publicacion" class="btn btn-primario">Crear publicación</label>
        </div>
    <!------------------------------FIN DEL LADO IZQUIERDO-------------------------->
    
<!--====================================================MEDIO=======================================================-->
                <div class="medio">
                    <div class="scroll">
                    <!------------------------ SCROLL HORIZONTAL DE MASCOTAS-->
                    <div class="recomendados">
                        
                        <div class="recomendado-individual">
                            <div class="foto-perfil">
                                <img src="../Public/images/perfil2.jpg">
                            </div>
                            <p class="mascota">Apolo</p>
                        </div>

                        <div class="recomendado-individual">
                            <div class="foto-perfil">
                                <img src="../Public/images/perfil2.jpg">
                            </div>
                            <p class="mascota">Benji</p>
                        </div>

                        <div class="recomendado-individual">
                            <div class="foto-perfil">
                                <img src="../Public/images/perfil2.jpg">
                            </div>
                            <p class="mascota">Hanna</p>
                        </div>

                        <div class="recomendado-individual">
                            <div class="foto-perfil">
                                <img src="../Public/images/perfil2.jpg">
                            </div>
                            <p class="mascota">Lukas</p>
                        </div>

                        <div class="recomendado-individual">
                            <div class="foto-perfil">
                                <img src="../Public/images/perfil2.jpg">
                            </div>
                            <p class="mascota">Toby</p>
                        </div>

                        <div class="recomendado-individual">
                            <div class="foto-perfil">
                                <img src="../Public/images/perfil2.jpg">
                            </div>
                            <p class="mascota">Figaro</p>
                        </div>

                        <div class="recomendado-individual">
                            <div class="foto-perfil">
                                <img src="../Public/images/perfil2.jpg">
                            </div>
                            <p class="mascota">Tiger</p>
                        </div>

                        <div class="recomendado-individual">
                            <div class="foto-perfil">
                                <img src="../Public/images/perfil2.jpg">
                            </div>
                            <p class="mascota">Romeo</p>
                        </div>

                        <div class="recomendado-individual">
                            <div class="foto-perfil">
                                <img src="../Public/images/perfil2.jpg">
                            </div>
                            <p class="mascota">Beny</p>
                        </div>
                    </div>
                </div>
                    <!--------------------FIN DEL SCROLL RECOMENDADO------------>

                    <!-----INPUT DE CREAR PUBLICACIÓN...IMPORTANTE!!(SOLO PARA EL ROL DE FUNDACIÓN)-->
                    <form class="crear-post">
                        <div class="foto-perfil">
                            <img src="../Public/images/perfil2.jpg">
                        </div>
                        <input type="text" placeholder="Ayuda a una mascota o promueve un evento aquí..." id="crear-post">
                        <input type="submit" value="Publicar" class="btn btn-primario">
                    </form>
                    <!---------------------------------FEED DE PUBLICACIONES----------------------------->
                    <div class="feed">
                        <!----------------FEED INDIVIDUAL NUMERO 01------------------>
                        <div class="feed-individual">
                            <div class="head">
                                <div class="usuario">
                                    <div class="foto-perfil">
                                        <img src="../Public/images/fundacion.png" alt="">
                                    </div>
                                    <div class="informacion">
                                        <h3>Fundacion TEPA</h3>
                                        <small>Bogota, Hace 30 Minutos</small>
                                    </div>
                                </div>
                                <span class="editar">
                                    <i class="uil uil-ellipsis-h"></i>
                                </span>
                            </div>
                            <div class="foto">
                                <img src="../Public/images/feed1.jpeg">
                            </div>
                            <div class="boton-accion">
                                <div class="botones-interaccion">
                                    <span><i class="uil uil-thumbs-up"></i></span>
                                    <span><i class="uil uil-share"></i></span>
                                </div>
                            </div>
                            <div class="likes">
                                <span><img src="../Public/images/perfil.jpg"></span>
                                <span><img src="../Public/images/perfil2.jpg"></span>
                                <span><img src="../Public/images/perfil.jpg"></span>
                                <p>Han reaccionado <b>Valentina</b> y <b>40 otros</b></p>
                            </div>
                            <div class="descripcion"></div>
                            <p>Jornada de adopcion, no se la pueden perder!!!</p>
                        </div>
                        <!----------------FIN DEL FEED 01------------------>
                        <div class="feed">
                            <!----------------FEED INDIVIDUAL NUMERO 02------------------>
                            <div class="feed-individual">
                                <div class="head">
                                    <div class="usuario">
                                        <div class="foto-perfil">
                                            <img src="../Public/images/fundacion2.png" alt="">
                                        </div>
                                        <div class="informacion">
                                            <h3>Fundacion Bogotá</h3>
                                            <small>Bogota, Hace 50 Minutos</small>
                                        </div>
                                    </div>
                                    <span class="editar">
                                        <i class="uil uil-ellipsis-h"></i>
                                    </span>
                                </div>
                                <div class="foto">
                                    <img src="../Public/images/feed2.jpeg">
                                </div>
                                <div class="boton-accion">
                                    <div class="botones-interaccion">
                                        <span><i class="uil uil-thumbs-up"></i></span>
                                        <span><i class="uil uil-share"></i></span>
                                    </div>
                                </div>
                                <div class="likes">
                                    <span><img src="../Public/images/perfil.jpg"></span>
                                    <span><img src="../Public/images/perfil2.jpg"></span>
                                    <span><img src="../Public/images/perfil.jpg"></span>
                                    <p>Han reaccionado <b>Valentina</b> y <b>10 otros</b></p>
                                </div>
                                <div class="descripcion"></div>
                                <p>Jornada de esterilización, de lo mejor!!!</p>
                            </div>
                            <!----------------FIN DEL FEED 02------------------>
                        </div>
                        <!--==============================================FIN DEL FEED DE PUBLICACIONES===========================================-->
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
                            <input  type="search" placeholder="Buscar guardianes" id="guardian-buscador">
                        </div>
                        <!------------------------ CATEGORIA DE GUARDIANES --------------------->
                        <div class="categoria">
                            <a class="enlinea">En Linea</a>
                            <a class="offline">Offline</a>
                        </div>
                        <!------------------------ GUARDIAN EN LINEA--------------------->
                        <div class="guardian-enlinea">
                            <div class="foto-perfil">
                                <img src="../Public/images/perfil.jpg">
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
                                <img src="../Public/images/valen.jpg">
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
                                    <img src="../Public/images/perfil2.jpg" >
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

<script src="../Public/js/main.js"></script>
</body>
</html>