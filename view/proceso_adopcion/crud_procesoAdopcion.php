<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Procesos de adopción</title>

</head>

<body>
    <div class="crud-vacunas">
        <h2 class="titulo">CRUD de procesos de adopción</h2>

        <a class="btn-añadir" id="openModal">
            <i class=" uil uil-plus-circle"></i> <span>Añadir proceso adoptivo</span>
        </a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha de inicio</th>
                <th>Fecha de finalización</th>
                <th>ID guardian FK</th>
                <th>ID fundacion FK</th>
                <th>ID estado adopcionFK</th>
                <th>Acciones</th>

            </tr>

            <tr>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>
                    <a class="edit" ">
                        <i class=" uil uil-pen" style="cursor: pointer;"></i>
                    </a>
                    <a class="delete" "">
                        <i class="uil uil-trash-alt" style="cursor: pointer;"></i>
                    </a>
                </td>
            </tr>
        </thead>

    </table>


    <!-- ================================= HTML DEL FORMULARIO DE REGISTRO  ======================================= -->
<!----------- MODAL DEL BOTON DE AÑADIR REGISTRO -------------->
    <div id="modal-procesos" class="modal" style="display: none;">
        <div class="modal-content">
        <span class="close">&times;</span>
            <h2 class="titulo-modal">¡Registro Nuevo Proceso !</h2>

            <!-------------- FORMULARIO VACUNAS ------->

            <form action="procesar_vacuna.php" method="POST" class="form-modal">
                <label for="id">ID:</label>
                <input class="input-modal" type="text" id="id" name="id" required><br><br>

                <label for="fecha_inicio">Fecha de inicio:</label>
                <input class="input-modal" type="date" id="fehca_inicio" name="fecha_inicio" required><br><br>

                <label for="fecha_finalizacion">Fecha de finalización:</label>
                <input class="input-modal" type="date" id="fecha_finalizacion" name="fecha_finalizacion" required><br><br>

                <label for="id_guardian">ID guardian FK:</label>
                <input class="input-modal" type="text" id="id_guardian" name="id_guardian" required><br><br>

                <label for="id_fundacion">ID fundacion FK:</label>
                <input class="input-modal" type="text" id="id_fundacion" name="id_fundacion" required><br><br>

                <label for="id_proceso">ID estado proceso FK:</label>
                <input class="input-modal" type="text" id="id_proceso" name="id_proceso" required><br><br>

                <button class="btn-añadir" type="submit">Guardar</button>
            </form>


        </div>
    </div>

</body>


</html>