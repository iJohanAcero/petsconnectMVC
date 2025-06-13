<?php

class AuthController
{
    // Enviar enlace de recuperación de contraseña
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Buscar usuario por email
        $usuario = \App\Models\Usuario::where('email', $request->email)->first();
        if (!$usuario) {
            return back()->withErrors(['email' => 'No se encontró un usuario con ese email.']);
        }

        // Generar token único
        $token = Str::random(60);
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

        $usuario->notify(new ResetPasswordNotification($token));
        return back()->with('status', 'Se ha enviado un enlace de recuperación a tu email.');
    }

    // Mostrar formulario para restablecer contraseña
    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');
        return view('auth.reset-password', ['token' => $token, 'email' => $email]);
    }

    // Procesar el restablecimiento de contraseña
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'token' => 'required'
        ]);

        // Buscar el registro de password_resets
        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'El enlace de recuperación no es válido o ha expirado.']);
        }

        // Buscar el usuario y actualizar la contraseña
        $usuario = \App\Models\Usuario::where('email', $request->email)->first();
        if (!$usuario) {
            return back()->withErrors(['email' => 'No se encontró un usuario con ese email.']);
        }
        $usuario->password = Hash::make($request->password);
        $usuario->save();

        // Eliminar el registro de password_resets
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', '¡Contraseña restablecida correctamente! Ya puedes iniciar sesión.');
    }

    // Actualizar perfil de usuario
    public function updateProfile(Request $request)
    {
        $user = Usuario::find(Auth::id());
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('profile', 'public');
            $user->imagen = $path;
        }

        $user->save();
        return back()->with('success', 'Perfil actualizado correctamente.');
    }


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

                // Enviar correo con el enlace de recuperación
                $enlace = "http://localhost/petsconnectMVC/view/login/restablecerContraseña.php?token=$token";
                $asunto = "Recupera tu contraseña";
                $mensajeCorreo = "Hola,\n\nHaz clic en el siguiente enlace para restablecer tu contraseña:\n$enlace\n\nSi no solicitaste este cambio, ignora este correo.";
                $cabeceras = "From: no-reply@petsconnect.com\r\n";
                if (mail($email, $asunto, $mensajeCorreo, $cabeceras)) {
                    $mensaje = "Se ha enviado un enlace de recuperación a tu correo.";
                } else {
                    $error = "No se pudo enviar el correo. Intenta más tarde.";
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
            $password = $_POST['password'] ?? '';
            $password2 = $_POST['password2'] ?? '';

            if ($password !== $password2) {
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
            $stmt = $conn->prepare("SELECT id_usuario, fecha_expiracion FROM t_recuperar_constrasena WHERE codigo_recuperacion = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->bind_result($id_usuario, $fecha_expiracion);
            if ($stmt->fetch()) {
                if (strtotime($fecha_expiracion) < strtotime(date('Y-m-d'))) {
                    $error = "El enlace ha expirado.";
                    $stmt->close();
                    $conn->close();
                    require __DIR__ . '/../view/login/restablecerContraseña.php';
                    return;
                }
                $stmt->close();

                // Actualizar contraseña
                $hash = password_hash($password, PASSWORD_DEFAULT);
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

