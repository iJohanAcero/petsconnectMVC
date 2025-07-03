<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link
        rel="shortcut icon"
        href="Public/images/icono2.png"
        type="image/png" />


    <!-- ===== All CSS files ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="Public/css/animate.css" />
    <link rel="stylesheet" href="Public/css/ud-styles.css" />

    <title>PetsConnect | inicio sesión</title>
</head>

<body style="background-color: f3f4fe; background-image: url('Public/images/login/background.jpg'); background-size:contain;">
    <!-- ====== Header Section Start ====== -->
    <header class="ud-header" style="padding-top: 0.5rem; position: sticky; background-color: hsl(252, 30%, 10%);">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg" id="header">
                        <a class="navbar-brand" href="index.php">
                            <img src="Public/images/logo/logo-oscuro.png" alt="Logo" />
                        </a>

                        <div class="navbar-collapse">
                            <ul id="nav" class="navbar-nav mx-auto">
                            </ul>
                        </div>

                        <div class="navbar-btn d-none d-sm-inline-block ud-hero-buttons">
                            <a href="index.php" class="ud-main-btn ud-login-btn">
                                Volver al inicio
                            </a>
                            <a class="ud-main-btn ud-white-btn" href="index.php?page=registro">
                                Registrarse
                            </a>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ====== Login Start ====== -->
    <section class="ud-login">
        <div class="container">
            <div class="row">
                <div class="col-lg-12" style="padding-bottom:5rem;">
                    <div class="ud-login-wrapper">
                        <div class="ud-login-logo">
                            <img src="Public/images/icono.png" alt="logo" />
                        </div>
                        <form class="ud-login-form" action="index.php" method="POST">
                            <input type="hidden" name="action" value="login">
                            <div class="ud-form-group">
                                <input type="email" id="email" placeholder="Correo electronico" name="email" required />
                            </div>
                            <div class="ud-form-group">
                                <input type="password" placeholder="Contraseña" id="contrasena" name="contrasena" required />
                            </div>
                            <?php if (isset($mensaje)): ?>
                                <div class="alert alert-danger">
                                    <?php echo htmlspecialchars($mensaje); ?>
                                </div>
                            <?php endif; ?>
                            <div class="ud-form-group">
                                <button type="submit" class="ud-main-btn w-100">Iniciar Sesión</button>
                            </div>


                        </form>

                        <div class="ud-socials-connect">
                            <p> O </p>
                            <p>Iniciar sesión con</p>

                            <ul>
                                <li>
                                    <a href="index.php?action=login_google" class="google" style="color: #fff;">
                                        <i class="uil uil-google">mail</i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <a class="forget-pass" href="index.php?page=recuperar_contrasena">
                            ¿Olvidaste tu contraseña?
                        </a>
                        <p class="signup-option">
                            ¿No eres miembro aún? <a href="index.php?page=registro"> Registrate </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ====== Login End ====== -->
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

    <!-- ====== Footer End ====== -->


    <!-- ====== All Javascript Files ====== -->
    <script src="Public/js/bootstrap.bundle.min.js"></script>
    <script src="Public/js/wow.min.js"></script>

</body>