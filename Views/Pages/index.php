<?php
require_once __DIR__ . '/../../Model/mascotasModel.php';

$modelo = new mascotasModel();
$mascotas = $modelo->obtenerMascotas();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../../Public/Css/index.css">
    <title>Mascotas</title>
</head>

<body>
    <h1>Mascotas Registradas</h1>
    <!-- menu de navegacion -->
    <nav class="navbar">
        <a href="index.php">Inicio</a>
        <a href="create.php">Crear mascota</a>
        <a href="../login.php">Salir</a>
    </nav>
    <div class="container">

        <table class="tabla">
            <tr>
                <th>Id_mascota</th>
                <th>Nombre</th>
                <th>Edad_meses</th>
                <th>Sexo</th>
                <th>Imagen</th>
                <th>Id_tipo_mascota</th>
                <th>Nit_fundacion</th>
                <th>Num_serie_vacuna</th>
            </tr>
            <?php if (!empty($mascotas)): ?>
                <?php foreach ($mascotas as $mascota): ?>
                    <tr>
                        <td><?= $mascota['id_mascota']?></td>
                        <td><?= $mascota['nombre']?></td>
                        <td><?= $mascota['edad_meses']?></td>
                        <td><?= $mascota['sexo']?></td>
                        <td><?= $mascota['imagen']?></td>
                        <td><?= $mascota['id_tipo_mascota']?></td>
                        <td><?= $mascota['nit_fundacion']?></td>
                        <td><?= $mascota['num_serie_vacuna']?></td>
                        <td>
                            <a href="edit.php?id=<?=$mascotas['id_mascota']?>" id="edit"><i class="bi bi-pencil-square"></i></a>
                            <a href="../../Controllers/DeleteController.php?id=<?=$mascota['id']?>" id="delete"><i class="bi bi-trash-fill"></i></a>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No hay mascotas registradas</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>

</html>