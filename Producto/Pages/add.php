<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar producto</title>
</head>

<body>
    <h1>Agregar producto</h1>
    <form method="POST" action="../Controlador/add.php">
        Nombre<br>
        <input type="text" name="Nombre" required autocomplete="off" placeholder="Nombre producto"><br><br>
        Tipo de producto <br>
        <select name="TipoProducto" required>
            <option>Seleccione</option>
            <option value="ComidaGato">Comida para gato</option>
            <option value="ComidaPerro">Comida para perro</option>
            <option value="ArenaGato">Arena para gatos</option>
        </select><br><br>
        Descripción <br>
        <input type="text" name="Descripcion" required autocomplete="off" placeholder="Descripción del producto"><br><br>
        Precio <br>
        <input type="number" name="Precio" required autocomplete="off" placeholder="Precio producto"><br><br>
        Cantidad disponible <br>
        <input type="number" name="Cantidad" required autocomplete="off" placeholder="Cantidad disponible"><br><br>
        <input type="submit" value="Registrar producto">
    </form>
</body>

</html>