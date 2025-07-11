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
    <section id="about-section-modal" class="pt-5 pb-5">
    <div class="custom-wrapper">
        <div class="container-fluid px-5 wrapabout">
            <div class="red"></div>
            <form id="form-editar-perfilFundacion" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($perfil['id_usuario']); ?>">
                <input type="hidden" name="accion" value="editar">

                <div class="row">
                    <!-- Columna de datos -->
                    <div class="col-lg-6 align-items-center justify-content-center d-flex mb-5 mb-lg-0">
                        <div class="blockabout">
                            <div class="blockabout-inner text-center text-sm-start">

                                <!-- Nombre -->
                                <div class="title-big pb-3 mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" name="nombre" id="nombre" value="<?= htmlspecialchars($perfil['nombre']); ?>" required>
                                </div>

                                <!-- Descripción -->
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label text-muted">Descripción <small class="text-secondary d-block">máx. 1000 caracteres</small></label>
                                    <textarea class="form-control" style="width: 30vw;"  name="descripcion" id="descripcion" rows="4" maxlength="1000" required><?= htmlspecialchars($perfil['descripcion']); ?></textarea>
                                </div>

                                <!-- Preferencia -->
                                <div class="mb-3">
                                    <label for="preferencia" class="form-label text-primary pt-3">Mascotas más buscadas</label>
                                    <select name="preferencia" id="preferencia" class="form-select" required>
                                        <option value="Perros" <?= $perfil['preferencia'] == 'Perros' ? 'selected' : '' ?>>Perros</option>
                                        <option value="Gatos" <?= $perfil['preferencia'] == 'Gatos' ? 'selected' : '' ?>>Gatos</option>
                                        <option value="Todos los animales" <?= $perfil['preferencia'] == 'Todos los animales' ? 'selected' : '' ?>>Todos los animales</option>
                                    </select>
                                </div>

                                <!-- Redes sociales (puedes convertir esto en inputs también si deseas) -->
                                <div class="sosmed-horizontal pt-3 pb-3">
                                    <a href="#"><i class="uil uil-facebook-f"></i></a>
                                    <a href="#"><i class="uil uil-instagram-alt"></i></a>
                                    <a href="#"><i class="uil uil-twitter"></i></a>
                                </div>

                                <button type="submit" class="btn rey-btn mt-3">
                                    <i class="uil uil-save"> Guardar cambios</i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Columna de imagen -->
                    <div class="col-lg-6 mt-5 mt-lg-0 align-items-center justify-content-center d-flex">
                        <figure class="perfil-img-container text-center">
                            <?php
                            $nombreImagen = !empty($perfil['imagen']) ? $perfil['imagen'] : 'default.jpg';
                            $rutaImagen = "/petsconnectMVC/Public/images/perfil/" . htmlspecialchars($nombreImagen);
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
                </div>
            </form>
        </div>
    </div>
</section>

</body>

</html>