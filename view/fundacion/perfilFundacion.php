<?php
require_once(__DIR__ . '/../../controller/perfil/PerfilController.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();

    // No cachear esta página
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Fundacion</title>
    <link rel="stylesheet" href="../../Public/css/style.css">
</head>

<body>
    <section id="about-section" class="pt-5 pb-5">
        <div class="custom-wrapper">
            <div class="container-fluid px-5 wrapabout">
                <div class="red"></div>
                <div class="row">
                    <div class="col-lg-6 align-items-center justify-content-center d-flex mb-5 mb-lg-0">
                        <div class="blockabout">
                            <div class="blockabout-inner text-center text-sm-start">
                                <div class="title-big pb-3 mb-3">
                                    <!-- Nombre -->
                                    <h3 class="card-title mb-2"><?php echo htmlspecialchars($perfil['nombre']); ?></h3>
                                </div>
                                <!-- Descripción -->
                                <h6 class="text-muted">Descripción</h6>
                                <p class="description-p text-muted pe-0 pe-lg-0">
                                    <?php echo htmlspecialchars($perfil['descripcion']); ?>
                                </p>
                                <h6 class="text-primary pt-5">Mascotas mas buscadas</h6>
                                <p class="text-muted mb-3">
                                    <?php echo htmlspecialchars($perfil['preferencia']); ?>
                                </p>
                                <div class="sosmed-horizontal pt-3 pb-3">
                                    <a href="#"><i class="uil uil-facebook-f"></i></a>
                                    <a href="#"><i class="uil uil-instagram-alt"></i></a>
                                    <a href="#"><i class="uil uil-twitter"></i></a>
                                </div>

                                <button class="btn rey-btn mt-3 btn-editar-perfilFundacion" data-id="<?php echo htmlspecialchars($perfil['id_usuario']); ?>">
                                    <i class="uil uil-pen">Editar perfil</i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-5 mt-lg-0 align-items-center justify-content-center d-flex">
                        <figure class="perfil-img-container">
                            <!-- Foto de Perfil -->
                            <?php

                            $nombreImagen = !empty($perfil['imagen']) ? $perfil['imagen'] : 'default.jpg';
                            $rutaImagen = "/petsconnectMVC/Public/images/perfil/" . htmlspecialchars($nombreImagen);
                            ?>

                            <img src="<?= $rutaImagen ?>"
                                alt="Foto de perfil"
                                style="object-fit: cover; max-height: 60vh; "
                                class="img-fluid perfil-img-preview">

                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modal-editar-perfilFundacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sin-limite  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contenido-editar">
                    <!-- Se carga dinámicamente con JS -->
                </div>
            </div>

            <div class="modal fade" id="modal-editar-perfilFundacion" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Perfil</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="contenido-editar">
                            <!-- Se carga dinámicamente con JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../Public/js/main.js"></script>
</body>

</html>