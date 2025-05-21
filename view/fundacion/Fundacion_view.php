<?php
//VALIDAR SESIÓN//
require_once("../Modelo/FundacionModel.php");
$Modelo = new Fundacion();

if (isset($_POST['btnregistrar'])) {
    $nit_fundacion = $_POST['nit_fundacion'];
    $id_usuario = $_POST['id_usuario'];
    $id_perfil = $_POST['id_perfil'];

    $Modelo->add($nit_fundacion, $id_usuario, $id_perfil);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD_Fundacion</title>
</head>

<body>
    <div class="crud">
        <h2 class="titulo">CRUD de Fundaciones</h2>

        <a class="btn-añadir" id="openModal">
            <i class=" uil uil-plus-circle"></i> <span>Añadir Fundación</span>
        </a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nit Fundación</th>
                <th>id_usuario</th>
                <th>id_perfil</th>
                <th>Acciones</th>
            </tr>

        </thead>
        <tbody>
            <?php
            $Fundacion = $Modelo->getFundacion();
            if ($Fundacion !== null) {
                foreach ($Fundacion as $Fundacion) {
            ?>
                    <tr>
                        <td><?php echo $Fundacion['nit_fundacion']; ?></td>
                        <td><?php echo $Fundacion['id_usuario']; ?></td>
                        <td><?php echo $Fundacion['id_perfil']; ?></td>
                        <td>
                            <a class="edit">
                                <i class=" uil uil-pen" style="cursor: pointer;"></i>
                            </a>
                            <a  class="delete">
                                <i class="uil uil-trash-alt" style="cursor: pointer;"></i>
                            </a>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='8'>No hay tipo $Fundacion registrados.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- ================================= HTML DEL FORMULARIO DE REGISTRO  ======================================= -->
    <!----------- MODAL DEL BOTON DE AÑADIR REGISTRO -------------->

    <div id="modal-fundacion" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="titulo-modal">¡ Registro Nueva Fundación !</h2>

            <!-------------- FORMULARIO VACUNAS ------->

            <form action="../Controlador/add.php" method="POST" class="form-modal">

                <label for="nit_fundacion">Nit Fundación:</label>
                <input class="input-modal" type="text" id="nit_fundacion" name="nit_fundacion" required>

                <label for="id_usuario">ID Usuario:</label>
                <input class="input-modal" type="text" id="id_usuario" name="id_usuario" required>

                <label for="id_perfil">ID Perfil:</label>
                <input class="input-modal" type="text" id="id_perfil" name="id_perfil" required>

                <button class="btn-añadir" type="submit">Guardar</button>
            </form>

        </div>
    </div>
</body>

</html>