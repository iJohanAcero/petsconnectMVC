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

<body style="background-color: f3f4fe; background-image: url('Public/images/login/background.jpg'); background-size:contain;">

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

    <script src="Public/js/bootstrap.bundle.min.js"></script>
    <script src="Public/js/wow.min.js"></script>
</body>

</html>