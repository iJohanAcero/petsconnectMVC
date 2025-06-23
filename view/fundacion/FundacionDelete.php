<?php
//////VALIDAR SESIÓN//////////
$Id=$_GET['Id'];
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
    <form method="POST" action="../../controller/fundacion/FundationController.php">
        <input type="hidden" name="Id" value="<?php echo $Id;?>">
        <p>¿Estas seguro que deseas eliminar este producto?</p>
        
        <input type="hidden" name="action" value="delete">
        <input type="submit" value="Eliminar producto">
    </form>
</body>

</html>