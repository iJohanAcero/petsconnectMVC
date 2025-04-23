<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producto</title>
</head>

<body>
    <h1>Editar producto</h1>
    <form method="POST" action="../Controladores/edit.php">
        <input type="hidden" name="Id" value="">
        Nombre<br>
        <input type="text" name="Nombre" required autocomplete="off" placeholder="Nombre producto"><br><br>
        Tipo de producto <br>
        <input type="text" name="TipoProducto" required autocomplete="off" placeholder="Tipo producto"><br><br>
        Descripción <br>
        <input type="text" name="Descripcion" required autocomplete="off" placeholder="Descripción del producto"><br><br>
        Precio <br>
        <input type="number" name="Precio" required autocomplete="off" placeholder="Precio producto"><br><br>
        Catidad disponible <br>
        <input type="number" name="Cantidad" required autocomplete="off" placeholder="Cantidad disponible"><br><br>
        Estado <br>
        <select name="Estado" required>
            <option value="Disponible">Disponible</option>
            <option value="No disponible">No disponible</option>
        </select><br><br> <input type="submit" value="Editar producto">
    </form>
</body>

</html>