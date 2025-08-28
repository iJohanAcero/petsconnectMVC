<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\adopcion\Adopcion;
use App\Model\Fundacion\Fundacion;
use App\Config\Roles;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$Modelo = new Adopcion();
$FundacionModelo = new Fundacion();
$procesos = $Modelo->getProcesosAdopcion();

$nits = $FundacionModelo->getNitsFundacion();

$nit_fundacion = null;


$id_usuario = $_SESSION['user']['id_usuario'] ?? null;
$esAdmin = $id_usuario && Roles::esAdmin($id_usuario);
$esFundacion = $id_usuario && Roles::esFundacion($id_usuario);
$nit_sesion = $_SESSION['user']['nit_fundacion'] ?? null;

if (isset($_SESSION["user"]["id_usuario"])) {
    $nit_fundacion = Fundacion::obtenerNitPorUsuario($_SESSION["user"]["id_usuario"]);
}
?>

<div class="container crud-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gestión de Adopciones</h2>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered mb-0 shadow" id="tabla_procesos_adopcion" style="border-radius: 10px; overflow: hidden;">
            <thead style="background: linear-gradient(135deg, #1a1333 0%, #2d1b4e 100%); color: white;">
                <tr>
                    <th class="py-3 border-0" style="min-width: 80px;">
                        <i class="uil uil-hash me-1"></i>ID Proceso
                    </th>
                    <th class="py-3 border-0" style="min-width: 120px;">
                        <i class="uil uil-file-alt me-1"></i>ID Formulario
                    </th>
                    <th class="py-3 border-0" style="min-width: 150px;">
                        <i class="uil uil-user me-1"></i>Usuario Solicitante
                    </th>
                    <th class="py-3 border-0" style="min-width: 120px;">
                        <i class="uil uil-heart me-1"></i>Mascota
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-traffic-light me-1"></i>Estado
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-building me-1"></i>Fundación
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 130px;">
                        <i class="uil uil-calendar-alt me-1"></i>Fecha Inicio
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 130px;">
                        <i class="uil uil-clock me-1"></i>Última Actualización
                    </th>
                    <th class="py-3 border-0 text-center" style="min-width: 120px;">
                        <i class="uil uil-gear me-1"></i>Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($procesos)) {
                    foreach ($procesos as $proceso) {
                ?>
                        <tr class="align-middle">
                            <td class="fw-bold" style="color: #1a1333;"><?= $proceso['id_proceso'] ?></td>
                            <td class="text-center">
                                <span class="badge bg-info bg-opacity-15 border border-info border-opacity-25 px-3 py-2">
                                    #<?= $proceso['id_formulario'] ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <span class="fw-semibold d-block"><?= htmlspecialchars($proceso['nombre_usuario'] . ' ' . $proceso['apellido_usuario']) ?></span>
                                        <small class="text-muted"><?= htmlspecialchars($proceso['email_usuario']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if (!empty($proceso['imagen_mascota'])): ?>
                                        <img src="<?= htmlspecialchars($proceso['imagen_mascota']) ?>"
                                            alt="Mascota"
                                            class="rounded-circle me-2"
                                            style="width: 30px; height: 30px; object-fit: cover;">
                                    <?php endif; ?>
                                    <span class="fw-medium"><?= htmlspecialchars($proceso['nombre_mascota']) ?></span>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php
                                $estadoClass = '';
                                $estadoIcon = '';
                                switch (strtoupper($proceso['tipo_estado'])) {
                                    case 'EN TRAMITE':
                                        $estadoClass = 'bg-warning bg-opacity-15 text-dark border border-warning border-opacity-25';
                                        $estadoIcon = '⏳';
                                        break;
                                    case 'APROBADO':
                                        $estadoClass = 'bg-success bg-opacity-15 text-dark border border-success border-opacity-25';
                                        $estadoIcon = '✅';
                                        break;
                                    case 'ADOPTADO':
                                        $estadoClass = 'bg-primary bg-opacity-15 text-dark border border-primary border-opacity-25';
                                        $estadoIcon = '❤️';
                                        break;
                                    default:
                                        $estadoClass = 'bg-secondary bg-opacity-15 text-dark border border-secondary border-opacity-25';
                                        $estadoIcon = '⚪';
                                }
                                ?>
                                <span class="badge px-3 py-2 <?= $estadoClass ?>">
                                    <?= $estadoIcon ?> <?= htmlspecialchars($proceso['tipo_estado']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div>
                                    <span class="fw-medium d-block"><?= htmlspecialchars($proceso['nombre_fundacion']) ?></span>
                                    <small class="text-muted">NIT: <?= $proceso['nit_fundacion'] ?></small>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="fw-medium"><?= date('d/m/Y', strtotime($proceso['fecha_inicio'])) ?></span><br>
                                <small class="text-muted"><?= date('H:i', strtotime($proceso['fecha_inicio'])) ?></small>
                            </td>
                            <td class="text-center">
                                <span class="fw-medium"><?= date('d/m/Y', strtotime($proceso['fecha_actualizada'])) ?></span><br>
                                <small class="text-muted"><?= date('H:i', strtotime($proceso['fecha_actualizada'])) ?></small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm" role="group">
                                    <button class="btn btn-sm btn-warning btn-editar-adopcion"
                                        data-id="<?= $proceso['id_proceso'] ?>"
                                        data-estado-actual="<?= $proceso['id_estado'] ?>"
                                        data-bs-toggle="tooltip" title="Actualizar estado">
                                        <i class="uil uil-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary btn-ver-formulario"
                                        data-formulario-id="<?= $proceso['id_formulario'] ?>"
                                        data-bs-toggle="tooltip" title="Ver formulario de adopción">
                                        <i class="uil uil-file-alt"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger btn-eliminar-adopcion"
                                        data-formulario-id="<?= $proceso['id_proceso'] ?>"
                                        data-bs-toggle="tooltip" title="Elimianr proceso">
                                        <i class="uil uil-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <i class="uil uil-clipboard-blank display-4 mb-3" style="color: #1a1333; opacity: 0.3;"></i>
                                <h5 class="text-muted mb-2">No hay procesos de adopción registrados</h5>
                                <p class="text-muted mb-0">Los procesos aparecerán cuando se registren solicitudes de adopción</p>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para actualizar estado -->
    <div class="modal fade" id="modalActualizarEstado" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1a1333; color: white;">
                    <h5 class="modal-title">
                        <i class="uil uil-traffic-light me-2"></i>
                        Actualizar Estado del Proceso
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formActualizarEstado">
                        <input type="hidden" id="procesoIdEstado" name="proceso_id">
                        <div class="mb-3">
                            <label for="nuevoEstado" class="form-label">Nuevo Estado</label>
                            <select class="form-select" id="nuevoEstado" name="nuevo_estado" required>
                                <option value="">Selecciona un estado...</option>
                                <option value="2">En Trámite</option>
                                <option value="3">Aceptado</option>
                                <option value="1">Rechazado</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarEstado">Actualizar Estado</button>
                </div>
            </div>
        </div>
    </div>
</div>