    <?php
    //////VALIDAR SESIÓN//////////
    $id=$_GET['id'];
    ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Eliminar productos</title>
    </head>

    <body>
        <h1>Eliminar producto</h1>
        <form method="POST" action="../../controller/producto/ProductoController.php">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="eliminar" value="1">
        <p>¿Estás seguro que deseas eliminar este producto?</p>
        <input type="submit" value="Eliminar producto">
    </form>

    </body>

    </html>