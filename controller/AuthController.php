<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php'; // Ajusta la ruta si es necesario

class AuthController
{
    // Mostrar formulario de recuperación
    public function mostrarRecuperar()
    {
        require_once __DIR__ . '/../view/login/recuperarContraseña.php';
    }

    // Procesar solicitud de recuperación
    public function enviar_recuperacion()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';

            $conn = new mysqli("localhost", "root", "", "petsconnect");
            if ($conn->connect_error) {
                $error = "Error de conexión a la base de datos.";
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
                $url = "http://localhost/petsconnectMVC/view/login/restablecerContraseña.php?token=$token";
                $mensaje = 'Haz clic en el siguiente enlace para cambiar tu contraseña: <a href="' . $url . '">Cambiar contraseña</a>';

                // Envío de correo (opcional)
                $mail = new PHPMailer(true);

                try {
                    // Configuración del servidor SMTP de Gmail
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'pablovela.upn@gmail.com'; // Tu correo de Gmail
                    $mail->Password = 'azky mxkm gqwa awvt'; 
                    // Contraseña de aplicación de Gmail
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
            } 
            $conn->close();
        }
    }

    // Mostrar formulario de restablecimiento
    public function mostrarRestablecer()
    {
        require_once __DIR__ . '/../view/login/restablecerContraseña.php';
    }

    // Procesar restablecimiento de contraseña
    public function guardar_nueva_contrasena()
    {
        global $mensaje, $error;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $email = $_POST['email'] ?? '';
            $contrasena = $_POST['contrasena'] ?? '';
            $contrasena2 = $_POST['contrasena2'] ?? '';
            // Depuración: mostrar datos recibidos

            if (empty($email) || empty($contrasena) || empty($contrasena2)) {
                $error = "Todos los campos son obligatorios.";

                return;
            }
            if ($contrasena !== $contrasena2) {
                $error = "Las contraseñas no coinciden.";
                return;
            }

            $conn = new mysqli("localhost", "root", "", "petsconnect");
            if ($conn->connect_error) {
                $error = "Error de conexión a la base de datos.";
                return;
            }

            // Validar token y obtener usuario
            $stmt = $conn->prepare("SELECT id_usuario, email, fecha_expiracion FROM t_recuperar_constrasena WHERE codigo_recuperacion = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->bind_result($id_usuario, $email_token, $fecha_expiracion);
            if ($stmt->fetch()) {
                
                if (strtotime($fecha_expiracion) < strtotime(date('Y-m-d'))) {
                    $error = "El enlace ha expirado.";
                    $stmt->close();
                    $conn->close();
                    return;
                }
                if ($email !== $email_token) {
                    $error = "El correo no coincide con el de la solicitud.";
                    $stmt->close();
                    $conn->close();
                    return;
                }
                $stmt->close();

                // Actualizar contraseña
                $hash = password_hash($contrasena, PASSWORD_DEFAULT);
                // Verifica los valores antes de actualizar
                var_dump($hash, $id_usuario);

                $stmt = $conn->prepare("UPDATE t_usuario SET contrasena = ? WHERE email = ?");
                $stmt->bind_param("ss", $hash, $email);
                $stmt->execute();

                // Verifica si realmente se actualizó alguna fila
                if ($stmt->affected_rows > 0) {
                    $mensaje = "¡Contraseña restablecida correctamente! Ya puedes iniciar sesión.";
                } else {
                    $error = "No se pudo actualizar la contraseña. Verifica tus datos.";
                }

                $stmt->close();

                // Eliminar token usado
                $stmt = $conn->prepare("DELETE FROM t_recuperar_constrasena WHERE codigo_recuperacion = ?");
                $stmt->bind_param("s", $token);
                $stmt->execute();
                $stmt->close();
            } else {
                $error = "El enlace no es válido o ha expirado.";
            }
            $conn->close();
        }
    }
}
