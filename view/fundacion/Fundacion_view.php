<?php
// Validación de sesión si aplica
require_once("../../Model/fundacion/Fundacion.php");

$Modelo = new Fundacion();
$fundaciones = $fundacionModel->getFundacion();

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD_Fundacion</title>
    <!-- Podés linkear tus estilos aquí -->
    <link rel="stylesheet" href="../css/estilos.css"> <!-- opcional -->    
</head>

<body>
    <div class="crud">
        <h2 class="titulo">CRUD de Fundaciones</h2>

        <!-- Botón para abrir el modal -->
        <a class="btn-añadir" id="openModal">
            <i class="uil uil-plus-circle"></i> <span>Añadir Fundación</span>
        </a>
    </div>

    <!-- Tabla con fundaciones ya registradas -->
    <table>
        <thead>
            <tr>
                <th>Nit Fundación</th>
                <th>ID Usuario</th>
                <th>ID Perfil</th>
                <th>Acciones</th>
            </tr>

        </thead>
        <tbody>
            <?php
            $fundaciones = $Modelo->getFundacion();
            if ($fundaciones !== null) {
                foreach ($fundaciones as $fundacion) {
                    ?>
                    <tr>
                        <td><?= $fundacion['nit_fundacion']; ?></td>
                        <td><?= $fundacion['id_usuario']; ?></td>
                        <td><?= $fundacion['id_perfil']; ?></td>
                        <td>
                            <!-- Aquí irían botones de editar o eliminar -->
                            <a class="edit"><i class="uil uil-pen" style="cursor: pointer;"></i></a>
                            <a class="delete"><i class="uil uil-trash-alt" style="cursor: pointer;"></i></a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='8'>No hay fundaciones registradas.</td></tr>";
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

            <!-- Formulario adaptado al procedimiento almacenado -->
            <form action="../../controller/fundacion/add.php" method="POST" class="form-modal">
                <!-- Datos del usuario -->
                <label for="nombre">Nombre:</label>
                <input class="input-modal" type="text" id="nombre" name="nombre" required>

                <label for="email">Email:</label>
                <input class="input-modal" type="email" id="email" name="email" required>

                <label for="direccion">Dirección:</label>
                <input class="input-modal" type="text" id="direccion" name="direccion" required>

                <label for="telefono">Teléfono:</label>
                <input class="input-modal" type="text" id="telefono" name="telefono" required>

                <!-- Solo se solicita el NIT -->
                <label for="nit_fundacion">NIT Fundación:</label>
                <input class="input-modal" type="text" id="nit_fundacion" name="nit_fundacion" required>

                <button class="btn-añadir" type="submit">Guardar</button>
            </form>
        </div>
    </div>

    <!-- Aquí poner el script JS para mostrar/ocultar el modal -->
</body>
</html>
