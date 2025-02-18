<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="shortcut icon" href="Public/images/icono.png" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css"> <!-- Llamamos a la librería de iconos --> 
    <link rel="stylesheet" href="Public/css/login.css" />
    <title>PetsConnect inicio sesión</title>
</head>

<body>
    <div class="container">
    
        <div class="forms-container">
            <div class="signin-signup">
                <form action="#" class="sign-in-form">
                    <img src="Public/images/logo.png" alt="">
                    <h2 class="title">Iniciar Sesión</h2>
                    <div class="input-field">
                    <i class="uil uil-user-square"></i>
                        <input type="email" placeholder="Correo electronico" />
                    </div>
                    <div class="input-field">
                    <i class="uil uil-padlock"></i>
                        <input type="password" placeholder="Contraseña" />
                    </div>
                    <input type="submit" value="Entrar" class="btn solid" />
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
                <form action="#" class="sign-up-form">
                    <input>Registrate</input>
                        <input type="text" placeholder="Username" />
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
                    <button class="btn transparent" id="sign-up-btn">
                        Registrate
                    </button>
                </div>
                <img src="Public/images/login1.png" class="image" alt="" />
            </div>
        </div>
    </div>
</body>

</html>