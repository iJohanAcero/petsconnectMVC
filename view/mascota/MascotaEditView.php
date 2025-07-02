<?php
require_once("../../Model/mascota/MascotaModel.php");

$Modelo = new Mascota();

// Validar ID de mascota
if (!isset($_GET['id'])) {
    echo "ID de mascota no especificado.";
    exit;
}

$id = $_GET['id'];
$mascota = $Modelo->getId($id);
if (!$mascota || empty($mascota)) {
    echo "Mascota no encontrada.";
    exit;
}

$mascota = $mascota[0];

// Cargar selects
$tipos = $Modelo->getTiposMascota();
$estados = $Modelo->getEstadosAdopcion();

if (!$tipos || !is_array($tipos)) {
    echo "⚠️ Error: No se pudieron cargar los tipos de mascota.";
    exit;
}

if (!$estados || !is_array($estados)) {
    echo "⚠️ Error: No se pudieron cargar los estados de adopción.";
    exit;
}


// Asumimos que solo el admin verá este campo
session_start();
$esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
$nits = $esAdmin ? $Modelo->getNitsFundacion() : [];
?>

<!-- Contenido principal -->
<div class="container mt-1">
    <form id="form-editar-mascota" method="POST" action="../../controller/mascota/MascotaController.php" enctype="multipart/form-data">
        <input type="hidden" name="accion" value="editar">
        <input type="hidden" name="id_mascota" value="<?= $mascota['id_mascota'] ?>">

        <fieldset class="mt-5; mb-4 p-3 border rounded bg-light">
            <div class="mb-3">
                <label class="form-label">Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($mascota['nombre']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Edad (en meses):</label>
                <input type="number" name="edad_meses" class="form-control" min="0" value="<?= $mascota['edad_meses'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Sexo:</label>
                <select name="sexo" class="form-select" required>
                    <option value="">Seleccione</option>
                    <option value="macho" <?= $mascota['sexo'] == 'macho' ? 'selected' : '' ?>>Macho</option>
                    <option value="hembra" <?= $mascota['sexo'] == 'hembra' ? 'selected' : '' ?>>Hembra</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo de Mascota:</label>
                <select name="id_tipo_mascota" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($tipos as $tipo): ?>
                        <option value="<?= $tipo['id_tipo_mascota'] ?>" <?= $tipo['id_tipo_mascota'] == $mascota['id_tipo_mascota'] ? 'selected' : '' ?>>
                            <?= $tipo['especie'] ?> - <?= $tipo['raza'] ?> - <?= $tipo['tamaño'] ?> - <?= $tipo['tipo_pelaje'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Estado de Adopción:</label>
                <select name="id_estado_adopcion" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <?php foreach ($estados as $estado): ?>
                        <option value="<?= $estado['id_estado_adopcion'] ?>" <?= $estado['id_estado_adopcion'] == $mascota['id_estado_adopcion'] ? 'selected' : '' ?>>
                            <?= $estado['tipo_estado'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if ($esAdmin): ?>
                <div class="mb-3">
                    <label class="form-label">NIT Fundación:</label>
                    <input list="nits_fundacion" name="nit_fundacion" class="form-control" value="<?= $mascota['nit_fundacion'] ?>" required>
                    <datalist id="nits_fundacion">
                        <?php foreach ($nits as $nit): ?>
                            <option value="<?= $nit ?>">
                            <?php endforeach; ?>
                    </datalist>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Imagen:</label>
                <input type="file" name="imagen" accept="image/*" class="form-control">
                <?php if (!empty($mascota['imagen'])): ?>
                    <div class="mt-2">
                        <img src="/petsconnectMVC/public/images/mascotas/<?= htmlspecialchars($mascota['imagen']) ?>" alt="Imagen actual" style="max-width: 150px; border-radius: 8px;">
                        <div><small class="text-muted">Imagen actual: <?= htmlspecialchars($mascota['imagen']) ?></small></div>
                        <div class="form-text">Formato recomendado: JPG, PNG.</div>
                    </div>
                <?php endif; ?>

            </div>
        </fieldset>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">← Volver</button>
            <button type="submit" class="btn btn-primary">Actualizar Mascota</button>
        </div>
    </form>
</div>