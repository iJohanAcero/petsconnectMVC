<?php
namespace App\Controller;

use App\Model\Usuario\Usuario;
use Google\Service\Oauth2;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Config\Roles;
use PDO;

require_once __DIR__ . '/../vendor/autoload.php';
class AuthController
{
    public function mostrarRecuperar()
    {
        require_once __DIR__ . '/../view/login/recuperarContraseña.php';
    }

    // Procesar solicitud de recuperación
    public function enviar_recuperacion()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';

            // Usar tu clase de conexión (PDO)
            $conexion = new \App\Model\Conexion();
            $db = $conexion->getConexion();

            // Buscar usuario por email
            $stmt = $db->prepare("SELECT id_usuario FROM t_usuario WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                $id_usuario = $usuario['id_usuario'];

                // Generar token y fechas
                $token = bin2hex(random_bytes(16));
                $fecha_solicitud = date('Y-m-d');
                $fecha_expiracion = date('Y-m-d', strtotime('+1 day'));


                // Guardar token
                $stmt = $db->prepare("INSERT INTO t_recuperar_constrasena (codigo_recuperacion, email, fecha_solicitud, fecha_expiracion, id_usuario) 
                                      VALUES (:token, :email, :fecha_solicitud, :fecha_expiracion, :id_usuario)");
                $stmt->execute([
                    ':token' => $token,
                    ':email' => $email,
                    ':fecha_solicitud' => $fecha_solicitud,
                    ':fecha_expiracion' => $fecha_expiracion,
                    ':id_usuario' => $id_usuario
                ]);

                // Enlace de restablecimiento
                $url = "https://petsconnectcol.com/index.php?page=restablecer_contrasena&token=$token";
                $mensaje = 'Haz clic en el siguiente enlace para cambiar tu contraseña: <a href="' . $url . '">Cambiar contraseña</a>';

                // Envío de correo
                $mail = new PHPMailer(true);

                try {
                    // Configuración del servidor SMTP de Gmail
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'petsconnectcol@gmail.com'; // Tu correo de Gmail
                    $mail->Password = 'slwz wdtl dsxi jqih';
                    // Contraseña de aplicación de Gmail
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('petsconnectcol@gmail.com', 'PetsConnect');
                    $mail->addAddress($email); // $email es el destinatario

                    $mail->Subject = 'Recuperación de contraseña - Soporte ';
                    $mail->isHTML(true);
                    $mail->Body = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color:hsl(252, 30%, 17%);;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 0 0 5px 5px;
            border: 1px solid #ddd;
            border-top: none;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: hsl(252, 30%, 17%);;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 15px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Recupera tu contraseña</h2>
    </div>
    <div class="content">
        <p>Hola,</p>
        <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta.</p>
        <p>Por favor, haz clic en el siguiente botón para continuar con el proceso:</p>
        
        <p style="text-align: center;">
            <a href="' . $url . '" class="button">Restablecer contraseña</a>
        </p>
        
        <p>Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
        <small>' . $url . '</small></p>
        
        <p><strong>¿No solicitaste este cambio?</strong><br>
        Si no fuiste tú quien solicitó restablecer la contraseña, puedes ignorar este mensaje. Tu contraseña permanecerá igual.</p>
        
        <div class="footer">
            <p>Este enlace expirará en 24 horas por motivos de seguridad.</p>
        </div>
    </div>
</body>
</html>
';

                    $mail->send();
                    $mensaje .= "<br>Se ha enviado un enlace de recuperación a tu correo.";
                } catch (Exception $e) {
                    $mensaje .= "<br><span style='color:red;'>No se pudo enviar el correo. Usa el enlace de arriba.<br>Error: {$mail->ErrorInfo}</span>";
                }
            }
            $conexion = null;
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
                try {
                    // Conectar con PDO
                    $conexion = new \App\Model\Conexion();
                    $db = $conexion->getConexion();

                    // Verificar token
                    $stmt = $db->prepare("SELECT id_usuario, email, fecha_expiracion 
                                      FROM t_recuperar_constrasena 
                                      WHERE codigo_recuperacion = :token");
                    $stmt->execute([':token' => $token]);
                    $recuperacion = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($recuperacion) {
                        if (strtotime($recuperacion['fecha_expiracion']) < strtotime(date('Y-m-d'))) {
                            $error = "El enlace ha expirado.";
                        } elseif ($email !== $recuperacion['email']) {
                            $error = "El correo no coincide con el de la solicitud.";
                        } else {
                            // Actualizar contraseña
                            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
                            $stmt = $db->prepare("UPDATE t_usuario SET contrasena = :contrasena WHERE email = :email");
                            $stmt->execute([
                                ':contrasena' => $hash,
                                ':email' => $email
                            ]);

                            if ($stmt->rowCount() > 0) {
                                $mensaje = "¡Contraseña restablecida correctamente! Ya puedes iniciar sesión.";
                            } else {
                                $error = "No se pudo actualizar la contraseña. Verifica tus datos.";
                            }

                            // Eliminar token usado
                            $stmt = $db->prepare("DELETE FROM t_recuperar_constrasena WHERE codigo_recuperacion = :token");
                            $stmt->execute([':token' => $token]);
                        }
                    } else {
                        $error = "El enlace no es válido o ha expirado.";
                    }

                    // Cerrar conexión explícitamente
                    $db = null;
                } catch (\PDOException $e) {
                    $error = "Error de conexión a la base de datos: " . $e->getMessage();
                }
            }
        }

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
                    $mail->Username = 'petsconnectcol@gmail.com';
                    $mail->Password = 'slwz wdtl dsxi jqih';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('petsconnectcol@gmail.com', 'Notificaciones PetsConnect');
                    $mail->addReplyTo($email, $fullname);
                    $mail->addAddress("petsconnectcol@gmail.com");

                    $mail->isHTML(true);
                    $mail->Subject = 'Nueva Solicitud para PetsConnect - ' . $fullname;
                    $mail->Body = "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Solicitud PetsConnect</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 300;
        }
        .header .subtitle {
            margin-top: 10px;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .info-card {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .field {
            margin-bottom: 15px;
            display: flex;
            flex-wrap: wrap;
        }
        .field-label {
            font-weight: 600;
            color: #555;
            min-width: 100px;
            margin-bottom: 5px;
        }
        .field-value {
            color: #333;
            flex: 1;
            min-width: 200px;
        }
        .message-section {
            background-color: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        .message-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 10px;
            display: block;
        }
        .message-content {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border-left: 3px solid #28a745;
            white-space: pre-line;
            line-height: 1.6;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
        .timestamp {
            color: #6c757d;
            font-size: 12px;
            text-align: right;
            margin-top: 20px;
            font-style: italic;
        }
        @media (max-width: 600px) {
            .email-container {
                margin: 0 10px;
            }
            .content {
                padding: 20px;
            }
            .field {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class='email-container'>
        <div class='header'>
            <h1>🐾 PetsConnect</h1>
            <div class='subtitle'>Nueva Solicitud de Registro</div>
        </div>
        
        <div class='content'>
            <div class='info-card'>
                <div class='field'>
                    <span class='field-label'>👤 Nombre:</span>
                    <span class='field-value'>$fullname</span>
                </div>
                
                <div class='field'>
                    <span class='field-label'>📧 Email:</span>
                    <span class='field-value'>$email</span>
                </div>
                
                <div class='field'>
                    <span class='field-label'>📱 Teléfono:</span>
                    <span class='field-value'>$phone</span>
                </div>
            </div>
            
            <div class='message-section'>
                <span class='message-label'>💬 Mensaje del solicitante:</span>
                <div class='message-content'>$message</div>
            </div>
            
            <div class='timestamp'>
                Solicitud recibida el " . date('d/m/Y \a \l\a\s H:i') . "
            </div>
        </div>
        
        <div class='footer'>
            <p>Esta es una notificación automática de PetsConnect.<br>
            Por favor, responde a esta solicitud en un plazo de 24-48 horas.</p>
        </div>
    </div>
</body>
</html>
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

    public function logout()
    {
        session_start();
        // Si existe token de Google, revocarlo
        if (isset($_SESSION['google_access_token'])) {
            $client = new \Google_Client();
            $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
            $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
            $client->setAccessToken($_SESSION['google_access_token']);
            // Revocar el token de acceso
            $client->revokeToken();
        }
        // Destruir sesión
        session_destroy();
        // Redirigir
        header("Location: https://petsconnectcol.com/index.php");
        exit;
    }

    public function loginGoogle()
    {
        $client = new \Google_Client();
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
        $client->addScope('email');
        $client->addScope('profile');
        // CLAVE: Forzar prompt de selección de cuenta
        $client->setPrompt('select_account');
        
        $login_url = $client->createAuthUrl();
        header('Location: ' . $login_url);
        exit;
    }

    // Callback de Google
    public function googleCallback()
    {
        $client = new \Google_Client();
        $client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);

        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            if (isset($token['error'])) {
                echo "<h3>Error al obtener el token de Google:</h3>";
                echo "<p><strong>Código:</strong> " . htmlspecialchars($token['error']) . "</p>";
                echo "<p><strong>Descripción:</strong> " . htmlspecialchars($token['error_description'] ?? 'Sin descripción') . "</p>";
                exit;
            }
            
            $client->setAccessToken($token['access_token']);
            
            session_start();
            $_SESSION['google_access_token'] = $token;

            // Obtener información del usuario
            $oauth2 = new Oauth2($client);
            $google_user = $oauth2->userinfo->get();

            if (isset($google_user->id)) {
                $usuarioModel = new Usuario();
                // Usamos el método findOrCreateGoogleUser
                $user = $usuarioModel->findOrCreateGoogleUser(
                    $google_user->id,
                    $google_user->givenName ?? '',
                    $google_user->familyName ?? '',
                    $google_user->email,
                    $google_user->picture ?? null
                );

                session_start();
                $_SESSION['user'] = $user;

                if (Roles::esAdmin($user["id_usuario"])) {
                    $_SESSION["tipo_usuario"] = "admin";
                    header("Location: index.php?page=admin_home");
                    exit;
                } else if (Roles::esGuardian($user["id_usuario"])) {
                    $_SESSION["tipo_usuario"] = "guardian";
                    header("Location: index.php?page=guardian_home");
                    exit;
                } else if (Roles::esFundacion($user["id_usuario"])) {
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
