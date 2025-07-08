<?php
require_once (__DIR__ . '/../../controller/perfil/PerfilController.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();

    // No cachear esta página
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}
?>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body text-center">

                        <!-- Foto de Perfil -->
                        <img src="Public/images/perfil/perfil2.jpg"
                            class="rounded-circle mb-3 img-fluid"
                            alt="Foto de perfil"
                            style="width: 150px; height: 150px; object-fit: cover;">

                        <!-- Nombre -->
                        <h3 class="card-title mb-2"><?php echo htmlspecialchars($perfil['nombre']); ?></h3>

                        <!-- Descripción -->
                        <p class="text-muted mb-3">
                            <?php echo htmlspecialchars($perfil['descripcion']); ?>
                        </p>

                        <!-- Preferencia de Mascotas -->
                        <div class="mb-3">
                            <h6 class="text-primary">Preferencia de mascotas</h6>
                            <p class="text-muted mb-3">
                                <?php echo htmlspecialchars($perfil['preferencia']); ?> 
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>