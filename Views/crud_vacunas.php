<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Vacunación</title>
</head>

<body>

    <h2 class="titulo">CRUD de Vacunación</h2>
    <div>
        <a  class="btn-añadir" id="openAñadir" ">
            <i class="uil uil-plus-circle"></i> <span>Añadir Vacuna</span>
        </a>
        <a  class="btn-eliminar" id="openBorrar">
            <i class="uil uil-trash"></i> <span>Eliminar Seleccionados</span>
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
                    <a href="#editProductModal" class="edit" ">
                        <i class="uil uil-pen"></i>
                    </a>
                    <a href="#deleteProductModal" class="delete" "">
                        <i class="uil uil-trash-alt"></i>
                    </a>
                </td>
            </tr>
        </thead>

    </table>

<!-- ================================= HTML DEL FORMULARIO DE REGISTRO  ======================================= -->
    <div id="addModal" class="modal">
                <h4 class="modal-title">Añadir Vacuna</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="fecha_vacunacion" class="form-label">Fecha Vacunación:</label>
                        <input type="date" class="form-control" name="fecha_vacunacion" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre_vacuna" class="form-label">Nombre de Vacuna:</label>
                        <input type="text" class="form-control" name="nombre_vacuna" required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion_veterinaria" class="form-label">Dirección Veterinaria:</label>
                        <input type="text" class="form-control" name="direccion_veterinaria" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary" name="btnregistrar">Registrar</button>
            </div>
        </div>
    </div>
</div>
</body>

</html>