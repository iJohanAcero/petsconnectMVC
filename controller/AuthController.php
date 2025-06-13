<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php'; // Ajusta la ruta si es necesario

class AuthController
{
    // Enviar enlace de recuperación de contraseña




    // Mostrar formulario de recuperación
    public function mostrarRecuperar()
    {
        require_once __DIR__ . '/../view/login/recuperarContraseña.php';
    }

    // Procesar solicitud de recuperación
    public function enviar_recuperacion()
    {
        $mensaje = '';
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';

            $conn = new mysqli("localhost", "root", "", "petsconnect");
            if ($conn->connect_error) {
                $error = "Error de conexión a la base de datos.";
                require __DIR__ . '/../view/login/recuperarContraseña.php';
                return;
            }

            // Buscar usuario por email
            $stmt = $conn->prepare("SELECT id_usuario FROM t_usuario WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($id_usuario);
            if ($stmt->fetch()) {
                $stmt->close();
                // Generar token y fechas
                $token = bin2hex(random_bytes(16));
                $fecha_solicitud = date('Y-m-d');
                $fecha_expiracion = date('Y-m-d', strtotime('+1 day'));

                // Guardar token
                $stmt = $conn->prepare("INSERT INTO t_recuperar_constrasena (codigo_recuperacion, email, fecha_solicitud, fecha_expiracion, id_usuario) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssi", $token, $email, $fecha_solicitud, $fecha_expiracion, $id_usuario);
                $stmt->execute();
                $stmt->close();

                // Enlace de restablecimiento
                $url = "http://localhost:8080/petsconnectMVC/view/login/restablecerContraseña.php?token=$token";
                $mensaje = 'Haz clic en el siguiente enlace para cambiar tu contraseña: <a href="' . $url . '">Cambiar contraseña</a>';

                // Envío de correo (opcional)
                $mail = new PHPMailer(true);

                try {
                    // Configuración del servidor SMTP de Gmail
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'pablovela.upn@gmail.com'; // Tu correo de Gmail
                    $mail->Password = 'ezfx nzzz refl boit
'; // Contraseña de aplicación de Gmail
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('pablovela.upn@gmail.com', 'PetsConnect');
                    $mail->addAddress($email); // $email es el destinatario

                    $mail->Subject = 'Recupera tu contraseña';
                    $mail->Body    = "Hola,\n\nHaz clic en el siguiente enlace para restablecer tu contraseña:\n$url\n\nSi no solicitaste este cambio, ignora este correo.";

                    $mail->send();
                    $mensaje .= "<br>Se ha enviado un enlace de recuperación a tu correo.";
                } catch (Exception $e) {
                    $mensaje .= "<br><span style='color:red;'>No se pudo enviar el correo. Usa el enlace de arriba.<br>Error: {$mail->ErrorInfo}</span>";
                }
            } else {
                $error = "El correo no está registrado.";
            }
            $conn->close();
        }
        require __DIR__ . '/../view/login/recuperarContraseña.php';
    }

    // Mostrar formulario de restablecimiento
    public function mostrarRestablecer()
    {
        require_once __DIR__ . '/../view/login/restablecerContraseña.php';
    }

    // Procesar restablecimiento de contraseña
    public function guardar_nueva_contraseña()
    {
        $mensaje = '';
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $email = $_POST['email'] ?? '';
            $contrasena = $_POST['contrasena'] ?? '';
            $contrasena2 = $_POST['contrasena2'] ?? '';
die(var_dump($token, $email, $contrasena, $contrasena2));
            if (empty($token) || empty($email) || empty($contrasena) || empty($contrasena2)) {
                $error = "Todos los campos son obligatorios.";
                require __DIR__ . '/../view/login/restablecerContraseña.php';
                return;
            }
            if ($contrasena !== $contrasena2) {
                $error = "Las contraseñas no coinciden.";
                require __DIR__ . '/../view/login/restablecerContraseña.php';
                return;
            }

            $conn = new mysqli("localhost", "root", "", "petsconnect");
            if ($conn->connect_error) {
                $error = "Error de conexión a la base de datos.";
                require __DIR__ . '/../view/login/restablecerContraseña.php';
                return;
            }

            // Validar token y obtener usuario
            $stmt = $conn->prepare("SELECT id_usuario, email, fecha_expiracion FROM t_recuperar_constrasena WHERE codigo_recuperacion = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->bind_result($id_usuario, $email_token, $fecha_expiracion);
            $prueba = $stmt->execute();
            die(var_dump($prueba));
            if ($stmt->fetch()) {
                if (strtotime($fecha_expiracion) < strtotime(date('Y-m-d'))) {
                    $error = "El enlace ha expirado.";
                    $stmt->close();
                    $conn->close();
                    require __DIR__ . '/../view/login/restablecerContraseña.php';
                    return;
                }
                if ($email !== $email_token) {
                    $error = "El correo no coincide con el de la solicitud.";
                    $stmt->close();
                    $conn->close();
                    require __DIR__ . '/../view/login/restablecerContraseña.php';
                    return;
                }
                $stmt->close();

                // Actualizar contraseña
                $hash = password_hash($contrasena, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE t_usuario SET contrasena = ? WHERE id_usuario = ?");
                $stmt->bind_param("si", $hash, $id_usuario);
                $stmt->execute();
                $stmt->close();

                // Eliminar token usado
                $stmt = $conn->prepare("DELETE FROM t_recuperar_constrasena WHERE codigo_recuperacion = ?");
                $stmt->bind_param("s", $token);
                $stmt->execute();
                $stmt->close();

                $mensaje = "¡Contraseña restablecida correctamente! Ya puedes iniciar sesión.";
            } else {
                $error = "El enlace no es válido o ha expirado.";
            }
            $conn->close();
        }
        require __DIR__ . '/../view/login/restablecerContraseña.php';
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'enviar_recuperacion') {
    (new AuthController())->enviar_recuperacion();
}
