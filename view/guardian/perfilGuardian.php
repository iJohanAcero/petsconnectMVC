<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Perfil\Perfil;


if (session_status() === PHP_SESSION_NONE) {
    session_start();

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

$perfil = new Perfil();

$id_usuario = $_SESSION['user']['id_usuario'] ?? null;

$perfil = $perfil->getPerfilPorUsuario($id_usuario);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil guardian</title>
    <link rel="stylesheet" href="config::/style.css">
</head>



<body>
    <section id="about-section" class="py-5">
        <div class="container">
            <!-- Header del perfil -->
            <div class="row justify-content-center mb-4">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <!-- Perfil principal -->
                            <div class="px-4 pb-4">
                                <div class="row align-items-end" style="margin-top: -60px;">
                                    <!-- Foto de perfil -->
                                    <div class="col-auto">
                                        <div class="position-relative">
                                            <?php
                                            $nombreImagen = !empty($perfil['imagen']) ? $perfil['imagen'] : 'default.jpg';
                                            $rutaImagen = htmlspecialchars($nombreImagen);
                                            ?>
                                            <img src="<?= $rutaImagen ?>"
                                                alt="Foto de perfil"
                                                class="rounded-circle border border-4 border-white shadow"
                                                style="width: 120px; height: 120px; object-fit: cover;">
                                            <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 24px; height: 24px;"></span>
                                        </div>
                                    </div>

                                    <!-- Info básica -->
                                    <div class="">
                                        <div class="mt-3">
                                            <h2 class="mb-1 fw-bold"><?php echo htmlspecialchars($perfil['nombre']); ?><i class="uil uil-user me-1"></i></h2>
                                        </div>
                                    </div>

                                    <!-- Botón editar -->
                                    <div class="col-auto">
                                        <button class="btn btn-outline-primary2 btn-editar-perfilGuardian"
                                            data-id="<?php echo htmlspecialchars($perfil['id_usuario']); ?>">
                                            <i class="uil uil-pen me-2"></i>
                                            Editar perfil
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row g-4">
                        <!-- Información principal -->
                        <div class="col-lg-8">
                            <!-- Descripción -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-3">
                                        <i class="uil uil-info-circle text-primary2 me-2"></i>
                                        Acerca de mí
                                    </h5>
                                    <p class="card-text text-muted lh-lg mb-0">
                                        <?php echo nl2br(htmlspecialchars($perfil['descripcion'])); ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Información de contacto -->
                            <?php if (!empty($perfil['telefono']) || !empty($perfil['direccion']) || !empty($perfil['email'])): ?>
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body p-4">
                                        <h5 class="card-title mb-3">
                                            <i class="uil uil-phone text-primary2 me-2"></i>
                                            Información de contacto
                                        </h5>
                                        <div class="row">
                                            <?php if (!empty($perfil['email'])): ?>
                                                <div class="col-md-6 mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary-subtle rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="uil uil-envelope text-primary2"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">Email</h6>
                                                            <small class="text-muted"><?php echo htmlspecialchars($perfil['email']); ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!empty($perfil['telefono'])): ?>
                                                <div class="col-md-6 mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success-subtle rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="uil uil-phone text-success"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">Teléfono</h6>
                                                            <small class="text-muted"><?php echo htmlspecialchars($perfil['telefono']); ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!empty($perfil['direccion'])): ?>
                                                <div class="col-12">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-info-subtle rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="uil uil-map-marker text-info"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">Dirección</h6>
                                                            <small class="text-muted"><?php echo htmlspecialchars($perfil['direccion']); ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Barra lateral -->
                        <div class="col-lg-4">
                            <!-- Preferencias de adopción -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body p-3">
                                    <h5 class="card-title mb-3">
                                        <i class="uil uil-heart text-primary2 me-2"></i>
                                        Preferencias de adopción
                                    </h5>
                                    <div class="text-center p-4 bg-light rounded">
                                        <div class="mb-3">
                                            <?php
                                            $iconoPreferencia = '';
                                            switch (strtolower($perfil['preferencia'])) {
                                                case 'perros':
                                                    $iconoPreferencia = 'uil uil-favorite';
                                                    break;
                                                case 'gatos':
                                                    $iconoPreferencia = 'uil uil-heart';
                                                    break;
                                                default:
                                                    $iconoPreferencia = 'uil uil-paw';
                                            }
                                            ?>
                                            <i class="<?= $iconoPreferencia ?> fs-1 text-primary2"></i>
                                        </div>
                                        <h6 class="mb-1"><?php echo htmlspecialchars($perfil['preferencia']); ?></h6>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modal-editar-perfilGuardian" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg mt-5">
                <div class="modal-header bg-light border-bottom-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="uil uil-edit-alt text-white"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold">Editar mi perfil</h5>
                            <small class="text-muted">Actualiza tu información personal</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close bg-secondary rounded-circle p-2" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body p-0" id="contenido-editar">
                    <!-- Se carga dinámicamente con JS -->
                    <!-- Loader mientras carga el contenido -->
                    <div class="text-center py-5" id="modal-loader">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3 text-muted">Cargando formulario de edición...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>