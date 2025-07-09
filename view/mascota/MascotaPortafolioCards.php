<?php
require_once '../../Model/mascota/MascotaModel.php';

$modelo = new Mascota();
$mascotas = $modelo->getMascotas();

if (!is_array($mascotas)) {
    echo "<p class='text-danger'>No se encontraron mascotas disponibles.</p>";
    return;
}

// Filtrar estados: EN ADOPCION, EN TRAMITE, TRANSITO
$mascotasFiltradas = array_filter($mascotas, function ($mascota) {
    $estado = strtoupper($mascota['tipo_estado']);
    return in_array($estado, ['EN ADOPCION', 'EN TRAMITE', 'TRANSITO']);
});

// Agrupar en bloques de 3
$grupos = array_chunk($mascotasFiltradas, 3);

function convertirMesesAEdad($meses) {
    if ($meses < 12) return $meses . " mes(es)";
    $años = floor($meses / 12);
    $resto = $meses % 12;
    return $años . " año(s)" . ($resto > 0 ? " y $resto mes(es)" : "");
}
?>

<?php foreach ($grupos as $index => $grupoMascotas): ?>
    <div class="carousel-item<?= $index === 0 ? ' active' : '' ?>">
        <div class="row g-3">
            <?php foreach ($grupoMascotas as $mascota): ?>
                <?php
                    $edadTexto = convertirMesesAEdad((int)$mascota['edad_meses']);
                ?>
                <div class="col-md-4">
                    <div class="card pet-card"
                        data-pet-id="<?= htmlspecialchars($mascota['id_mascota']) ?>"
                        data-name="<?= htmlspecialchars($mascota['nombre']) ?>"
                        data-age="<?= $edadTexto ?>"
                        data-sexo="<?= htmlspecialchars($mascota['sexo']) ?>"
                        data-especie="<?= htmlspecialchars($mascota['especie']) ?>"
                        data-raza="<?= htmlspecialchars($mascota['raza']) ?>"
                        data-tamaño="<?= htmlspecialchars($mascota['tamaño']) ?>"
                        data-pelaje="<?= htmlspecialchars($mascota['tipo_pelaje']) ?>"
                        data-estado="<?= htmlspecialchars($mascota['tipo_estado']) ?>"
                        data-imagen="../../Public/images/mascotas/<?= htmlspecialchars($mascota['imagen']) ?>"
                    >
                        <img src="../../Public/images/mascotas/<?= htmlspecialchars($mascota['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($mascota['nombre']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($mascota['nombre']) ?></h5>
                            <p class="card-text"><strong>Estado:</strong> <?= htmlspecialchars($mascota['tipo_estado']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>
