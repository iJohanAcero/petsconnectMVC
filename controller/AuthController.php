<?php
class AuthController
{
    // Muestra el formulario de recuperación
    public function mostrarRecuperar()
    {
        require_once '../view/login/recuperarContraseña.php';
    }

    // Procesa el envío del formulario de recuperación
    public function enviar_recuperacion()
    {
        $mensaje = '';
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';

            // Conexión a la base de datos (ajusta según tu config)
            $conn = new mysqli("localhost", "root", "", "petsconnect");
            if ($conn->connect_error) {
                $error = "Error de conexión a la base de datos.";
                require '../view/login/recuperarContraseña.php';
                return;
            }

            // Buscar usuario por email
            $stmt = $conn->prepare("SELECT id_usuario FROM t_usuario WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($id_usuario);
            if ($stmt->fetch()) {
                // Usuario encontrado, genera token
                $stmt->close();
                $token = bin2hex(random_bytes(16));
                $fecha_solicitud = date('Y-m-d');
                $fecha_expiracion = date('Y-m-d', strtotime('+1 day'));

                // Guarda el token en la tabla de recuperación
                $stmt = $conn->prepare("INSERT INTO t_recuperar_constrasena (codigo_recuperacion, email, fecha_solicitud, fecha_expiracion, id_usuario) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssi", $token, $email, $fecha_solicitud, $fecha_expiracion, $id_usuario);
                $stmt->execute();
                $stmt->close();

                // Aquí puedes enviar el correo real con el enlace
                $enlace = "http://localhost/petsconnectMVC/view/login/restablecerContraseña.php?token=$token";
                // mail($email, "Recupera tu contraseña", "Enlace: $enlace");

                $mensaje = "Se ha enviado un enlace de recuperación a tu correo.";
            } else {
                $error = "El correo no está registrado.";
            }
            $conn->close();
        }
        require '../view/login/recuperarContraseña.php';
    }

    // Muestra el formulario para restablecer la contraseña
    public function mostrarRestablecer()
    {
        $token = $_GET['token'] ?? '';
        require '../view/login/restablecerContraseña.php';
    }

    // Procesa el restablecimiento de la contraseña
    public function guardar_nueva_contraseña()
    {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        if ($password !== $password2) {
            $error = "Las contraseñas no coinciden.";
            require '../view/login/restablecerContraseña.php';
            return;
        }

        // Busca el token en la base de datos y verifica que no haya expirado
        $tokenValido = true; // Cambia esto por tu lógica real

        if ($tokenValido) {
            // Actualiza la contraseña del usuario (hasheada)
            // password_hash($password, PASSWORD_DEFAULT)
            // Elimina o invalida el token
            $mensaje = "Contraseña restablecida correctamente.";
        } else {
            $error = "El enlace no es válido o ha expirado.";
        }
        require '../view/login/restablecerContraseña.php';
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'enviar_recuperacion') {
    (new AuthController())->enviar_recuperacion();
}