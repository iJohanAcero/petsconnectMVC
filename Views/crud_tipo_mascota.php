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

            <tr>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>
                    <a class="edit" ">
                        <i class=" uil uil-pen" style="cursor: pointer;"></i>
                    </a>
                    <a class="delete">
                        <i class="uil uil-trash-alt" style="cursor: pointer;"></i>
                    </a>
                </td>
            </tr>
        </thead>

    </table>

    <!-- ================================= HTML DEL FORMULARIO DE REGISTRO  ======================================= -->
    <!----------- MODAL DEL BOTON DE AÑADIR REGISTRO -------------->

    <div id="modal-tipoMascota" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="titulo-modal">¡ Registro Nuevo Tipo de Mascota !</h2>

                <!-------------- FORMULARIO VACUNAS ------->

                <form  action="procesar_tipoMascota.php" method="POST" class="form-modal">

                    <label for="nombre">ID:</label>
                    <input class="input-modal" type="text" id="id" name="nombre" required>

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