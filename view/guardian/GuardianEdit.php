<?php
require_once("../../Model/perfil/PerfilModel.php");

$Modelo = new PerfilModel();

// Validación: si no se pasa el ID, se muestra un mensaje y se detiene la ejecución
if (!isset($_GET['id'])) {
    echo "ID de Perfil no especificado.";
    exit;
}

$id = $_GET['id']; // Ojo: era `$Id`, pero luego se usa `$id` en getId. Uniformamos.
$perfil = $Modelo->getPerfilPorUsuario($id);

// Si no se encuentra el perfil con ese ID, se avisa y se detiene
if (!$perfil || empty($perfil)) {
    echo "Perfil no encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Actualizar perfil</title>
</head>

<body>

    <section id="about-section" class="pt-5 pb-5">
        <div class="container wrapabout">
            <div class="red"></div>
            <form id="form-editar-perfilGuardian" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="editar">
                <input type="hidden" name="id" value="<?= htmlspecialchars($perfil['id_usuario']); ?>">

                <div class="row">
                    <div class="col-lg-6 align-items-center justify-content-center d-flex mb-5 mb-lg-0">
                        <div class="blockabout">
                            <div class="blockabout-inner text-center text-sm-start">
                                <div class="title-big pb-3 mb-3">
                                    <!-- Nombre editable -->
                                    <h6 class="text-muted mb-1">Nombre</h6>
                                    <input type="text" class="form-control mb-3" name="nombre" value="<?= htmlspecialchars($perfil['nombre']); ?>" required>
                                </div>

                                <!-- Descripción editable -->
                                <h6 class="text-muted">Descripción</h6>
                                <textarea class="form-control mb-3" name="descripcion" rows="3" required><?= htmlspecialchars($perfil['descripcion']); ?></textarea>

                                <!-- Preferencia editable -->
                                <h6 class="text-primary">Preferencia de mascotas</h6>
                                <select class="form-select mb-3" name="preferencia" required>
                                    <option value="Perros" <?= $perfil['preferencia'] === 'Perros' ? 'selected' : '' ?>>Perros</option>
                                    <option value="Gatos" <?= $perfil['preferencia'] === 'Gatos' ? 'selected' : '' ?>>Gatos</option>
                                    <option value="Todos los animales" <?= $perfil['preferencia'] === 'Todos los animales' ? 'selected' : '' ?>>Todos los animales</option>
                                </select>

                                <!-- Red social ficticia (puedes dejarlo igual o adaptar) -->
                                <div class="sosmed-horizontal pt-3 pb-3">
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-instagram"></i></a>
                                    <a href="#"><i class="fa fa-pinterest"></i></a>
                                </div>

                                <!-- Botón de guardar -->
                                <button type="submit" class="btn rey-btn mt-3">
                                    <i class="uil uil-save">Guardar cambios</i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Imagen de perfil y campo para actualizar -->
                    <div class="col-lg-6 mt-5 mt-lg-0">
                        <figure class="perfil-img-container mx-auto mb-3">
                            <?php
                            $nombreImagen = !empty($perfil['imagen']) ? $perfil['imagen'] : 'default.jpg';
                            $rutaImagen = "/petsconnectMVC/Public/images/perfil/" . htmlspecialchars($nombreImagen);
                            ?>
                            <img id="preview-imagen"
                                src="<?= $rutaImagen ?>"
                                alt="Foto de perfil"
                                class="img-fluid perfil-img-preview">

                            <div class="mt-2" style="max-width: 300px; margin: 0 auto;">
                                <label class="form-label">Actualizar imagen</label>
                                <input type="file" name="imagen" id="input-imagen" class="form-control">
                            </div>
                        </figure>
                    </div>
                </div>
            </form>
        </div>
    </section>


</body>

</html>