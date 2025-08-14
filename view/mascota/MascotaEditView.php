<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../vendor/autoload.php';
    use App\Model\Mascota\Mascota;

try {
    

    if (!isset($_GET['id'])) {
        echo "<div class='alert alert-danger'>ID de mascota no especificado.</div>";
        exit;
    }

    $id = $_GET['id'];
    $Modelo = new Mascota();
    $mascota = $Modelo->getId($id);
    
    if (!$mascota || empty($mascota)) {
        echo "<div class='alert alert-danger'>Mascota no encontrada.</div>";
        exit;
    }

    $mascota = $mascota[0];
    $tipos = $Modelo->getTiposMascota();
    $estados = $Modelo->getEstadosAdopcion();
    
    session_start();
    $esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin';
    $nits = $esAdmin ? $Modelo->getNitsFundacion() : [];

} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    exit;
}
?>

<form id="form-editar-mascota" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="accion" value="editar">
    <input type="hidden" name="id_mascota" value="<?= htmlspecialchars($mascota['id_mascota']) ?>">

    <div class="mb-3">
        <label class="form-label">Nombre:</label>
        <input type="text" name="nombre" class="form-control" 
               value="<?= htmlspecialchars($mascota['nombre']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Edad (en meses):</label>
        <input type="number" name="edad_meses" class="form-control" min="0" 
               value="<?= htmlspecialchars($mascota['edad_meses']) ?>" required>
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
            <?php if (!empty($tipos)): ?>
                <?php foreach ($tipos as $tipo): ?>
                    <option value="<?= htmlspecialchars($tipo['id_tipo_mascota']) ?>" 
                            <?= $tipo['id_tipo_mascota'] == $mascota['id_tipo_mascota'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tipo['especie'] ?? 'N/A') ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Estado de Adopción:</label>
        <select name="id_estado_adopcion" class="form-select" required>
            <option value="">Seleccione...</option>
            <?php if (!empty($estados)): ?>
                <?php foreach ($estados as $estado): ?>
                    <option value="<?= htmlspecialchars($estado['id_estado_adopcion']) ?>" 
                            <?= $estado['id_estado_adopcion'] == $mascota['id_estado_adopcion'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($estado['tipo_estado']) ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Imagen:</label>
        <input type="file" name="imagen" accept="image/*" class="form-control">
        <input type="hidden" name="imagen_actual" value="<?= htmlspecialchars($mascota['imagen'] ?? '') ?>">
        <?php if (!empty($mascota['imagen'])): ?>
            <div class="mt-2">
                <img src="/petsconnectMVC/public/images/mascotas/<?= htmlspecialchars($mascota['imagen']) ?>" 
                     alt="Imagen actual" style="max-width: 150px; border-radius: 8px;">
            </div>
        <?php endif; ?>
    </div>

    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Actualizar Mascota</button>
    </div>
</form>