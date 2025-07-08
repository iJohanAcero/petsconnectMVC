<?php
require_once 'controller/AuthController.php';
$mensaje = $error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    try {
        (new AuthController())->enviar_recuperacion();
        $mensaje = "Si el correo existe, se ha enviado el enlace de recuperación.";
    } catch (Exception $e) {
        $error = "Error al enviar el enlace de recuperación.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <!--====== Favicon Icon ======-->
    <link
        rel="shortcut icon"
        href="Public/images/icono2.png"
        type="image/png" />


    <!-- ===== All CSS files ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href=Public/css/animate.css" />
    <link rel="stylesheet" href="Public/css/ud-styles.css" />
</head>

<!-- <body style="background-color: f3f4fe; background-image: url('Public/images/login/background.jpg'); background-size:contain;"> -->
<body id="hero">
        <video class="video-desktop" muted autoplay loop>
            <source src="Public/images/login/videobg.mp4" 
            type="video/mp4">
        </video>

        <video class="video-mobile" muted autoplay loop>
            <source src="Public/images/login/videobg3.mp4" 
            type="video/mp4">
        </video>
    <section class="ud-login">
        <div class="container">
            <div class="row">
                <div class="col-lg-12" style="padding-bottom:5rem;">
                    <div class="ud-login-wrapper">
                        <!-- Logo -->
                        <div class="ud-login-logo">
                            <img src="Public/images/icono.png" alt="logo" />
                        </div>

                        <!-- Formulario para recuperar contraseña -->
                        <form class="ud-login-form" action="index.php?page=recuperar_contrasena" method="post">
                            <div class="ud-form-group">
                                <input type="email" id="email" name="email" placeholder="Ingresa tu correo electrónico" required />
                            </div>
                            <div class="ud-form-group">
                                <button type="submit" class="ud-main-btn w-100">Enviar enlace de recuperación</button>
                            </div>
                        </form>
                        <p class="signup-option">
                            ¿Ya tienes un token? <a href="index.php?page=restablecer_contrasena"> Restablecer contraseña </a>
                        </p>
                        <!-- Volver al login -->
                        <form action="index.php" method="get">
                            <div class="ud-form-group">
                                <button type="submit" name="action" value="mostrar_login" class="ud-main-btn w-100 btn-secondary">
                                    Volver al Login
                                </button>
                            </div>
                        </form>

                        <!-- Mensajes del servidor -->
                        <?php if (isset($mensaje)) : ?>
                            <div class="ud-form-group text-success text-center mt-3">
                                <?= $mensaje ?>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($error)) : ?>
                            <div class="ud-form-group text-danger text-center mt-3">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ====== Footer Start ====== -->
    <footer class="ud-footer wow fadeInUp" data-wow-delay=".15s">
        <div class="shape shape-2">
            <img src="Public/images/footer/shape-2.svg" alt="shape" />
        </div>
        <div class="shape shape-3">
            <img src="Public/images/footer/shape-3.svg" alt="shape" />
        </div>
        <div class="ud-footer-widgets">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="ud-widget">
                            <a href="index.php" class="ud-footer-logo">
                                <img src="Public/images/logo/logo-oscuro.png" alt="logo" />
                            </a>
                            <p class="ud-widget-desc">
                                PetsConnect es una plataforma dedicada a conectar animales necesitados con personas dispuestas a brindarles un hogar amoroso. Nuestra misión es facilitar la adopción responsable y promover el bienestar animal en nuestra comunidad.
                            </p>
                            <ul class="ud-widget-socials">
                                <li>
                                    <a href="https://twitter.com/MusharofChy" target="_blank">
                                        <i class="uil uil-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/MusharofChy" target="_blank">
                                        <i class="uil uil-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/MusharofChy" target="_blank">
                                        <i class="uil uil-instagram"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/MusharofChy" target="_blank">
                                        <i class="uil uil-linkedin"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6">
                        <div class="ud-widget">
                            <h5 class="ud-widget-title">Sobre nosotros</h5>
                            <ul class="ud-widget-links">
                                <li>
                                    <a href="index.php #home">Inicio</a>
                                </li>
                                <li>
                                    <a href="index.php #about">Misión</a>
                                </li>
                                <li>
                                    <a href="index.php #pricing">Actividades</a>
                                </li>
                                <li>
                                    <a href="index.php #testimonials">Testimonios</a>
                                </li>
                                <li>
                                    <a href="index.php #team">Ayudas</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6">
                        <div class="ud-widget">
                            <h5 class="ud-widget-title">Información </h5>
                            <ul class="ud-widget-links">
                                <li>
                                    <a href="index.php #about">¿Cómo funciona?</a>
                                </li>
                                <li>
                                    <a href="view/terms_privacy/Politica_de_Privacidad_PetsConnect.pdf" target="_blank">Política de privacidad</a>
                                </li>
                                <li>
                                    <a href="view/terms_privacy/terminos_condiciones.pdf" target="_blank">Términos y Condiciones</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-8 col-sm-10">
                        <div class="ud-widget">
                            <h5 class="ud-widget-title">Colaboradores </h5>
                            <ul class="ud-widget-brands">
                                <li>
                                    <a
                                        href="https://oferta.senasofiaplus.edu.co/sofia-oferta/"
                                        rel="nofollow noopner"
                                        target="_blank">
                                        <img
                                            src="Public/images/footer/brands/sena.png"
                                            alt="SENA" />
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="https://iconscout.com/"
                                        rel="nofollow noopner"
                                        target="_blank">
                                        <img
                                            src="Public/images/footer/brands/iconscout.png"
                                            alt="ecommerce-html" />
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="https://getbootstrap.com/"
                                        rel="nofollow noopner"
                                        target="_blank">
                                        <img
                                            src="Public/images/footer/brands/bootstrap.png"
                                            alt="graygrids" />
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="https://uideck.com/"
                                        rel="nofollow noopner"
                                        target="_blank">
                                        <img
                                            src="Public/images/footer/brands/uideck.svg"
                                            alt="lineicons" />
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ud-footer-bottom">
            <div class="container">
                <div class="row">

                    <div class="col-md-5">
                        <p class="ud-footer-bottom-right">
                            Diseñado y desarrollado por
                            <a href="https://www.linkedin.com/in/johan-acero/" target="no_blank" rel="nofollow" class="autor">Johan Acero |</a>
                            <a href="#" rel="nofollow" class="autor">Pablo Vela |</a>
                            <a href="#" rel="nofollow" class="autor">Katherine Rojas</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="Public/js/bootstrap.bundle.min.js"></script>
    <script src="Public/js/wow.min.js"></script>
</body>

</html>