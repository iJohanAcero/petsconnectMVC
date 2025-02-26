<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuarios</title>
</head>
<body>
    <h1>Registrar Nuevo usuario</h1>
    <form action="../index.php" method="POST">
        <input type="hidden" name="action" value="register">
        <label for="username">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        <br>
        <label for="contrasena">Contraseña: </label>
        <input type="text" name="contrasena" id="contrasena" required> 
        <br>
        <label for="email">Email:</label>
        <input type="text" name="email" id="email" required>
        <br>
        <label for="password">Direccion: </label>
        <input type="text" name="direccion" id="direccion" required> 
        <br>
        <label for="password">Telefono: </label>
        <input type="text" name="telefono" id="telefono" required> 
        <br>
        <button type="submit">Registrar</button>
    </form>
    
    <hr>
    <a href="../index.php">Volver al Login</a>
</body>
</html>