<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>CRUD de Mascotas - PetsConnect</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../Public/css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            var checkbox = $('table tbody input[type="checkbox"]');
            $("#selectAll").click(function () {
                if (this.checked) {
                    checkbox.each(function () { this.checked = true; });
                } else {
                    checkbox.each(function () { this.checked = false; });
                }
            });
            checkbox.click(function () {
                if (!this.checked) {
                    $("#selectAll").prop("checked", false);
                }
            });
        });
    </script>
</head>

<body>
    <div class="container-xl">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-sm-6">
                            <h2>Administrar <b>Mascotas</b></h2>
                        </div>
                        <div class="col-sm-6">
                            <a href="#addPetModal" class="btn btn-success" data-toggle="modal">
                                <i class="material-icons">&#xE147;</i> <span>Añadir Mascota</span>
                            </a>
                            <a href="#deleteMultiplePetsModal" class="btn btn-danger" data-toggle="modal">
                                <i class="material-icons">&#xE872;</i> <span>Eliminar Seleccionados</span>
                            </a>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>
                                <span class="custom-checkbox">
                                    <input type="checkbox" id="selectAll">
                                    <label for="selectAll"></label>
                                </span>
                            </th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tipo de edad</th>
                            <th>Edad</th>
                            <th>Sexo</th>
                            <th>Imagen</th>
                            <th>ID tipo mascota</th>
                            <th>NIT Fundación</th>
                            <th># de serie vacuna</th>
                            <th>Acciones</th> <!-- Nueva columna de acciones -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>1</td>
                            <td>Firulais</td>
                            <td>Meses</td>
                            <td>2</td>
                            <td>Hembra</td>
                            <td><img src="img/firulais.jpg" alt="Firulais" width="100"></td>
                            <td>1</td>
                            <td>123456789</td>
                            <td>VAX123456</td>
                            <td>
                                <!-- Botones de editar y eliminar para cada mascota -->
                                <a href="#editPetModal" class="edit" data-toggle="modal">
                                    <i class="material-icons">&#xE254;</i>
                                </a>
                                <a href="#deletePetModal" class="delete" data-toggle="modal">
                                    <i class="material-icons">&#xE872;</i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>2</td>
                            <td>Pelusa</td>
                            <td>Años</td>
                            <td>5</td>
                            <td>Macho</td>
                            <td><img src="img/pelusa.jpg" alt="Pelusa" width="100"></td>
                            <td>2</td>
                            <td>987654321</td>
                            <td>VAX987654</td>
                            <td>
                                <!-- Botones de editar y eliminar para cada mascota -->
                                <a href="#editPetModal" class="edit" data-toggle="modal">
                                    <i class="material-icons">&#xE254;</i>
                                </a>
                                <a href="#deletePetModal" class="delete" data-toggle="modal">
                                    <i class="material-icons">&#xE872;</i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>3</td>
                            <td>Rocky</td>
                            <td>Meses</td>
                            <td>8</td>
                            <td>Hembra</td>
                            <td><img src="img/rocky.jpg" alt="Rocky" width="100"></td>
                            <td>1</td>
                            <td>192837465</td>
                            <td>VAX564738</td>
                            <td>
                                <!-- Botones de editar y eliminar para cada mascota -->
                                <a href="#editPetModal" class="edit" data-toggle="modal">
                                    <i class="material-icons">&#xE254;</i>
                                </a>
                                <a href="#deletePetModal" class="delete" data-toggle="modal">
                                    <i class="material-icons">&#xE872;</i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Mascota -->
    <div id="addPetModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4 class="modal-title">Añadir Mascota</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>ID</label>
                            <input type="number" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Tipo edad</label>
                            <select class="form-control" required>
                                <option>Años</option>
                                <option>Meses</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Edad</label>
                            <input type="number" class="form-control" min ="0" required>
                        </div>
                        <div class="form-group">
                            <label>Sexo</label>
                            <select class="form-control" required>
                                <option>Macho</option>
                                <option>Hembra</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image">Imagen</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        </div>
                        <div class="form-group">
                            <label for="petType">Tipo de Mascota</label>
                            <select class="form-control" id="petType" name="petType" onchange="updatePetTypeId()">
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
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                        <input type="submit" class="btn btn-success" value="Guardar">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar Mascota -->
    <div id="deletePetModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4 class="modal-title">Eliminar Mascota</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar esta mascota?</p>
                        <p class="text-warning"><small>Esta acción no se puede deshacer.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                        <input type="submit" class="btn btn-danger" value="Eliminar">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar Mascotas Seleccionadas -->
    <div id="deleteMultiplePetsModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4 class="modal-title">Eliminar Mascotas Seleccionadas</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar las mascotas seleccionadas?</p>
                        <p class="text-warning"><small>Esta acción no se puede deshacer.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                        <input type="submit" class="btn btn-danger" value="Eliminar Seleccionados">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function updatePetTypeId() {
            var petTypeSelect = document.getElementById("petType");
            var petTypeIdField = document.getElementById("petTypeId");
    
            // Obtener el valor seleccionado del desplegable
            var selectedValue = petTypeSelect.value;
    
            // Asignar el valor seleccionado al campo oculto (ID de mascota)
            petTypeIdField.value = selectedValue;
        }
    </script>    
</body>

</html>