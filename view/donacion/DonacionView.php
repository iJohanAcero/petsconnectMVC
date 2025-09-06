<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Donacion\Donacion;
use App\Model\Fundacion\Fundacion;
use App\Config\Roles;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$Modelo = new Donacion();

$nit_fundacion = null;
$id_usuario = $_SESSION['user']['id_usuario'] ?? null;
$esAdmin = $id_usuario && Roles::esAdmin($id_usuario);
$esFundacion = $id_usuario && Roles::esFundacion($id_usuario);

if (isset($_SESSION["user"]["id_usuario"])) {
    $nit_fundacion = Fundacion::obtenerNitPorUsuario($_SESSION["user"]["id_usuario"]);
}
?>

<div class="container crud-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gestión de Donaciones</h2>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered mb-0 shadow" id="tabla_donaciones" style="border-radius: 10px; overflow: hidden;">
            <thead style="background: linear-gradient(135deg, #1a1333 0%, #2d1b4e 100%); color: white;">
                <tr>
                    <th class="py-3 border-0" style="min-width: 80px;">
                        <i class="uil uil-hash me-1"></i>ID
                    </th>
                    <th class="py-3 border-0" style="min-width: 120px;">
                        <i class="uil uil-dollar-alt me-1"></i>Monto
                    </th>
                    <th class="py-3 border-0" style="min-width: 150px;">
                        <i class="uil uil-heart me-1"></i>Causa
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-check-circle me-1"></i>Estado
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-credit-card me-1"></i>Método Pago
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-calendar me-1"></i>Fecha
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-building me-1"></i>
                        <?php if ($esFundacion || $esAdmin): ?>Donante<?php else: ?>Fundación<?php endif; ?>
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-gear me-1"></i>Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                // LÓGICA CORREGIDA SEGÚN ROL
                if ($id_usuario) {
                    if ($esAdmin) {
                        // Admin ve TODAS las donaciones
                        $Donaciones = $Modelo->obtenerTodasLasDonaciones();
                    } elseif ($esFundacion && $nit_fundacion) {
                        // Fundación ve donaciones RECIBIDAS
                        $Donaciones = $Modelo->obtenerDonacionesPorFundacion($nit_fundacion);
                    } else {
                        // Usuario normal ve donaciones que ÉL hizo
                        $Donaciones = $Modelo->obtenerTodasDonacionesUsuario($id_usuario);
                    }
                } else {
                    $Donaciones = [];
                }

                if (!empty($Donaciones)) {
                    foreach ($Donaciones as $donacion) {
                ?>
                        <tr class="align-middle">
                            <td class="fw-bold" style="color: #1a1333;"><?= $donacion['id_donacion'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="fw-semibold text-success">$<?= number_format($donacion['monto'], 2, ',', '.') ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-medium"><?= htmlspecialchars($donacion['causa_nombre'] ?? 'Causa #' . $donacion['id_causa']) ?></span>
                                    <small class="text-muted">ID: <?= $donacion['id_causa'] ?></small>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php
                                $estado_color = '';
                                $estado_icon = '';
                                switch ($donacion['estado']) {
                                    case 'pagado':
                                        $estado_color = 'success';
                                        $estado_icon = 'uil-check-circle';
                                        break;
                                    default:
                                        $estado_color = 'secondary';
                                        $estado_icon = 'uil-question-circle';
                                }
                                ?>
                                <span class="badge bg-<?= $estado_color ?> px-3 py-2 rounded-pill">
                                    <i class="uil <?= $estado_icon ?> me-1"></i>
                                    <?= ucfirst($donacion['estado']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="fw-medium text-muted"><?= htmlspecialchars($donacion['metodo_pago']) ?></span>
                            </td>
                            <td class="text-center">
                                <small class="text-muted fw-medium"><?= date('d/m/Y H:i', strtotime($donacion['fecha'])) ?></small>
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <?php if ($esFundacion || $esAdmin): ?>
                                        <!-- Para fundaciones y admin: mostrar info del donante -->
                                        <span class="fw-medium" style="font-size: 0.85rem;">
                                            <?= htmlspecialchars(($donacion['donante_nombre'] ?? '') . ' ' . ($donacion['donante_apellidos'] ?? '')) ?>
                                        </span>
                                        <small class="text-muted"><?= htmlspecialchars($donacion['donante_email'] ?? 'N/A') ?></small>
                                    <?php else: ?>
                                        <!-- Para usuarios normales: mostrar fundación -->
                                        <span class="fw-medium" style="font-size: 0.85rem;"><?= htmlspecialchars($donacion['fundacion_nombre'] ?? 'N/A') ?></span>
                                        <small class="text-muted"><?= $donacion['nit_fundacion'] ?></small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm" role="group">
                                    <button class="btn btn-sm btn-ver-donacion"
                                        style="background-color: #1a1333; color: white; border-color: #1a1333;"
                                        data-id="<?= $donacion['id_donacion'] ?>"
                                        data-bs-toggle="tooltip" title="Ver detalles">
                                        <i class="uil uil-eye"></i>
                                    </button>
                                    <?php if ($donacion['estado'] === 'pagado'): ?>
                                        <button class="btn btn-sm btn-success btn-recibo-donacion"
                                            data-id="<?= $donacion['id_donacion'] ?>"
                                            data-bs-toggle="tooltip"
                                            title="Descargar recibo de donación">
                                            <i class="uil uil-receipt"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <i class="uil uil-heart display-4 mb-3" style="color: #1a1333; opacity: 0.3;"></i>
                                <?php if ($esFundacion): ?>
                                    <h5 class="text-muted mb-2">No hay donaciones recibidas</h5>
                                    <p class="text-muted mb-0">Aún no has recibido donaciones para tus causas</p>
                                <?php else: ?>
                                    <h5 class="text-muted mb-2">No hay donaciones registradas</h5>
                                    <p class="text-muted mb-0">¡Realiza tu primera donación para ayudar a una causa!</p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>