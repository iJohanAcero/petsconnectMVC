<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Perfil\Perfil;

if (session_status() === PHP_SESSION_NONE) {
    session_start();

    // No cachear esta página
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

$perfilModel = new Perfil();

$id_usuario = $_SESSION['user']['id_usuario'] ?? null;

$perfilData = $perfilModel->getPerfilPorUsuario($id_usuario);
$mascotas = $perfilModel->mascotasFundacion($id_usuario);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body class=" bg-light">
    <!-- Header Section -->
    <section class="py-4 bg-white">
        <div class="container-fluid px-3" style="width: 70vw;">
            <!-- Información Principal -->
            <div class="row align-items-center mb-4">
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    <?php

                    $nombreImagen = !empty($perfilData['imagen']) ? $perfilData['imagen'] : 'default.jpg';
                    $rutaImagen = htmlspecialchars($nombreImagen);
                    ?>
                    <img src="<?= $rutaImagen ?>"
                        alt="Logo de la fundación"
                        class="rounded-circle border border-2 border-light shadow"
                        style="width: 100px; height: 100px; object-fit: cover;">
                </div>
                <div class="col-md-7">
                    <h1 class="h3 mb-2"><?php echo htmlspecialchars($perfilData['nombre']); ?></h1>
                    <div class="d-flex align-items-center text-muted small">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        <span><?php echo htmlspecialchars($perfilData['direccion']); ?></span>
                    </div>
                </div>
                <div class="col-md-3 text-md-end">
                    <div class="d-flex flex-column flex-md-row gap-2">
                        <?php
                        // Verificar si el usuario logueado es el dueño del perfil
                        $usuario_logueado = $_SESSION['user']['id_usuario'] ?? null;
                        $dueño_perfil = $perfilData['id_usuario'] ?? null;

                        if ($usuario_logueado && $usuario_logueado == $dueño_perfil): ?>
                            <button class="btn btn-outline-primary2 btn-sm btn-editar-perfilFundacion"
                                data-id="<?php echo htmlspecialchars($perfilData['id_usuario']); ?>">
                                <i class="fas fa-edit me-1"></i>
                                Editar
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Información Detallada -->
    <section class="py-4">
        <div class="container-fluid " style="width: 60vw;">
            <div class="row">
                <!-- Columna Principal -->
                <div class="col-lg-8 mb-4">
                    <!-- Sobre Nosotros -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-3">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-heart text-primary me-2"></i>
                                Sobre nosotros
                            </h5>
                            <p class="text-muted">
                                <?php echo htmlspecialchars($perfilData['descripcion']); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Preferencias de Mascotas -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-3">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-star text-warning me-2"></i>
                                Especialidades
                            </h5>
                            <p class="text-muted">
                                <?php echo htmlspecialchars($perfilData['preferencia']); ?>
                            </p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-paw text-primary me-2"></i>
                                    Nuestras mascotas
                                </h5>
                            </div>

                            <!-- Grid de mascotas -->
                            <div class="row g-3" id="mascotas-container">
                                <?php if (!empty($mascotas)): ?>
                                    <?php foreach ($mascotas as $mascota): ?>
                                        <div class="col-6 col-md-4 col-lg-3">
                                            <div class="card h-100 border-0 shadow-sm">
                                                <img src="<?= htmlspecialchars($mascota['imagen']) ?>"
                                                    class="card-img-top"
                                                    alt="<?= htmlspecialchars($mascota['nombre']) ?>"
                                                    style="height:180px;object-fit:cover;">
                                                <div class="card-body text-center p-2">
                                                    <h6 class="mb-0"><?= htmlspecialchars($mascota['nombre']) ?></h6>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="col-12 text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-paw fa-2x mb-3"></i>
                                            <p>Las mascotas aparecerán aquí</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Información de Contacto -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-3">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-address-book text-primary me-2"></i>
                                Contacto
                            </h5>
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-envelope text-muted me-2"></i>
                                    <small class="text-muted">Email</small>
                                </div>
                                <a href="mailto:<?php echo htmlspecialchars($perfilData['email']); ?>" class="text-decoration-none small">
                                    <?php echo htmlspecialchars($perfilData['email']); ?>
                                </a>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-phone text-muted me-2"></i>
                                    <small class="text-muted">Teléfono</small>
                                </div>
                                <a href="tel:<?php echo htmlspecialchars($perfilData['telefono']); ?>" class="text-decoration-none small">
                                    <?php echo htmlspecialchars($perfilData['telefono']); ?>
                                </a>
                            </div>
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                    <small class="text-muted">Ubicación</small>
                                </div>
                                <span class="text-muted small"><?php echo htmlspecialchars($perfilData['direccion']); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Redes Sociales -->
                    <?php if (isset($perfilData['redes_sociales']) && !empty($perfilData['redes_sociales'])): ?>
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-3">
                                    <i class="fas fa-share-alt text-primary me-2"></i>
                                    Síguenos
                                </h5>
                                <div class="d-flex gap-2">
                                    <?php foreach ($perfilData['redes_sociales'] as $red): ?>
                                        <?php
                                        // Definir clases y iconos según el tipo de red
                                        $claseBoton = '';
                                        $icono = '';
                                        switch ($red['tipo_red']) {
                                            case 'facebook':
                                                $claseBoton = 'btn-outline-primary';
                                                $icono = 'fab fa-facebook-f';
                                                break;
                                            case 'instagram':
                                                $claseBoton = 'btn-outline-danger';
                                                $icono = 'fab fa-instagram';
                                                break;
                                            case 'pagina_web':
                                                $claseBoton = 'btn-outline-info';
                                                $icono = 'fas fa-globe';
                                                break;
                                            default:
                                                $claseBoton = 'btn-outline-secondary';
                                                $icono = 'fas fa-link';
                                        }
                                        ?>
                                        <a href="<?= htmlspecialchars($red['url_red']) ?>"
                                            target="_blank"
                                            class="btn <?= $claseBoton ?> btn-sm flex-fill text-center"
                                            title="<?= ucfirst($red['tipo_red']) ?>">
                                            <i class="<?= $icono ?>"></i>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal para editar perfil -->
    <div class="modal fade" id="modal-editar-perfilFundacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered pt-5">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>
                        Editar Perfil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contenido-editar">
                    <!-- Se carga dinámicamente con JS -->
                </div>
            </div>
        </div>
    </div>

</body>

</html>