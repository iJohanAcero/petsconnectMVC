<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    
</head>
<body>
    <div class="container">
        <h2>Recuperar Contraseña</h2>
        <form action="/petsconnectmvc/index.php?action=enviar_recuperacion" method="post">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required placeholder="Ingresa tu correo">
            <button type="submit">¿Ya tienes un token? Restablecer contraseña</button>
        </form>
        <div style="text-align:center; margin-top:10px;">
            <!-- Simulación de enlace recibido por correo -->
            <a href="./restablecerContraseña.php">¿Ya tienes un token? Restablecer contraseña</a>
        </div>
        <?php if (isset($mensaje)) : ?>
            <p class="mensaje"><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>
        <?php if (isset($error)) : ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>