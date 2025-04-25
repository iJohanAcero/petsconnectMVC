<?php
////////////////////VALIDAR SESIÓN//////////////////
require_once("../Modelo/TipoMascotaModel.php");
$Modelo = new TipoMascota();

if (isset($_POST['btnregistrar'])) {
    $especie = $_POST['especie'];
    $raza = $_POST['raza'];
    $Modelo->add($especie, $raza,);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD_Tipo_Mascota</title>
</head>

<body>
    <div class="crud">
        <h2 class="titulo">CRUD de Tipo de Mascota</h2>

        <a class="btn-añadir" id="openModal">
            <i class=" uil uil-plus-circle"></i> <span>Añadir tipo de mascota</span>
        </a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Especie </th>
                <th>Raza</th>
                <th>Acciones</th>
            </tr>

        </thead>
        <tbody>
            <?php
            $Tipo_mascota = $Modelo->getTipo_mascota();
            if ($Tipo_mascota !== null) {
                foreach ($Tipo_mascota as $Tipo_mascota) {
            ?>
                    <tr>
                        <td><?php echo $Tipo_mascota['id_tipo_mascota']; ?></td>
                        <td><?php echo $Tipo_mascota['especie']; ?></td>
                        <td><?php echo $Tipo_mascota['raza']; ?></td>
                        <td>
                            <a href="edit.php?Id=<?php echo $Tipo_mascota['id_Tipo_mascota']; ?>" class="edit">
                                <i class=" uil uil-pen" style="cursor: pointer;"></i>
                            </a>
                            <a href="delete.php?Id=<?php echo $Tipo_mascota['id_Tipo_mascota']; ?>" class="delete">
                                <i class="uil uil-trash-alt" style="cursor: pointer;"></i>
                            </a>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='8'>No hay tipo$Tipo_mascota registrados.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- ================================= HTML DEL FORMULARIO DE REGISTRO  ======================================= -->
    <!----------- MODAL DEL BOTON DE AÑADIR REGISTRO -------------->

    <div id="modal-tipoMascota" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="titulo-modal">¡ Registro Nuevo Tipo de Mascota !</h2>

            <!-------------- FORMULARIO VACUNAS ------->

            <form action="../Controlador/add.php" method="POST" class="form-modal">

                <label for="especie">Especie:</label>
                <input class="input-modal" type="text" id="especie" name="especie" required>

                <label for="raza">Raza:</label>
                <input class="input-modal" type="text" id="raza" name="raza" required>

                <button class="btn-añadir" type="submit">Guardar</button>
            </form>

        </div>
    </div>
</body>

</html>