
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Restablecer contraseña</title>
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

                    <!-- Logo si quieres mantenerlo (opcional) -->
                    <div class="ud-login-logo">
                        <img src="Public/images/icono.png" alt="logo" />
                    </div>

                    <!-- Título -->
                    <h2 class="text-center mb-4">Restablecer contraseña</h2>

                    <!-- Formulario de restablecimiento -->
                    <form class="ud-login-form" action="index.php?page=restablecer_contrasena" method="post">
                        <input type="hidden" name="action" value="guardar_nueva_contrasena">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? $_POST['token'] ?? '') ?>">

                        <div class="ud-form-group">
                            <input type="email" id="email" name="email" required placeholder="Confirma tu correo electrónico" />
                        </div>
                        <div class="ud-form-group">
                            <input type="password" id="contrasena" name="contrasena" required placeholder="Nueva contraseña" />
                        </div>
                        <div class="ud-form-group">
                            <input type="password" id="contrasena2" name="contrasena2" required placeholder="Repite la contraseña" />
                        </div>
                        <div class="ud-form-group">
                            <button type="submit" class="ud-main-btn w-100">Restablecer contraseña</button>
                        </div>
                    </form>

                    <!-- Botón para volver al login -->
                    <form action="index.php" method="get">
                        <div class="ud-form-group">
                            <button type="submit" name="action" value="mostrar_login" class="ud-main-btn w-100 btn-secondary">
                                Volver al Login
                            </button>
                        </div>
                    </form>

                    <!-- Mensajes de éxito o error -->
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

                    <?php if (isset($mensaje) && strpos($mensaje, 'restablecida correctamente') !== false): ?>
                        <script>
                            console.log("¡Contraseña restablecida correctamente!");
                        </script>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</section>
</body>

</html>