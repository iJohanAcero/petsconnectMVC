<body>
    <div class="container crud-container main-content" style="padding: 40px; max-width: 600px;">
    <div class="mb-4">
        <h2 class="mb-0">Registrar Nueva Causa</h2>
    </div>
    <form id="form-registrar-causa" method="POST" enctype="multipart/form-data" action="../../controller/causa/CausaController.php" class="card p-4 shadow-sm border-0 rounded-4 bg-white">
        <input type="hidden" name="accion" value="registrar">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
        </div>
        <div class="mb-3">
            <label for="meta" class="form-label">Meta</label>
            <input type="text" class="form-control" id="meta" name="meta" required>
        </div>
        <div class="mb-3">
            <label for="estado_causa" class="form-label">Estado de causa</label>
            <input type="text" class="form-control" id="estado_causa" name="estado_causa" required>
        </div>
        <div class="mb-3">
            <label for="nit_fundacion" class="form-label">NIT Fundación</label>
            <input type="text" class="form-control" id="nit_fundacion" name="nit_fundacion" required>
        </div>
        <div class="mb-3">
            <label for="tipo_causa" class="form-label">Tipo de causa</label>
            <input type="text" class="form-control" id="tipo_causa" name="tipo_causa" required>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen</label>
            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
        </div>
        <div class="d-flex justify-content-end gap-2">
            <a href="CausaView.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>
</body>