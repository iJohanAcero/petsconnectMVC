<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer contraseña</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; }
        .container { max-width: 400px; margin: 60px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);}
        h2 { text-align: center; color: #333; }
        label { display: block; margin-bottom: 8px; color: #555; }
        input[type="email"], input[type="contrasena"] { width: 100%; padding: 10px; margin-bottom: 18px; border: 1px solid #ccc; border-radius: 4px; }
        button { width: 100%; padding: 10px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer;}
        button:hover { background: #0056b3; }
        .mensaje { color: green; text-align: center; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Restablecer contraseña</h2>
        <form action="/petsconnectmvc/index.php?action=guardar_nueva_contrasena" method="post">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
            <label for="email">Confirma tu correo electrónico:</label>
            <input type="email" id="email" name="email" required placeholder="Ingresa tu correo">
            <label for="contrasena">Nueva contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required placeholder="Nueva contrasena">
            <label for="contrasena2">Repite la contraseña:</label>
            <input type="password" id="contrasena2" name="contrasena2" required placeholder="Repite la contrasena">
            <button type="submit">Restablecer contraseña</button>
        </form>
        <?php if (isset($mensaje)) : ?>
            <p class="mensaje"><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>
        <?php if (isset($error) && !empty($error)) : ?>
            <div class="error" style="color: red; margin-bottom: 10px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if (isset($mensaje) && strpos($mensaje, 'restablecida correctamente') !== false): ?>
            <script>
                console.log("¡Contraseña restablecida correctamente!");
            </script>
        <?php endif; ?>
    </div>
</body>
</html>