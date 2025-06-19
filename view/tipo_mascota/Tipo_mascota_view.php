<?php

require_once("../../Model/tipo_mascota/TipoMascotaModel.php");
$Modelo = new TipoMascota();

?>


    <table id="tipo_mascota_tabla">
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
                echo "<tr><td colspan='8'>No hay tipo mascotas registradas.</td></tr>";
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

            <form action="controller/tipo_mascota/add.php" method="POST" class="form-modal">

                <label for="especie">Especie:</label>
                <input class="input-modal" type="text" id="especie" name="especie" required>

                <label for="raza">Raza:</label>
                <input class="input-modal" type="text" id="raza" name="raza" required>

                <button class="btn-añadir" type="submit">Guardar</button>
            </form>

        </div>
    </div>
