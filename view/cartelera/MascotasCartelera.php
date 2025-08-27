<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Model\Mascota\Mascota;

if (session_status() === PHP_SESSION_NONE) {
    session_start();

    // No cachear esta página
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

$mascota = new Mascota();

$id_usuario = $_SESSION['user']['id_usuario'] ?? null;

$mascotas = $mascota->getAllMascotasCarrusel();
?>
<!DOCTYPE html>

<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body class="">
    <div class="container my-5">
        <!-- Encabezado -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="display-4 mb-3">Nuestras Mascotas</h1>
                <p class="lead text-muted">Conoce a los compañeros peludos que buscan un hogar lleno de amor</p>
            </div>
        </div>

        <!-- Loading -->
        <div class="row" id="loadingMascota">
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3 text-muted">Cargando mascotas...</p>
            </div>
        </div>

        <!-- Contenedor de cartas -->
        <div class="row g-4" id="mascotasContainer" style="display: none;">
            <!-- Las cartas se generarán aquí -->
        </div>

        <!-- Mensaje si no hay mascotas -->
        <div class="row" id="mensajeVacioMascota" style="display: none;">
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-paw fa-3x mb-3 text-info"></i>
                    <h4>No hay mascotas registradas</h4>
                    <p class="mb-0">Aún no se han registrado mascotas en el sistema.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar perfil completo de mascota -->
    <div class="modal fade" id="modal-perfil-mascota" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-dog me-2"></i>
                        Perfil de la Mascota
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" id="contenido-perfil-mascota" style="max-height: 80vh; overflow-y: auto;">
                    <!-- Se carga dinámicamente con JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Adopción -->
    <div class="modal fade mt-5" id="modalAdopcion" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg">
                <!-- Encabezado -->
                <div class="modal-header text-white" style="background-color:#1a1333;">
                    <h5 class="modal-title fw-bold text-white">🐾 Formulario de Adopción</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-formulario-body">
                    <div class="alert alert-light text-center mt-2" style="color:#1a1333; border:1px solid #1a1333; border-radius:.75rem; background-color: #fdaac4;;">
                        <small>
                            Si algún dato personal es incorrecto, debes actualizarlo desde tus <strong>ajustes de cuenta</strong> antes de continuar con la solicitud de adopción.
                        </small>
                    </div>
                    <div class="card-body p-4">
                        <form>
                            <h5 class="mb-3 fw-bold" style="color:#1a1333;">Datos Personales</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nombre Completo</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($_SESSION["user"]["nombre"]); ?> <?php echo htmlspecialchars($_SESSION["user"]["apellido"]); ?>" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Edad</label>
                                    <input type="number" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Estado Civil</label>
                                    <select class="form-select" required>
                                        <option value="">Selecciona...</option>
                                        <option>Soltero/a</option>
                                        <option>Casado/a</option>
                                        <option>Unión libre</option>
                                        <option>Otro</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Tipo y número de documento -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Tipo de Documento</label>
                                    <select class="form-select" required>
                                        <option value="">Selecciona...</option>
                                        <option>Cédula de Ciudadanía</option>
                                        <option>Cédula de Extranjería</option>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Número de Documento</label>
                                    <input type="text" class="form-control" placeholder="Ej: 1234567890" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($_SESSION["user"]["email"]); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" value="<?php echo htmlspecialchars($_SESSION["user"]["telefono"]); ?>" readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Dirección de residencia</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($_SESSION["user"]["direccion"]); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ocupación</label>
                                <input type="text" class="form-control" placeholder="Ej: Ingeniero, estudiante, comerciante..." required>
                            </div>

                            <!-- CONDICIONES DEL HOGAR -->
                            <h5 class="mb-3 fw-bold" style="color:#1a1333;">Condiciones del Hogar</h5>
                            <div class="mb-3">
                                <label class="form-label">Tipo de vivienda</label>
                                <select class="form-select" required>
                                    <option value="">Selecciona...</option>
                                    <option>Casa propia</option>
                                    <option>Apartamento propio</option>
                                    <option>Casa en arriendo</option>
                                    <option>Apartamento en arriendo</option>
                                    <option>Finca / Rural</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">¿Tu vivienda tiene patio o balcón?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="patio" required>
                                    <label class="form-check-label">Sí</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="patio">
                                    <label class="form-check-label">No</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">¿Tu vivienda tiene mallas de seguridad en ventanas/balcones?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="seguridad" required>
                                    <label class="form-check-label">Sí</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="seguridad">
                                    <label class="form-check-label">No</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">¿Cuántas personas viven en tu hogar?</label>
                                <input type="number" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">¿Hay niños o adultos mayores en casa?</label>
                                <input type="text" class="form-control" placeholder="Ej: 2 niños pequeños, 1 adulto mayor...">
                            </div>

                            <!-- ESTILO DE VIDA -->
                            <h5 class="mb-3 fw-bold" style="color:#1a1333;">Estilo de Vida</h5>
                            <div class="mb-3">
                                <label class="form-label">¿Cuántas horas al día pasas fuera de casa?</label>
                                <input type="text" class="form-control" placeholder="Ej: 8 horas por trabajo, fines de semana en casa...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">¿Viajas con frecuencia?</label>
                                <textarea class="form-control" rows="2"></textarea>
                            </div>

                            <!-- EXPERIENCIA CON MASCOTAS -->
                            <h5 class="mb-3 fw-bold" style="color:#1a1333;">Experiencia con Mascotas</h5>
                            <div class="mb-3">
                                <label class="form-label">¿Has tenido mascotas anteriormente? ¿Qué pasó con ellas?</label>
                                <textarea class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">¿Tienes otras mascotas actualmente?</label>
                                <input type="text" class="form-control" placeholder="Ej: 1 perro, 2 gatos...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">¿Tus mascotas actuales están vacunadas y esterilizadas?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="vacunas">
                                    <label class="form-check-label">Sí</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="vacunas">
                                    <label class="form-check-label">No</label>
                                </div>
                            </div>

                            <!-- COMPROMISO -->
                            <h5 class="mb-3 fw-bold" style="color:#1a1333;">Compromiso y Responsabilidad</h5>
                            <div class="mb-3">
                                <label class="form-label">¿Estás dispuesto a cubrir gastos de alimentación, vacunas y emergencias veterinarias?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" required>
                                    <label class="form-check-label">Sí, me comprometo a cubrirlos</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">¿Qué harías si tu situación económica cambia y ya no puedes mantener a la mascota?</label>
                                <textarea class="form-control" rows="2"></textarea>
                            </div>

                            <!-- MOTIVACIÓN -->
                            <h5 class="mb-3 fw-bold" style="color:#1a1333;">Motivación</h5>
                            <div class="mb-3">
                                <label class="form-label">¿Por qué deseas adoptar una mascota?</label>
                                <textarea class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">¿Qué esperas de la mascota que adoptes?</label>
                                <textarea class="form-control" rows="2"></textarea>
                            </div>

                            <!-- MENSAJE FINAL -->
                            <div class="alert mt-4 text-center fw-semibold" style="background-color:#f8f9fa; color:#1a1333; border:1px solid #1a1333; border-radius:1rem;">
                                <small>
                                    Soy consciente de que este es <strong>solo el primer paso</strong> en el proceso de adopción y que
                                    <strong>llenar este formulario no garantiza</strong> la entrega inmediata de la mascota.
                                    Entiendo que la fundación realizará verificaciones adicionales, entrevistas y visitas de seguimiento si lo considera necesario,
                                    y que la aprobación final dependerá de una evaluación responsable para asegurar el bienestar del animal.
                                </small>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="condiciones" required>
                                <label class="form-check-label" for="condiciones">
                                    Acepto y comprendo esta condición
                                </label>
                            </div>

                            <!-- BOTÓN -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-lg btn-primary2 text-white px-5 rounded-pill">
                                    Enviar Solicitud
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para filtros -->
    <div class="modal fade" id="modalFiltros" tabindex="-1" aria-labelledby="modalFiltrosLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFiltrosLabel">
                        <i class="fas fa-filter me-2"></i>
                        Filtrar Mascotas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="filtroEspecie" class="form-label">Especie</label>
                            <select class="form-select" id="filtroEspecie">
                                <option value="">Todas las especies</option>
                                <option value="perro">Perros</option>
                                <option value="gato">Gatos</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="filtroEdad" class="form-label">Edad</label>
                            <select class="form-select" id="filtroEdad">
                                <option value="">Todas las edades</option>
                                <option value="cachorro">Cachorro/Cría</option>
                                <option value="joven">Joven</option>
                                <option value="adulto">Adulto</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="filtroGenero" class="form-label">Género</label>
                            <select class="form-select" id="filtroGenero">
                                <option value="">Todos</option>
                                <option value="macho">Macho</option>
                                <option value="hembra">Hembra</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" id="btnLimpiarFiltros">Limpiar Filtros</button>
                    <button type="button" class="btn btn-primary" id="btnAplicarFiltros" data-bs-dismiss="modal">Aplicar Filtros</button>
                </div>
            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-4" style="z-index: 1050;">
        <button type="button" class="btn btn-primary btn-lg rounded-circle shadow" data-bs-toggle="modal" data-bs-target="#modalFiltros" title="Filtrar mascotas">
            <i class="fas fa-filter"></i>
        </button>
    </div>
</body>

</html>