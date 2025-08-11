<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Model\Causa\Causa;

$Modelo = new Causa();

// Validación: si no se pasa el ID, se muestra un mensaje y se detiene la ejecución
if (!isset($_GET['id'])) {
    echo "ID de causa no especificado.";
    exit;
}

$id = $_GET['id']; // Ojo: era `$Id`, pero luego se usa `$id` en getId. Uniformamos.
$causa = $Modelo->getId($id);

// Si no se encuentra el Causa con ese ID, se avisa y se detiene
if (!$causa || empty($causa)) {
    echo "Causa no encontrada.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Actualizar causa</title>
</head>

<body>
    <!-- Contenedor principal de Bootstrap -->
    <div class="container crud-container main-content" style="padding: 40px; max-width: 600px;">
        <!-- Formulario con clases de Bootstrap -->
        <form id="form-editar-causa" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm border-0 rounded-4 bg-white">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="id_causa" value="<?= htmlspecialchars($causa['id_causa']); ?>">
            <div class="mb-3">
                <label for="id_causa" class="form-label">ID Causa</label>
                <input type="text" class="form-control" id="id_causa" name="id_causa" value="<?= htmlspecialchars($causa['id_causa']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($causa['nombre']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?= htmlspecialchars($causa['descripcion']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="meta" class="form-label">Meta</label>
                <input type="text" class="form-control" id="meta" name="meta" value="<?= htmlspecialchars($causa['meta']); ?>" required readonly>
                <small class="text-muted">La meta no se puede modificar</small>
            </div>
            <div class="mb-3">
                <label for="estado_causa" class="form-label">Estado de causa</label>
                <select class="form-select" id="estado_causa" name="estado_causa" required>
                    <option value="">Selecciona estado</option>
                    <option value="activa">Activa</option>
                    <option value="en pausa">En pausa</option>
                    <option value="cumplida">Cumplida</option>
                    <option value="cancelada">Cancelada</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha_creacion" class="form-label">Fecha de creación</label>
                <input type="text" class="form-control" id="fecha_creacion" name="fecha_creacion" value="<?= htmlspecialchars($causa['fecha_creacion']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="nit_fundacion" class="form-label">NIT Fundacion</label>
                <input type="text" class="form-control" id="nit_fundacion" name="nit_fundacion" value="<?= htmlspecialchars($causa['nit_fundacion']); ?>" required readonly>
                <small class="text-muted">El NIT no se puede modificar</small>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0 align-items-center justify-content-center d-flex">
                <figure class="causa-img-container text-center">
                    <?php
                    $nombreImagen = !empty($causa['imagen_url']) ? $causa['imagen_url'] : 'default.jpg';
                    $rutaImagen = "/petsconnectMVC/Public/images/causa/" . htmlspecialchars($nombreImagen);
                    ?>
                    <img id="preview-imagen"
                        src="<?= $rutaImagen ?>"
                        alt="Foto de perfil"
                        style="object-fit: cover; max-height: 60vh;"
                        class="img-fluid perfil-img-preview">

                    <div class="mt-3">
                        <label for="input-imagen" class="form-label">Actualizar imagen</label>
                        <input type="file" name="imagen" id="input-imagen" class="form-control" accept="image/*">
                    </div>
                </figure>
            </div>
            <div class="mb-3">
                <label for="tipo_causa" class="form-label">Tipo de causa</label>
                <input type="text" class="form-control" id="tipo_causa" name="tipo_causa" value="<?= htmlspecialchars($causa['tipo_causa']); ?>" required readonly>
                <small class="text-muted">El tipo de causa no se puede modificar</small>
            </div>
            <div class="d-flex justify-content-end gap-2">
                <!-- Enlace para regresar -->
                <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal" aria-label="Close">← Volver a la lista</button>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</body>

</html>