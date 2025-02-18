<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">


    <link rel="stylesheet" href="../Public/css/style.css">
    <link rel="stylesheet" href="../Public/css/crud.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css"> <!-- Llamamos a la librería de iconos --> 

</head>
<body>
    <nav>
        <div class="container">

            <h1 class="nombre">PetsConnect</h1>

            <div class="barra-buscador">
                <i class="uil uil-search"></i>
                <input type="search" placeholder="Busca en publicaciones, perfiles o intereses...">
            </div>

            <div  class="crear" >
                <label class="btn btn-primario" for="crear-post">Cerrar Sesión</label>
            </div>

            <div class="temas">
                <button class="tema" id="cambio-tema">
                    <i class="uil uil-moon"></i>
                    <i class="uil uil-brightness"></i>
                </button>
            </div>
        
        </div>
    </nav>

<!--============================================================MAIN=============================================-->
<main>
    <div class="container">
<!--==================================IZQUIERDA================================================-->
        <div class="izquierda">
            <a class="perfil">
                <div class="foto-perfil">
                    <img src="../Public/images/perfil2.jpg">
                </div>
                <div class="hundle">
                    <h4> Johan Acero</h4>
                    <p class="text-suave">
                        @Acero24
                    </p>
                </div>
            </a>
                    
<!------------------------------BARRA LATERAL - SIDE BAR-------------------------->
            <div class="sidebar">
                <a class="menu-item activo" href="feedback_admin.php">
                    <span><i class="uil uil-house-user"></i></span> <h3>Inicio</h3>
                </a>
                
                <a class="menu-item">
                    <span><i class="uil uil-user-circle"></i></span> <h3>Perfil</h3>
                </a>

                <a class="menu-item" id="guardianes-box">
                    <span><i class="uil uil-smile-squint-wink"></i></span> <h3>Guardianes</h3>
                </a>

                <a class="menu-item">
                    <span><i class="uil uil-shop"></i></span> <h3>Fundaciones</h3>
                </a>

                <a class="menu-item">
                    <span><i class="uil uil-credit-card"></i></span> <h3>Donaciones</h3>
                </a>

                <a class="menu-item" id="popup-mascotas">
                    <span><i class="uil uil-heartbeat"></i></span> <h3>Mascotas</h3>

<!---------------------------------------------------POPUP DE MASCOTAS --------------------------------------------->
                    <div class="mascotas-popup">
                        <div class="popup-item">
                            <div class="foto-perfil">
                                <img src="../Public/images/perro.JPG">
                            </div>
                            <div class="popup-body">
                                <b class="text-suave">Perros</b>
                            </div>
                        </div>

                        <div class="popup-item">
                            <div class="foto-perfil">
                                <img src="../Public/images/gato.jpg">
                            </div>
                            <div class="popup-body">
                                <b class="text-suave">Gatos</b>
                            </div>
                        </div>
                        <div class="popup-item">
                            <div class="foto-perfil">
                                <img src="../Public/images/todo-mascotas.jpg">
                            </div>
                            <div class="popup-body">
                                <b class="text-suave">Todos</b>
                            </div>
                        </div>
                    </div>
                    <!---------------------FIN DEL POPUP DE MASCOTAS----------------------------->
                </a>

                <a class="menu-item">
                    <span><i class="uil uil-constructor"></i></span> <h3>CRUD Admin</h3>
                </a>

                <a class="menu-item">
                    <span><i class="uil uil-setting"></i></span> <h3>Configuración</h3>
                </a>
            </div>
            <!------------------------FIN DEL SIDEBAR---------------------->
    <label for="crear-publicacion" class="btn btn-primario">Crear publicación</label>
        </div>
    <!------------------------------FIN DEL LADO IZQUIERDO-------------------------->
    
<!--====================================================MEDIO=======================================================-->
                <div class="medio">

                <div class="container-xl">
	<div class="table-responsive">
		<div class="table-wrapper">
			<div class="table-title">
				<div class="row">
					<div class="col-sm-6">
						<h2>CRUD <b>Vacunas</b></h2>
					</div>
					<div class="col-sm-6">
						<a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Agregar una vacuna</span></a>
						<a href="#deleteEmployeeModal" class="btn btn-danger" data-toggle="modal"><i class="material-icons">&#xE15C;</i> <span>Eliminar</span></a>						
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
						<th>Fecha de vacunación</th>
						<th>Nombre de vacuna</th>
						<th>Dirección_veterinaria</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<span class="custom-checkbox">
								<input type="checkbox" id="checkbox1" name="options[]" value="1">
								<label for="checkbox1"></label>
							</span>
						</td>
						<td>01</td>
						<td>2024-02-10</td>
						<td>Rabia Canina</td>
						<td>Clínica Veterinaria San Francisco, Av. Libertad 456, Ciudad</td>
						<td>
							<a href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
							<a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
						</td>
					</tr>
					<tr>
						<td>
							<span class="custom-checkbox">
								<input type="checkbox" id="checkbox2" name="options[]" value="1">
								<label for="checkbox2"></label>
							</span>
						</td>
						<td>02</td>
						<td>2023-12-15</td>
						<td>Moquillo y Parvovirus</td>
						<td>Centro VetCare, Calle Siempre Viva 742, Ciudad</td>
						<td>
							<a href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
							<a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
						</td>
					</tr>
					<tr>
						<td>
							<span class="custom-checkbox">
								<input type="checkbox" id="checkbox3" name="options[]" value="1">
								<label for="checkbox3"></label>
							</span>
						</td>
						<td>03</td>
						<td>2024-01-05</td>
						<td>Leptospirosis Canina</td>
						<td>Hospital Mascotas Felices, Blvd. Central 123, Ciudad</td>
						<td>
							<a href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
							<a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
						</td>
					</tr>
					<tr>
						<td>
							<span class="custom-checkbox">
								<input type="checkbox" id="checkbox4" name="options[]" value="1">
								<label for="checkbox4"></label>
							</span>
						</td>
						<td>04</td>
						<td>2023-11-20</td>
						<td>Hepatitis Infecciosa</td>
						<td>Veterinaria Vida Animal, Calle Luna 908, Ciudad</td>
						<td>
							<a href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
							<a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
						</td>
					</tr>					
					<tr>
						<td>
							<span class="custom-checkbox">
								<input type="checkbox" id="checkbox5" name="options[]" value="1">
								<label for="checkbox5"></label>
							</span>
						</td>
						<td>05</td>
						<td>2024-02-01</td>
						<td>Coronavirus Canino</td>
						<td>PetCare Integral, Av. Los Pinos 321, Ciudad</td>
						<td>
							<a href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
							<a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
						</td>
					</tr> 
				</tbody>
			</table>
			<div class="clearfix">
				<div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
				<ul class="pagination">
					<li class="page-item disabled"><a href="#">Previous</a></li>
					<li class="page-item"><a href="#" class="page-link">1</a></li>
					<li class="page-item"><a href="#" class="page-link">2</a></li>
					<li class="page-item active"><a href="#" class="page-link">3</a></li>
					<li class="page-item"><a href="#" class="page-link">4</a></li>
					<li class="page-item"><a href="#" class="page-link">5</a></li>
					<li class="page-item"><a href="#" class="page-link">Next</a></li>
				</ul>
			</div>
		</div>
	</div>        
</div>
<!-- Edit Modal HTML -->
<div id="addEmployeeModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form>
				<div class="modal-header">						
					<h4 class="modal-title">Agregar Vacuna</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">					
					<div class="form-group">
						<label>ID</label>
						<input type="text" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Fecha de vacunación</label>
						<input type="email" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Nombre de vacuna</label>
						<textarea class="form-control" required></textarea>
					</div>
					<div class="form-group">
						<label>Dirección veterinaria</label>
						<input type="text" class="form-control" required>
					</div>					
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
					<input type="submit" class="btn btn-success" value="Add">
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Edit Modal HTML -->
<div id="editEmployeeModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form>
				<div class="modal-header">						
					<h4 class="modal-title">Modificar vacuna</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">					
					<div class="form-group">
						<label>ID</label>
						<input type="text" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Fecha de vacunación</label>
						<input type="email" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Nombre de vacuna</label>
						<textarea class="form-control" required></textarea>
					</div>
					<div class="form-group">
						<label>Dirección veterinaria</label>
						<input type="text" class="form-control" required>
					</div>					
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
					<input type="submit" class="btn btn-info" value="Save">
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Delete Modal HTML -->
<div id="deleteEmployeeModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form>
				<div class="modal-header">						
					<h4 class="modal-title">Eliminar vacuna</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">					
					<p>¿Estas seguro de eliminar este registro?</p>
					<p class="text-warning"><small>Esta acción no puede ser rehecha</small></p>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
					<input type="submit" class="btn btn-danger" value="Delete">
				</div>
			</form>
		</div>
	</div>
</div>



                </div>
<!--==============================================DERECHA===========================================-->
                <div class="derecha">
                    <!------------------------ OTROS GUARDIANES------------------------>
                    <div class="guardianes">
                        <div class="head">
                            <h4>Otros guardianes</h4><i class="uil uil-users-alt"></i>
                        </div>
                        <!------------------------ BARRA DE BUSCADOR --------------------->
                        <div class="barra-buscador">
                            <i class="uil uil-search"></i>
                            <input  type="search" placeholder="Buscar guardianes" id="guardian-buscador">
                        </div>
                        <!------------------------ CATEGORIA DE GUARDIANES --------------------->
                        <div class="categoria">
                            <a class="enlinea">En Linea</a>
                            <a class="offline">Offline</a>
                        </div>
                        <!------------------------ GUARDIAN EN LINEA--------------------->
                        <div class="guardian-enlinea">
                            <div class="foto-perfil">
                                <img src="../Public/images/perfil.jpg">
                                <div class="enlinea"></div>
                            </div>
                            <div class="guardian-body">
                                <h5>Juank Pera</h5>
                                <p class="text-suave">Me encantan los gatos uwu</p>
                                <div class="accion">
                                    <button class="btn btn-primario">
                                        Ver perfil
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="guardian-enlinea">
                            <div class="foto-perfil">
                                <img src="../Public/images/valen.jpg">
                                <div class="enlinea"></div>
                            </div>
                            <div class="guardian-body">
                                <h5>Valentina Urrego</h5>
                                <p class="text-suave">Me encantan los michis :3</p>
                                <div class="accion">
                                    <button class="btn btn-primario">
                                        Ver perfil
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!------------------------ GUARDIAN OFFLINE--------------------->
                    <div class="guardian-offline">
                                <div class="foto-perfil">
                                    <img src="../Public/images/perfil2.jpg" >
                                    <div class="offline"></div>
                                </div>
                                <div>
                                    <h5>Manuel Moncada</h5>
                                    <p class="text-suave">
                                        Activo hace 2 dias
                                    </p>
                                    <div class="accion">
                                        <button class="btn btn-primario">
                                            Ver perfil
                                        </button>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
    </div>
                <!--==============================================FIN DE LA DERECHA===========================================-->
</main>
<!--==============================================CONFIGURACION DE FONDO===========================================-->

<script src="../Public/js/main.js"></script>
</body>
</html>