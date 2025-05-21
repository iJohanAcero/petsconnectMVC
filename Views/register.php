<!DOCTYPE html>
<html lang="es">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../Public/css/login.css">
    <title>Registrar Usuarios</title>
</head>

<body>


    <div class="barra-top">
        <img class="logo" src="../Public/images/logo-oscuro.png">
    </div>

    <div class="formulario">
        <form class="form" action="../index.php" method="POST">
            <input type="hidden" name="action" value="register">
            <p class="title_registro">Registrar Nuevo usuario </p>
            <p class="message"> Registrate para tener acceso a todas las funcionalidades </p>
            <div class="flex">
                <label>
                    <input class="input" name="nombre" id="nombre" type="text" placeholder="" required>
                    <span>Nombre</span>
                </label>

                <label>
                    <input class="input" name="apellido" id="apellido" type="text" placeholder="" required>
                    <span>Apellido</span>
                </label>
            </div>

            <label>
                <input class="input" name="contrasena" id="contrasena" type="password" placeholder="" required>
                <span>Password</span>
            </label>

            <!-- <label>
                    <input class="input" name="email" id="email" type="email" placeholder="" required>
                    <span>Email</span>
            </label> -->

            <div class="validar-email">
                <label>
                    <input class="input" name="email" id="email" type="email" placeholder="" required>
                    <span>Email</span>
                </label>
                <div id="checked-icon" class="NoEmail"></div>
            </div>


            <label>
                <input class="input" name="direccion" id="direccion" type="text" placeholder="" required>
                <span>Direccion</span>
            </label>

            <label>
                <input class="input" name="telefono" id="telefono" type="text" placeholder="" required>
                <span>Telefono</span>
            </label>

            <button class="submit">Registrar </button>
            <p class="signin">Ya tienes una cuenta ? <a href="../index.php">Inicio de Sesión</a> </p>
        </form>
    </div>

    <script src="../Public/js/regex.js"></script>
</body>

</html>