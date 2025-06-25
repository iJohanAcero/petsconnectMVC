<!-- ================================= HTML DEL FORMULARIO DE REGISTRO ======================================= -->
<div class="modal fade" id="modal-fundacion" tabindex="-1" aria-labelledby="modalFundacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- Añadí modal-lg para más espacio -->
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalFundacionLabel">Registrar Nueva Fundación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <!-- Formulario de fundación -->
                <form id="form-registrar-fundacion">
                    <input type="hidden" name="accion" value="registrar_fundacion">

                    <!-- Sección Representante Legal -->
                    <fieldset class="border p-3 mb-4 rounded">
                        <legend class="w-auto px-2 fs-6">Datos del Representante Legal</legend>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rep_nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="rep_nombre" name="rep_nombre" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="rep_apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="rep_apellido" name="rep_apellido" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rep_contrasena" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="rep_contrasena" name="rep_contrasena" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="rep_email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="rep_email" name="rep_email" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="rep_direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="rep_direccion" name="rep_direccion" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="rep_telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="rep_telefono" name="rep_telefono" required>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Sección Datos de la Fundación -->
                    <fieldset class="border p-3 mb-4 rounded bg-light">
                        <legend class="w-auto px-2 fs-6">Datos de la Fundación</legend>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fund_nombre" class="form-label">Nombre Legal de la Fundación</label>
                                <input type="text" class="form-control" id="fund_nombre" name="fund_nombre" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="fund_nit" class="form-label">NIT de la Fundación</label>
                                <input type="text" class="form-control" id="fund_nit" name="fund_nit" required>
                                <small class="text-muted">Ejemplo: 123456789-0</small>
                            </div>
                        </div>
                    </fieldset>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Registrar Fundación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>