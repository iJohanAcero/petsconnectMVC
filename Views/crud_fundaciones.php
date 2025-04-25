<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD fundaciones</title>
</head>

<body>
    <div class="crud">
        <h2 class="titulo">CRUD de Fundaciones</h2>

        <a class="btn-añadir" id="openModal">
            <i class=" uil uil-plus-circle"></i> <span>Añadir tipo de mascota</span>
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>NIT</th>
                <th>ID Registro</th>
                <th>ID Usuario</th>
                <th>ID Perfil</th>
                <th>Acciones</th>

            </tr>

            <tr>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>
                    <a class="edit">
                        <i class=" uil uil-pen" style="cursor: pointer;"></i>
                    </a>
                    <a class="delete">
                        <i class="uil uil-trash-alt" style="cursor: pointer;"></i>
                    </a>
                </td>
            </tr>
        </thead>

    </table>

    <div id="modal-fundaciones" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="titulo-modal">¡ Registro Nueva Fundación !</h2>


            <form method="post" class="form-modal" action="procesar_fundaciones.php">

                <label for="nombre">Nombre:</label>
                <input type="text" class="input-modal" name="nombre" required>

                <label for="tipo_producto">Tipo de producto:</label>
                <input type="text" class="input-modal" name="tipo_producto" required>

                <label for="descripcion">Descripción:</label>
                <input type="text" class="input-modal" name="descripcion" required>

                <label for="precio">Precio:</label>
                <input type="number" class="input-modal" name="precio" required>

                <label for="cantidad">Cantidad:</label>
                <input type="number" class="input-modal" name="cantidad" required>

                <button class="btn-añadir" type="submit">Guardar</button>
            </form>
        </div>
    </div>
</body>

</html>