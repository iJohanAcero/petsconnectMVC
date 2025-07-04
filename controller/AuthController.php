<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php'; // Ajusta la ruta si es necesario
require_once __DIR__ . '/../config/roles.php';

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
                $url = "http://localhost/petsconnectMVC/index.php?page=restablecer_contrasena&token=$token";
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

            if (empty($email) || empty($contrasena) || empty($contrasena2)) {
                $error = "Todos los campos son obligatorios.";
            } elseif ($contrasena !== $contrasena2) {
                $error = "Las contraseñas no coinciden.";
            } else {
                $conn = new mysqli("localhost", "root", "", "petsconnect");
                if ($conn->connect_error) {
                    $error = "Error de conexión a la base de datos.";
                } else {
                    $stmt = $conn->prepare("SELECT id_usuario, email, fecha_expiracion FROM t_recuperar_constrasena WHERE codigo_recuperacion = ?");
                    $stmt->bind_param("s", $token);
                    $stmt->execute();
                    $stmt->bind_result($id_usuario, $email_token, $fecha_expiracion);
                    if ($stmt->fetch()) {
                        if (strtotime($fecha_expiracion) < strtotime(date('Y-m-d'))) {
                            $error = "El enlace ha expirado.";
                        } elseif ($email !== $email_token) {
                            $error = "El correo no coincide con el de la solicitud.";
                        } else {
                            $stmt->close();
                            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
                            $stmt = $conn->prepare("UPDATE t_usuario SET contrasena = ? WHERE email = ?");
                            $stmt->bind_param("ss", $hash, $email);
                            $stmt->execute();
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
                        }
                    } else {
                        $error = "El enlace no es válido o ha expirado.";
                    }
                    $conn->close();
                }
            }
        }
        // SIEMPRE muestra la vista al final, así los mensajes se ven
        require __DIR__ . '/../view/login/restablecerContraseña.php';
    }

    public function enviar_tutorial()
    {
        $mensaje = '';
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = $_POST['fullName'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $message = $_POST['message'] ?? '';

            // Validaciones
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error .= "El correo no es válido.<br>";
            }
            if (empty($fullname) || empty($phone) || empty($message)) {
                $error .= "Todos los campos son obligatorios.<br>";
            }

            if ($error === '') {
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'pablovela.upn@gmail.com';
                    $mail->Password = 'azky mxkm gqwa awvt';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Mejor práctica: setFrom seguro y addReplyTo para el usuario
                    $mail->setFrom('pablovela.upn@gmail.com', 'Notificaciones PetsConnect');
                    $mail->addReplyTo($email, $fullname);
                    $mail->addAddress("pablovela.upn@gmail.com");

                    $mail->isHTML(true);
                    $mail->Subject = 'Solicitud para pertenecer a PetsConnect';
                    $mail->Body = "
                        <h3>Solicitud de Tutorial</h3>
                        <p><strong>Nombre:</strong> $fullname</p>
                        <p><strong>Email:</strong> $email</p>
                        <p><strong>Teléfono:</strong> $phone</p>
                        <p><strong>Mensaje:</strong><br>$message</p>
                    ";

                    $mail->send();
                    $mensaje .= "<br>El mensaje ha sido enviado correctamente. Nos pondremos en contacto contigo pronto.";
                } catch (Exception $e) {
                    $mensaje .= "<br><span style='color:red;'>No se pudo enviar el correo. Usa el enlace de arriba.<br>Error: {$mail->ErrorInfo}</span>";
                }
            }
        }
        print_r($mensaje);
    }

    public function loginGoogle()
    {
        $client = new Google_Client();
        $client->setClientId('637931459042-873opva17515qd99c4dj51i6202jdqlf.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri('http://localhost/petsconnectmvc/index.php?action=google_callback');
        $client->addScope('email');
        $client->addScope('profile');

        $login_url = $client->createAuthUrl();
        header('Location: ' . $login_url);
        exit;
    }

    // Callback de Google
    public function googleCallback()
    {
        $client = new Google_Client();
        $client->setClientId('637931459042-873opva17515qd99c4dj51i6202jdqlf.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri('http://localhost/petsconnectmvc/index.php?action=google_callback');

        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

            if (isset($token['error'])) {
                echo "<h3>Error al obtener el token de Google:</h3>";
                echo "<p><strong>Código:</strong> " . htmlspecialchars($token['error']) . "</p>";
                echo "<p><strong>Descripción:</strong> " . htmlspecialchars($token['error_description'] ?? 'Sin descripción') . "</p>";
                exit;
            }

            $client->setAccessToken($token['access_token']);

            // Obtener información del usuario
            $oauth2 = new Google_Service_Oauth2($client);
            $google_user = $oauth2->userinfo->get();

            if (isset($google_user->id)) {
                $usuarioModel = new Usuario();
                // Usamos el método findOrCreateGoogleUser que ya tienes en tu modelo
                $user = $usuarioModel->findOrCreateGoogleUser(
                    $google_user->id,
                    $google_user->givenName ?? '',
                    $google_user->familyName ?? '',
                    $google_user->email,
                    $google_user->picture ?? null
                );

                session_start();
                $_SESSION['user'] = $user;

                if (esAdmin($user["id_usuario"])) {
                    $_SESSION["tipo_usuario"] = "admin";
                    header("Location: index.php?page=admin_home");
                    exit;
                } else if (esGuardian($user["id_usuario"])) {
                    $_SESSION["tipo_usuario"] = "guardian";
                    header("Location: index.php?page=guardian_home");
                    exit;
                } else if (esFundacion($user["id_usuario"])) {
                    $_SESSION["tipo_usuario"] = "fundacion";
                    header("Location: index.php?page=fundacion_home");
                    exit;
                } else {
                    $_SESSION["tipo_usuario"] = "desconocido";
                    session_destroy();
                    header("Location: index.php?page=login");
                    exit;
                }
                header('Location: index.php?page=guardian_home');
                exit;
            } else {
                echo "<h3>No se pudo obtener información del usuario.</h3>";
                exit;
            }
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'enviar_recuperacion') {
    (new AuthController())->enviar_recuperacion();
}
if (isset($_GET['action']) && $_GET['action'] === 'enviar_tutorial') {
    $controller = new AuthController();
    $controller->enviar_tutorial();
    exit;
}
