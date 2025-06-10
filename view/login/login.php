<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="shortcut icon" href="../Public/images/icono.png" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link
        rel="shortcut icon"
        href="../../Public/images/icono.png"
        type="image/png" />


    <!-- ===== All CSS files ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="../../Public/css/animate.css" />
    <link rel="stylesheet" href="../../Public/css/ud-styles.css" />

    <title>PetsConnect inicio sesión</title>
</head>

<body style="background-color: hsl(252, 30%, 10%);">
    <!-- ====== Header Section Start ====== -->
    <header class="ud-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg" id="header">
                        <a class="navbar-brand" href="../../index.php">
                            <img src="../../Public/images/logo/logo-oscuro.png" alt="Logo" />
                        </a>
                        <button class="navbar-toggler">
                            <span class="toggler-icon"> </span>
                            <span class="toggler-icon"> </span>
                            <span class="toggler-icon"> </span>
                        </button>

                        <div class="navbar-collapse">
                            <ul id="nav" class="navbar-nav mx-auto">
                            </ul>
                        </div>

                        <div class="navbar-btn d-none d-sm-inline-block ud-hero-buttons">
                            <a href="" class="ud-main-btn ud-login-btn">
                                Iniciar Sesión
                            </a>
                            <a class="ud-main-btn ud-white-btn" href="register.php">
                                Registrarse
                            </a>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- <div class="container">

        <div class="forms-container">
            <div class="signin-signup">
                <form action="index.php" class="sign-in-form" method="POST">
                    <img src="Public/images/logo.png" alt="">
                    <h2 class="title">Iniciar Sesión</h2>
                    <input type="hidden" name="action" value="login">
                    <div class="input-field">
                        <i class="uil uil-user-square"></i>
                        <input type="email" id="email" placeholder="Correo electronico" name="email" required />
                    </div>
                    <div class="input-field">
                        <i class="uil uil-padlock"></i>
                        <input type="password" placeholder="Contraseña" id="contrasena" name="contrasena" required />
                    </div>
                    <input type="submit" value="Entrar" class="btn solid" />

                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-danger"><?php echo $mensaje; ?></div>
                    <?php endif; ?>

                    <p class="social-text">O inicia sesion con tu cuenta de:</p>
                    <div class="social-media">
                        <a href="#" class="social-icon">
                            <i class="uil uil-facebook"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="uil uil-twitter"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="uil uil-google"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="uil uil-github"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>¿ Nuevo aqui ?</h3>
                    <p>
                        Introduce tus datos y comienza tu adopción con nosotros
                    </p>
                    <a href="view/login/register.php" class="btn transparent" id="sign-up-btn">
                        Registrate
                    </a>
                </div>
                <img src="Public/images/login1.png" class="image" alt="" />
            </div>
        </div>
    </div> -->

    <!-- ====== Banner Start ====== -->
    <!-- <section class="ud-page-banner" >
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ud-banner-content">
                        <h1>Login Page</h1>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- ====== Banner End ====== -->

    <!-- ====== Login Start ====== -->
    <section class="ud-login">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ud-login-wrapper">
                        <div class="ud-login-logo">
                            <img src="assets/images/logo/logo-2.svg" alt="logo" />
                        </div>
                        <form class="ud-login-form">
                            <div class="ud-form-group">
                                <input
                                    type="email"
                                    name="email"
                                    placeholder="Email/username" />
                            </div>
                            <div class="ud-form-group">
                                <input
                                    type="password"
                                    name="password"
                                    placeholder="*********" />
                            </div>
                            <div class="ud-form-group">
                                <button type="submit" class="ud-main-btn w-100">Login</button>
                            </div>
                        </form>

                        <div class="ud-socials-connect">
                            <p>Connect With</p>

                            <ul>
                                <li>
                                    <a href="javascript:void(0)" class="facebook">
                                        <i class="lni lni-facebook-filled"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="twitter">
                                        <i class="lni lni-twitter-filled"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="google">
                                        <i class="lni lni-google"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <a class="forget-pass" href="javascript:void(0)">
                            Forget Password?
                        </a>
                        <p class="signup-option">
                            Not a member yet? <a href="javascript:void(0)"> Sign Up </a>
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
                                    <a href="#home">Inicio</a>
                                </li>
                                <li>
                                    <a href="#about">Misión</a>
                                </li>
                                <li>
                                    <a href="#pricing">Actividades</a>
                                </li>
                                <li>
                                    <a href="#testimonials">Testimonios</a>
                                </li>
                                <li>
                                    <a href="#team">Equipo</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-6">
                        <div class="ud-widget">
                            <h5 class="ud-widget-title">Información </h5>
                            <ul class="ud-widget-links">
                                <li>
                                    <a href="#about">¿Cómo funciona?</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)">Política de privacidad</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)">Términos del servicio</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)">Política de reembolso</a>
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
                            <a href="#" rel="nofollow" class="autor">Johan Acero |</a>
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
    <script src="../../Public/js/bootstrap.bundle.min.js"></script>
    <script src="../../Public/js/wow.min.js"></script>
    <script src="../../Public/js/main.js"></script>
    <script>
        // ==== for menu scroll
        const pageLink = document.querySelectorAll(".ud-menu-scroll");

        pageLink.forEach((elem) => {
            elem.addEventListener("click", (e) => {
                e.preventDefault();
                document.querySelector(elem.getAttribute("href")).scrollIntoView({
                    behavior: "smooth",
                    offsetTop: 1 - 60,
                });
            });
        });

        // section menu active
        function onScroll(event) {
            const sections = document.querySelectorAll(".ud-menu-scroll");
            const scrollPos =
                window.pageYOffset ||
                document.documentElement.scrollTop ||
                document.body.scrollTop;

            for (let i = 0; i < sections.length; i++) {
                const currLink = sections[i];
                const val = currLink.getAttribute("href");
                const refElement = document.querySelector(val);
                const scrollTopMinus = scrollPos + 73;
                if (
                    refElement.offsetTop <= scrollTopMinus &&
                    refElement.offsetTop + refElement.offsetHeight > scrollTopMinus
                ) {
                    document
                        .querySelector(".ud-menu-scroll")
                        .classList.remove("active");
                    currLink.classList.add("active");
                } else {
                    currLink.classList.remove("active");
                }
            }
        }

        window.document.addEventListener("scroll", onScroll);

    </script>
</body>