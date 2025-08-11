<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Mascota\Mascota;
use App\Model\Fundacion\Fundacion;
use App\Config\Roles;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$Modelo = new Mascota();
$FundacionModelo = new Fundacion();

$mascotas = $Modelo->getMascota();
$tipos = $Modelo->getTiposMascota();
$nits = $FundacionModelo->getNitsFundacion();
$estados = $Modelo->getEstadosAdopcion();

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
        <h2 class="mb-0">Gestión de Mascotas</h2>
        <?php if ($esFundacion): ?>
            <button id="btn-abrir-modal-mascota" class="btn btn-primary">
                <i class=" bi bi-plus-circle"></i> Registrar mascota
            </button>
        <?php endif; ?>
    </div>

    

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered text-center" id="tabla_mascotas">
            <thead class="table" style="background-color: #1a1333; color: white;">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Edad (meses)</th>
                    <th>Sexo</th>
                    <th>Especie</th>
                    <th>Estado</th>
                    <th>Nit Fundación</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $tipo_usuario = $_SESSION["tipo_usuario"] ?? null;
                $mascotas = $Modelo->getMascota();

                if ($tipo_usuario === "fundacion" && $nit_fundacion !== null) {
                    $mascotas = $Modelo->getMascotasPorFundacion($nit_fundacion);
                } else {
                    $mascotas = $Modelo->getMascota();
                }
                if (!empty($mascotas)) {
                    foreach ($mascotas as $mascota) {
                ?>
                        <tr>
                            <td><?= $mascota['id_mascota'] ?></td>
                            <td><?= htmlspecialchars($mascota['nombre']) ?></td>
                            <td><?= $mascota['edad_meses'] ?></td>
                            <td><?= $mascota['sexo'] ?></td>
                            <td><?= htmlspecialchars($mascota['especie']) ?></td>
                            <td><?= htmlspecialchars($mascota['tipo_estado']) ?></td>
                            <td><?= $mascota['nit_fundacion'] ?></td>
                            <td>
                                <?php if (!empty($mascota['imagen'])): ?>
                                    <img src="/petsconnectMVC/public/images/mascotas/<?= htmlspecialchars($mascota['imagen']) ?>" alt="Mascota" width="60" class="img-thumbnail">
                                <?php else: ?>
                                    <span class="text-muted">Sin imagen</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($esAdmin || $esFundacion): ?>
                                    <button class="btn btn-sm btn-warning btn-editar-mascota" data-id="<?= $mascota['id_mascota'] ?>">
                                        <i class="uil uil-pen"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($esAdmin || $esFundacion): ?>
                                    <button class="btn btn-sm btn-danger btn-eliminar-mascota" data-id="<?php echo $mascota['id_mascota']; ?>">
                                        <i class="uil uil-trash"></i>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                    } 
                    } else {
                    ?>
                        <tr>
                            <td colspan="9" class="text-center">No hay mascotas registradas</td>
                        </tr>
                    <?php
                }
            ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Registrar Mascota -->
<div class="modal fade" id="modal-mascotas" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="margin-top: 70px;">
        <div class="modal-content">
            <form id="form-registrar-mascota" method="post" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="registrar">

                <div class="modal-header" style="background-color: #1a1333; color: white;">
                    <h5 class="modal-title" style="color: white;">Registrar nueva mascota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2">
                        <label>ID Número de chip:</label>
                        <input type="text" name="id_mascota" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Nombre:</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Edad (meses):</label>
                        <input type="number" name="edad_meses" class="form-control" min="0" required>
                    </div>

                    <div class="mb-2">
                        <label>Sexo:</label>
                        <select name="sexo" class="form-select" required>
                            <option value="">Seleccione</option>
                            <option value="macho">Macho</option>
                            <option value="hembra">Hembra</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Imagen:</label>
                        <input type="file" name="imagen" accept="image/*" class="form-control">
                        <div class="form-text">Formato recomendado: JPG, PNG.</div>
                    </div>

                    <div class="mb-2">
                        <label>Tipo de Mascota:</label>
                        <select name="id_tipo_mascota" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php if (!empty($tipos) && is_array($tipos)): ?>
                                <?php foreach ($tipos as $tipo): ?>
                                    <option value="<?= $tipo['id_tipo_mascota'] ?>">
                                        <?= $tipo['especie'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option disabled>No hay tipos de mascota registrados</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Estado de Adopción:</label>
                        <select name="id_estado_adopcion" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($estados as $estado): ?>
                                <option value="<?= $estado['id_estado_adopcion'] ?>" <?= $estado['tipo_estado'] === 'EN ADOPCIÓN' ? 'selected' : '' ?>>
                                    <?= $estado['tipo_estado'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <input type="hidden" name="nit_fundacion" value="<?php echo htmlspecialchars($nit_fundacion); ?>">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar (Contenido por JS) -->
<div class="modal fade" id="modal-editar-mascota" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #fdaac4;">
                <h5 class="modal-title">Editar Mascota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenido-editar-mascota"></div>
        </div>
    </div>
</div>