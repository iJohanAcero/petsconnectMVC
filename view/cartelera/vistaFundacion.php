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

$id_perfil = $_GET['id'] ?? null;

if (!$id_perfil || !is_numeric($id_perfil)) {
    header("Location: /lista-fundaciones.php");
    exit();
}

// Usar el método nuevo que busca por id_perfil
$perfil = $perfilModel->getPerfilPorIdPerfil($id_perfil);

if (!$perfil) {
    echo "Fundación no encontrada";
    exit();
}
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

                    $nombreImagen = !empty($perfil['imagen']) ? $perfil['imagen'] : 'default.jpg';
                    $rutaImagen = "/petsconnectMVC/Public/images/perfil/" . htmlspecialchars($nombreImagen);
                    ?>
                    <img src="<?= $rutaImagen ?>"
                        alt="Logo de la fundación"
                        class="rounded-circle border border-2 border-light shadow"
                        style="width: 100px; height: 100px; object-fit: cover;">
                </div>
                <div class="col-md-7">
                    <h1 class="h3 mb-2"><?php echo htmlspecialchars($perfil['nombre']); ?></h1>
                    <div class="d-flex align-items-center text-muted small">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        <span><?php echo htmlspecialchars($perfil['direccion']); ?></span>
                    </div>
                </div>
                <div class="col-md-3 text-md-end">
                    <div class="d-flex flex-column flex-md-row gap-2">
                        <?php
                        // Verificar si el usuario logueado es el dueño del perfil
                        $usuario_logueado = $_SESSION['user']['id_usuario'] ?? null;
                        $dueño_perfil = $perfil['id_usuario'] ?? null;

                        if ($usuario_logueado && $usuario_logueado == $dueño_perfil): ?>
                            <button class="btn btn-outline-primary2 btn-sm btn-editar-perfilFundacion"
                                data-id="<?php echo htmlspecialchars($perfil['id_usuario']); ?>">
                                <i class="fas fa-edit me-1"></i>
                                Editar
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-3 bg-white mt-2" style="width: 70vw;">
        <div class="container-fluid px-3">
            <div class="row text-center">
                <div class="col-4">
                    <div class="p-2">
                        <h4 class="mb-1 text-primary">0</h4>
                        <p class="text-muted mb-0 small">Mascotas en adopción</p>
                    </div>
                </div>
                <div class="col-4 border-start border-end">
                    <div class="p-2">
                        <h4 class="mb-1 text-success">0</h4>
                        <p class="text-muted mb-0 small">Adopciones exitosas</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2">
                        <h4 class="mb-1 text-info">0</h4>
                        <p class="text-muted mb-0 small">Años de experiencia</p>
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
                                <?php echo htmlspecialchars($perfil['descripcion']); ?>
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
                                <?php echo htmlspecialchars($perfil['preferencia']); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Mascotas en Adopción -->
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-paw text-primary me-2"></i>
                                    Nuestras mascotas
                                </h5>
                                <button class="btn btn-outline-primary2 btn-sm">
                                    Ver todas
                                </button>
                            </div>

                            <!-- Grid de mascotas - Espacio vacío para la lógica -->
                            <div class="row g-3" id="mascotas-container">
                                <!-- Aquí se cargarán las mascotas dinámicamente -->
                                <div class="col-12 text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-paw fa-2x mb-3"></i>
                                        <p>Las mascotas aparecerán aquí</p>
                                    </div>
                                </div>
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
                                <a href="mailto:<?php echo htmlspecialchars($perfil['email']); ?>" class="text-decoration-none small">
                                    <?php echo htmlspecialchars($perfil['email']); ?>
                                </a>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-phone text-muted me-2"></i>
                                    <small class="text-muted">Teléfono</small>
                                </div>
                                <a href="tel:<?php echo htmlspecialchars($perfil['telefono']); ?>" class="text-decoration-none small">
                                    <?php echo htmlspecialchars($perfil['telefono']); ?>
                                </a>
                            </div>
                            <div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                    <small class="text-muted">Ubicación</small>
                                </div>
                                <span class="text-muted small"><?php echo htmlspecialchars($perfil['direccion']); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Redes Sociales -->
                    <?php if (isset($perfil['redes_sociales']) && !empty($perfil['redes_sociales'])): ?>
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-3">
                                    <i class="fas fa-share-alt text-primary me-2"></i>
                                    Síguenos
                                </h5>
                                <div class="d-flex gap-2">
                                    <?php foreach ($perfil['redes_sociales'] as $red): ?>
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