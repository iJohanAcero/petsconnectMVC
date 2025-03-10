<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD MASCOTAS</title>
</head>

<body>

    <h2 class="titulo">CRUD MASCOTAS</h2>
    <div>
        <a href="#addProductModal" class="btn-añadir" data-bs-toggle="modal">
            <i class="uil uil-plus-circle"></i> <span>Añadir mascota</span>
        </a>
        <a href="#deleteMultipleProductsModal" class="btn-eliminar" data-bs-toggle="modal">
            <i class="uil uil-trash"></i> <span>Eliminar seleccionados</span>
        </a>
    </div>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Edad</th>
                <th>Sexo</th>
                <th>Imagen</th>
                <th>ID tipo mascota</th>
                <th>NIT fundación</th>
                <th># Serie vacuna</th>
                <th>Acciones</th>

            </tr>

            <tr>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>xx</td>
                <td>
                    <a href="#editProductModal" class="edit" data-bs-toggle="modal">
                        <i class="uil uil-pen"></i>
                    </a>
                    <a href="#deleteProductModal" class="delete" data-bs-toggle="modal">
                        <i class="uil uil-trash-alt"></i>
                    </a>
                </td>
            </tr>
        </thead>

    </table>

    <div id="addProductModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Añadir Mascota</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <di class="modal-body">
                    <form method="post">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">ID:</label>
                            <input type="number" class="form-control" name="id mascota" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_producto" class="form-label">Tipo edad:</label>
                            <select class="form-control" required>
                                <option>Años</option>
                                <option>Meses</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_producto" class="form-label">Edad:</label>
                            <input type="number" class="form-control" name="edad" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Sexo:</label>
                            <select class="form-control" required>
                                <option>Macho</option>
                                <option>Hembra</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Imagen:</label>
                            <input type="file" class="form-control" id="image" name="imagen" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Tipo mascota:</label>
                            <select class="form-control" id="petType" name="id tipo mascota" onchange="updatePetTypeId()">
                                <option value="1">Perro</option>
                                <option value="2">Gato</option>
                            </select>
                        </div>
                        <input type="hidden" id="petTypeId" name="petTypeId" min="0" value="1"> <!-- Campo oculto que almacenará el ID -->                           
                        <div class="form-group">
                            <label>NIT fundación</label>
                            <input type="number" class="form-control" required>
                        </div>        
                        <div class="form-group">
                            <label># de serie vacuna</label>
                            <input type="text" class="form-control" required>
                        </div> 
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary" name="btnregistrar">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>