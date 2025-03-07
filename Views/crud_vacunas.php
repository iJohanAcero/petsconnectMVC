<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Vacunación</title>

</head>

<body>
    <div class="crud-vacunas">
        <h2 class="titulo">CRUD de Vacunación</h2>

        <a class="btn-añadir" id="openModal">
            <i class=" uil uil-plus-circle"></i> <span>Añadir Vacuna</span>
        </a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha de Vacunación</th>
                <th>Nombre de Vacuna</th>
                <th>Dirección Veterinaria</th>
                <th>Acciones</th>

            </tr>

            <tr>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>
                    <a class="edit" ">
                        <i class=" uil uil-pen"></i>
                    </a>
                    <a class="delete" "">
                        <i class="uil uil-trash-alt"></i>
                    </a>
                </td>
            </tr>
        </thead>

    </table>


    <!-- ================================= HTML DEL FORMULARIO DE REGISTRO  ======================================= -->
<!----------- MODAL DEL BOTON DE AÑADIR REGISTRO -------------->
    <div id="modal-vacunas" class="modal" style="display: none;">
        <div class="modal-content">
        <span class="close">&times;</span>
            <h2 class="titulo-modal">¡Registro Nueva Vacuna!</h2>

            <!-------------- FORMULARIO VACUNAS ------->

            <form action="procesar_vacuna.php" method="POST" class="form-modal">
                <label for="id">ID:</label>
                <input class="input-modal" type="text" id="id" name="id" required><br><br>

                <label for="fecha_vacunacion">Fecha de Vacunación:</label>
                <input class="input-modal" type="date" id="fecha_vacunacion" name="fecha_vacunacion" required><br><br>

                <label for="nombre_vacuna">Nombre de Vacuna:</label>
                <input class="input-modal" type="text" id="nombre_vacuna" name="nombre_vacuna" required><br><br>

                <label for="direccion_veterinaria">Dirección Veterinaria:</label>
                <input class="input-modal" type="text" id="direccion_veterinaria" name="direccion_veterinaria" required><br><br>

                <button class="btn-añadir" type="submit">Guardar</button>
            </form>


        </div>
    </div>

</body>


</html>