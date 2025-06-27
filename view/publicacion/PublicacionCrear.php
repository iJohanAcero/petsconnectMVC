<body>
    <div class="container crud-container main-content" style="padding: 40px; max-width: 600px;">
    <div class="mb-4">
        <h2 class="mb-0">Registrar Nueva Publicación</h2>
    </div>
    <form id="form-registrar-publicacion" method="POST" enctype="multipart/form-data" action="ruta_a_tu_controlador.php" class="card p-4 shadow-sm border-0 rounded-4 bg-white">
        <input type="hidden" name="accion" value="registrar">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="mb-3">
            <label for="contenido" class="form-label">Contenido</label>
            <input type="text" class="form-control" id="contenido" name="contenido" required>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen</label>
            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
        </div>
        <div class="mb-3">
            <label for="nit_fundacion" class="form-label">Nit Fundación</label>
            <input type="text" class="form-control" id="nit_fundacion" name="nit_fundacion" required>
        </div>
        <div class="d-flex justify-content-end gap-2">
            <a href="PublicacionView.php" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>
</body>