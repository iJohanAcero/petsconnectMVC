<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="../Public/css/.css" />
    

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Vacunación</title>
    
</head>
<body>

    <form action="procesar.php" method="post" class="formulario">
        <label for="id">ID:</label>
        <input type="number" name="id" id="id" required>

        <label for="fecha">Fecha de Vacunación:</label>
        <input type="date" name="fecha" id="fecha" required>

        <label for="nombre_vacuna">Nombre de Vacuna:</label>
        <input type="text" name="nombre_vacuna" id="nombre_vacuna" required>

        <label for="direccion_veterinaria">Dirección Veterinaria:</label>
        <input type="text" name="direccion_veterinaria" id="direccion_veterinaria" required>

        <button type="submit" name="accion" value="modificar">Modificar</button>
        <button type="submit" name="accion" value="eliminar">Eliminar</button>
    </form>

</body>
</html>
