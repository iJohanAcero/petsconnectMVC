<?php
//VALIDAR SESIÓN//
require_once("../../Model/fundacion/FundacionModel.php");
$Modelo = new Fundacion();

?>

<body>
    <div class="container crud-container main-content" id="crud-container" style="padding: 40px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Gestión de Fundaciones</h2>
            <button id="btn-abrir-modal-fundacion" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Añadir Fundación
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered" id="tabla_fundacion">
                <thead class="table-dark">
                    <tr>
                        <th>nit_fundacion</th>
                        <th>Id usuario</th>
                        <th>Id perfil</th>
                        <th>Acciones</th>
                    </tr>

                </thead>
                <tbody>
                    <?php
                    $Fundacion = $Modelo->getFundacion();
                    if ($Fundacion !== null) {
                        foreach ($Fundacion as $Fundacion) {
                    ?>
                            <tr>
                                <td><?php echo $Fundacion['nit_fundacion']; ?></td>
                                <td><?php echo $Fundacion['id_usuario']; ?></td>
                                <td><?php echo $Fundacion['id_perfil']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning btn-editar-fundacion" data-id="<?php echo $Fundacion['nit_fundacion']; ?>">
                                        <i class="uil uil-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger btn-eliminar-fundacion" data-id="<?php echo $Fundacion['nit_fundacion']; ?>">
                                        <i class="uil uil-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7" class="text-center">No hay Fundaciones registradas</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- ================================= HTML DEL FORMULARIO DE REGISTRO  ======================================= -->
        <div class="modal fade" id="modal-fundacion" tabindex="-1" aria-labelledby="modalFundacionLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFundacionLabel">Registrar Nueva Fundación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Formulario de fundación -->
                        <form id="form-registrar-fundacion">
                            <input type="hidden" name="accion" value="registrar_fundacion">

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>

                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" required>
                            </div>

                            <div class="mb-3">
                                <label for="contrasena" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                            </div>

                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" required>
                            </div>

                            <div class="mb-3">
                                <label for="nit_fundacion" class="form-label">nit_fundacion</label>
                                <input type="text" class="form-control" id="nit_fundacion" name="nit_fundacion" required>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Registrar Fundación</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

</body>

</html>