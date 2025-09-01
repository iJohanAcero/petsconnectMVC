// Función para cargar causas desde el backend - CORREGIDA
function cargarCausasDesdeBackend() {
    const loading = document.getElementById('loadingCausa');
    const causasContainer = document.getElementById('causasContainer');
    const mensajeVacio = document.getElementById('mensajeVacioCausa');

    // Verificar que los elementos existan
    if (!loading || !causasContainer || !mensajeVacio) {
        console.error("⚠️ Elementos del DOM no encontrados para cartelera");
        return;
    }

    // Mostrar loading
    loading.style.display = 'block';
    causasContainer.style.display = 'none';
    mensajeVacio.style.display = 'none';

    // ✅ CORREGIDO: Usar POST con FormData
    const formData = new FormData();
    formData.append('accion', 'getAllCausasCarrusel');

    fetch('controller/Causa/CausaController.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(data => {
            console.log('Datos recibidos:', data); // ✅ Debug
            
            if (!data || data.trim() === '') {
                console.error('Respuesta vacía del servidor');
                mostrarErrorCarga();
                return;
            }
            
            try {
                const causas = JSON.parse(data);
                const fundacion = JSON.parse(data);
                console.log('Causas parseadas:', causas); // ✅ Debug
                console.log('Fundación parseada:', fundacion); // ✅ Debug
                mostrarCausas(causas);
            } catch (e) {
                console.error('Error al parsear JSON:', e);
                console.error('Contenido recibido:', data);
                mostrarErrorCarga();
            }
        })
        .catch(error => {
            console.error("⚠️ Error completo al cargar causas:", error);
            mostrarErrorCarga();
        });
}

// ✅ RESTO DEL CÓDIGO SIN CAMBIOS
window.cargarCarteleraCausas = function () {
    fetch("view/cartelera/CausasCartelera.php")
        .then(response => {
            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            const mainContainer = document.getElementById("main-content");

            if (mainContainer) {
                mainContainer.innerHTML = data;

                setTimeout(() => {
                    inicializarEventosCarteleraCausas();
                    cargarCausasDesdeBackend();
                    inicializarFiltrosCausas();
                }, 100);
            }
        })
        .catch(error => {
            console.error("⚠️ Error al cargar PHP:", error);
            mostrarErrorCarga();
        });
};

function generarUrlCloudinary(publicId, transformaciones = '') {
    if (!publicId || publicId.trim() === '') {
        return generarImagenPorDefecto();
    }
    
    // Verificar si ya es una URL completa de Cloudinary
    if (publicId.includes('res.cloudinary.com')) {
        if (transformaciones && !publicId.includes('w_')) {
            return publicId.replace('/upload/', `/upload/${transformaciones}/`);
        }
        return publicId;
    }
    
    const CLOUDINARY_CLOUD_NAME = 'dhyowmhw6';
    
    const cleanPublicId = publicId.replace(/\.(jpg|jpeg|png|gif|webp)$/i, '');
    
    let url = `https://res.cloudinary.com/${CLOUDINARY_CLOUD_NAME}/image/upload/`;
    
    if (transformaciones) {
        url += `${transformaciones}/`;
    }
    
    url += `${cleanPublicId}`;
    
    return url;
}

function inicializarEventosCarteleraCausas() {
    if (!document.getElementById('causasContainer')) {
        console.warn("Elementos de cartelera no encontrados");
        return;
    }
}

// Función para inicializar filtros de causas
function inicializarFiltrosCausas() {
    const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');
    const btnAplicarFiltros = document.getElementById('btnAplicarFiltros');

    if (btnAplicarFiltros) {
        btnAplicarFiltros.addEventListener('click', aplicarFiltrosCausas);
    }

    if (btnLimpiarFiltros) {
        btnLimpiarFiltros.addEventListener('click', limpiarFiltrosCausas);
    }
}

function mostrarErrorCarga() {
    const mainContainer = document.getElementById("main-content");
    if (mainContainer) {
        mainContainer.innerHTML = `
            <div class="container my-5">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3 text-danger"></i>
                            <h4>Error al cargar la vista</h4>
                            <p class="mb-3">No se pudo cargar la cartelera de causas.</p>
                            <button class="btn btn-primary" onclick="cargarCarteleraCausas()">
                                <i class="fas fa-redo me-1"></i>
                                Reintentar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
}

// Función para mostrar las causas en cartas
function mostrarCausas(causas) {
    const loading = document.getElementById('loadingCausa');
    const causasContainer = document.getElementById('causasContainer');
    const mensajeVacio = document.getElementById('mensajeVacioCausa');

    loading.style.display = 'none';

    if (!causas || causas.length === 0) {
        mensajeVacio.style.display = 'block';
        mensajeVacio.innerHTML = `...`;
        return;
    }

    causasContainer.style.display = 'flex';
    causasContainer.innerHTML = causas.map(crearCartaCausa).join('');

    // Inicializar eventos de las cartas y botones "Ver más"
    inicializarEventosCartasCausas();
    animarEntradaCartas();

    // ✅ Inicializar eventos para los nuevos botones "Ver más"
    document.querySelectorAll('.btn-ver-causa').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const idCausa = this.getAttribute('data-id');
            cargarDetalleCausa(idCausa);
        });
    });
}

// Función para crear HTML de cada carta causa
function crearCartaCausa(causa) {
    const nombre = causa.nombre;
    const descripcion = causa.descripcion || 'Sin descripción disponible';
    const meta = causa.meta;
    const estadoCausa = causa.estado_causa;
    const tipoCausa = causa.tipo_causa;
    const nitFundacion = causa.nit_fundacion;
    
    // Formatear meta con separador de miles
    const metaFormateada = meta ? 
        new Intl.NumberFormat('es-CO', { 
            style: 'currency', 
            currency: 'COP' 
        }).format(meta) : 'Meta no definida';
    
    // Icono según tipo de causa
    const iconoCausa = {
        'ALIMENTACION': 'uil uil-utensils-alt',
        'MEDICINA': 'uil uil-heartbeat',
        'REFUGIO': 'uil uil-home-alt',
        'EDUCACION': 'uil uil-graduation-cap',
        'GENERAL': 'uil uil-heart'
    }[tipoCausa?.toUpperCase()] || 'uil uil-heart';
    
    // Color del badge según estado
    const colorEstado = {
        'ACTIVA': 'bg-success',
        'PAUSADA': 'bg-warning',
        'FINALIZADA': 'bg-secondary',
        'CANCELADA': 'bg-danger'
    }[estadoCausa?.toUpperCase()] || 'bg-info';
    
    // Generar URL de imagen optimizada
    const imagenUrl = generarUrlCloudinary(
        causa.imagen_url || causa.public_id,
        'w_300,h_250,c_fill,g_center,q_auto,f_auto'
    );
    
    // Truncar descripción para vista previa
    const descripcionCorta = descripcion.length > 100 ? 
        descripcion.substring(0, 100) + '...' : descripcion;
    
    return `
<div class="col-lg-6 col-md-6 col-12 mb-4">
    <div class="card shadow-lg border-0 carta-causa h-100" data-id="${causa.id_causa}" 
         style="border-radius: 15px; overflow: hidden; transition: all 0.3s ease;">
        
        <!-- Layout horizontal: imagen + contenido -->
        <div class="row g-0 h-100">
            
            <!-- Imagen lado izquierdo -->
            <div class="col-md-5">
                <div class="position-relative h-100" style="min-height: 200px;">
                    <img src="${imagenUrl}"
                         class="w-100 h-100"
                         alt="${nombre}"
                         style="object-fit: cover;">
                    
                    <!-- Badge de estado superpuesto -->
                    <div class="position-absolute top-0 start-0 m-3">
                        <span class="badge ${colorEstado} fs-6 px-3 py-2 shadow-sm">
                            ${estadoCausa}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Contenido lado derecho -->
            <div class="col-md-7">
                <div class="card-body p-4 d-flex flex-column h-100">
                    
                    <!-- Header con nombre e icono -->
                    <div class="mb-3">
                        <h5 class="card-title text-primary2 fw-bold mb-2 d-flex align-items-center">
                            <i class="${iconoCausa} me-2 "></i>
                            ${nombre}
                        </h5>
                        
                        <!-- Tipo de causa como badge pequeño -->
                        <span class="badge bg-light text-dark border border-primary2 px-2 py-1 small">
                            ${tipoCausa || 'General'}
                        </span>
                    </div>
                    
                    <!-- Descripción -->
                    <div class="mb-3 flex-grow-1">
                        <p class="card-text text-muted small mb-0" style="line-height: 1.4;">
                            ${descripcionCorta}
                        </p>
                    </div>
                    
                    <!-- Meta destacada -->
                    <div class="mb-3">
                        <div class="bg-opacity-10 rounded-3 p-3 text-center" style="background-color: hsl(252, 30%, 17%);">
                            <div class="small text-white mb-1">Meta de recaudación</div>
                            <div class="fw-bold text-white h6 mb-0">${metaFormateada}</div>
                        </div>
                    </div>
                    
                    <!-- Botones de acción en la parte inferior -->
                    <div class="mt-auto">
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button"
                                        class="btn btn-outline w-100 btn-ver-causa d-flex align-items-center justify-content-center"
                                        data-id="${causa.id_causa}"
                                        style="border-radius: 8px;">
                                    <i class="fas fa-eye me-1"></i>
                                    <small>Ver más</small>
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button"
                                        class="btn btn-primary2 w-100 btn-donar d-flex align-items-center justify-content-center"
                                        data-id="${causa.id_causa}"
                                        style="border-radius: 8px;">
                                    <i class="fas fa-heart me-1"></i>
                                    <small>Donar</small>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>`;
}

// Función para inicializar eventos de las cartas
function inicializarEventosCartasCausas() {

    // ✅ EVENTOS PARA BOTONES "DONAR" - CORREGIDO
    document.querySelectorAll('.btn-donar').forEach(btn => {
        btn.addEventListener('click', function() {
            const idCausa = this.getAttribute('data-id');
            mostrarModalDonacion(idCausa);
        });
    });

    // Evento click en toda la carta (opcional)
    document.querySelectorAll('.carta-causa').forEach(carta => {
        carta.addEventListener('click', function(e) {
            // Solo si no se clickeó un botón
            if (!e.target.classList.contains('btn') && !e.target.closest('.btn')) {
                const id = this.getAttribute('data-id');
            }
        });
    });
}

// ✅ EVENTOS PARA BOTONES "VER MÁS" - NUEVO
document.querySelectorAll('.btn-ver-causa').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation(); // Evitar que se dispare el click de la carta
        const idCausa = this.getAttribute('data-id');
        console.log('Ver más causa ID:', idCausa);
        cargarDetalleCausa(idCausa);
    });
});

// NUEVA FUNCIÓN: Cargar detalle de causa para el modal
function cargarDetalleCausa(idCausa) {
    if (!idCausa) {
        console.error('ID de causa no proporcionado');
        return;
    }

    // Mostrar loading en el modal
    mostrarLoadingModal();

    // Preparar datos para enviar al backend
    const formData = new FormData();
    formData.append('accion', 'obtenerDetalleCausa');
    formData.append('id_causa', idCausa);

    fetch('controller/Causa/CausaController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(data => {
        console.log('Detalle recibido:', data);
        
        try {
            const resultado = JSON.parse(data);
            
            if (resultado.error) {
                console.error('Error del servidor:', resultado.error);
                mostrarErrorEnModal(resultado.error);
                return;
            }
            
            // Mostrar la causa en el modal
            mostrarCausaCompletoEnModal(resultado.causa, resultado.fundacion);
            
        } catch (e) {
            console.error('Error al parsear respuesta:', e);
            console.error('Contenido recibido:', data);
            mostrarErrorEnModal('Error al procesar la respuesta del servidor');
        }
    })
    .catch(error => {
        console.error('Error al cargar detalle:', error);
        mostrarErrorEnModal('Error de conexión al cargar el detalle');
    });
}


// NUEVA FUNCIÓN: Inicializar eventos del modal de causa
function inicializarEventosModalCausa() {
    // Evento para botón de donar del modal
    const btnDonarModal = document.querySelector('.btn-donar-modal');
    if (btnDonarModal) {
        btnDonarModal.addEventListener('click', function() {
            const idCausa = this.getAttribute('data-id');
            // Cerrar modal actual primero
            const modal = bootstrap.Modal.getInstance(document.getElementById('modal-causa-detalle'));
            if (modal) modal.hide();
            
            // Mostrar modal de donación
            setTimeout(() => {
                mostrarModalDonacion(idCausa);
            }, 300);
        });
    }
}

// AÑADIR estas funciones a carteleraCausa.js

// ✅ FUNCIÓN PRINCIPAL: Mostrar modal de donación
function mostrarModalDonacion(idCausa) {
    if (!idCausa) {
        console.error('ID de causa no proporcionado para donación');
        return;
    }

    console.log('Mostrando modal de donación para causa:', idCausa);

    // Crear o mostrar modal de donación
    crearModalDonacion(idCausa);
}

// ✅ FUNCIÓN: Crear modal de donación dinámicamente
function crearModalDonacion(idCausa) {
    // Verificar si ya existe el modal
    let modalExistente = document.getElementById('modal-donacion-causa');
    if (modalExistente) {
        modalExistente.remove();
    }

    // Crear nuevo modal
    const modalHTML = `
        <div class="modal fade" id="modal-donacion-causa" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                    <div class="modal-header bg-gradient text-white" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 15px 15px 0 0;">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-heart me-2"></i>
                            Realizar Donación
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        
                        <!-- Información de la causa -->
                        <div class="bg-light rounded-3 p-3 mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                <small class="text-muted">Donando a la causa ID: <strong>${idCausa}</strong></small>
                            </div>
                        </div>

                        <!-- Formulario de donación -->
                        <form id="form-donacion-causa">
                            <input type="hidden" name="id_causa" value="${idCausa}">
                            
                            <!-- Monto de donación -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-dollar-sign me-1"></i>
                                    Monto de donación
                                </label>
                                
                                <!-- Botones de montos rápidos -->
                                <div class="row g-2 mb-3">
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-success w-100 btn-monto-rapido" data-monto="10000">
                                            $10.000
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-success w-100 btn-monto-rapido" data-monto="25000">
                                            $25.000
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button type="button" class="btn btn-outline-success w-100 btn-monto-rapido" data-monto="50000">
                                            $50.000
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Input personalizado -->
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" 
                                           class="form-control" 
                                           name="monto" 
                                           id="monto-donacion"
                                           placeholder="Ingresa tu monto personalizado"
                                           min="1000"
                                           step="1000"
                                           required>
                                    <span class="input-group-text">COP</span>
                                </div>
                                <small class="text-muted">Monto mínimo: $1.000 COP</small>
                            </div>

                            <!-- Método de pago -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-credit-card me-1"></i>
                                    Método de pago
                                </label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="form-check p-3 border rounded-3 h-100">
                                            <input class="form-check-input" type="radio" name="metodo_pago" id="tarjeta" value="tarjeta" checked>
                                            <label class="form-check-label w-100" for="tarjeta">
                                                <i class="fas fa-credit-card me-2 text-primary"></i>
                                                Tarjeta de crédito/débito
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check p-3 border rounded-3 h-100">
                                            <input class="form-check-input" type="radio" name="metodo_pago" id="transferencia" value="transferencia">
                                            <label class="form-check-label w-100" for="transferencia">
                                                <i class="fas fa-university me-2 text-success"></i>
                                                Transferencia bancaria
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del donante -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-user me-1"></i>
                                    Información del donante
                                </label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="nombre_donante" placeholder="Nombre completo" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" class="form-control" name="email_donante" placeholder="Correo electrónico" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Mensaje opcional -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-comment me-1"></i>
                                    Mensaje de apoyo (opcional)
                                </label>
                                <textarea class="form-control" 
                                          name="mensaje" 
                                          rows="3" 
                                          placeholder="Escribe un mensaje de apoyo para esta causa..."></textarea>
                            </div>

                            <!-- Términos y condiciones -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terminos-donacion" required>
                                    <label class="form-check-label small" for="terminos-donacion">
                                        Acepto los <a href="#" class="text-decoration-none">términos y condiciones</a> 
                                        y autorizo el procesamiento de mis datos personales para esta donación.
                                    </label>
                                </div>
                            </div>

                        </form>
                    </div>
                    
                    <!-- Footer con botones -->
                    <div class="modal-footer bg-light" style="border-radius: 0 0 15px 15px;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-success btn-lg" id="btn-procesar-donacion">
                            <i class="fas fa-heart me-2"></i>
                            Procesar Donación
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Insertar modal en el DOM
    document.body.insertAdjacentHTML('beforeend', modalHTML);

    // Inicializar eventos del modal
    inicializarEventosModalDonacion();

    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('modal-donacion-causa'));
    modal.show();

    // Limpiar modal cuando se cierre
    document.getElementById('modal-donacion-causa').addEventListener('hidden.bs.modal', function () {
        this.remove();
    });
}

// ✅ FUNCIÓN: Inicializar eventos del modal de donación
function inicializarEventosModalDonacion() {
    // Eventos para botones de monto rápido
    document.querySelectorAll('.btn-monto-rapido').forEach(btn => {
        btn.addEventListener('click', function() {
            const monto = this.getAttribute('data-monto');
            document.getElementById('monto-donacion').value = monto;
            
            // Resaltar botón seleccionado
            document.querySelectorAll('.btn-monto-rapido').forEach(b => {
                b.classList.remove('btn-success');
                b.classList.add('btn-outline-success');
            });
            this.classList.remove('btn-outline-success');
            this.classList.add('btn-success');
        });
    });
}

// ✅ FUNCIÓN: Mostrar loading en modal
function mostrarLoadingModal() {
    const modalBody = document.getElementById('contenido-causa');
    if (modalBody) {
        modalBody.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-3 text-muted">Cargando información de la causa...</p>
            </div>
        `;
    }
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('modal-causa-detalle'));
    modal.show();
}

// ✅ FUNCIÓN: Mostrar error en modal
function mostrarErrorEnModal(mensaje) {
    const modalBody = document.getElementById('contenido-causa');
    if (modalBody) {
        modalBody.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <h5 class="text-danger">Error al cargar</h5>
                <p class="text-muted">${mensaje}</p>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>
        `;
    }
}

// ✅ FUNCIÓN COMPLETA: Mostrar causa en modal (CORREGIDA)
function mostrarCausaCompletoEnModal(causa, fundacion) {
    const modalBody = document.getElementById('contenido-causa');
    
    if (!causa) {
        mostrarErrorEnModal('No se pudo cargar la información de la causa');
        return;
    }

    // Generar imagen optimizada
    const imagenUrl = generarUrlCloudinary(
        causa.imagen_url || causa.public_id,
        'w_400,h_300,c_fill,g_center,q_auto,f_auto'
    );

    const imagenFundacionUrl = generarUrlCloudinary(
        fundacion?.imagen_url || fundacion?.public_id,
        'w_60,h_60,c_fill,g_face,q_auto,f_auto'
    );

    // Formatear meta
    const metaFormateada = causa.meta ? 
        new Intl.NumberFormat('es-CO', { 
            style: 'currency', 
            currency: 'COP' 
        }).format(causa.meta) : 'Meta no definida';

    // Icono según tipo de causa
    const iconoCausa = {
        'ALIMENTACION': 'uil fa-utensils',
        'MEDICINA': 'uil fa-heartbeat',
        'REFUGIO': 'uil fa-home',
        'EDUCACION': 'uil fa-graduation-cap',
        'GENERAL': 'uil fa-heart'
    }[causa.tipo_causa?.toUpperCase()] || 'uil uil-heart';

    // Color del badge según estado
    const colorEstado = {
        'ACTIVA': 'bg-success',
        'PAUSADA': 'bg-warning',
        'FINALIZADA': 'bg-secondary',
        'CANCELADA': 'bg-danger'
    }[causa.estado_causa?.toUpperCase()] || 'bg-info';

const contenidoModal = `
<div class="card shadow border-0">

  <!-- Imagen principal con estado -->
  <div class="position-relative">
    <img src="${imagenUrl}" class="card-img-top" alt="${causa.nombre}" style="height: 240px; object-fit: cover;">
    <span class="badge ${colorEstado} position-absolute top-0 end-0 m-2 fs-6 px-3 py-2">
      ${causa.estado_causa}
    </span>
  </div>

  <!-- Contenido -->
  <div class="card-body">

    <!-- Título y tipo -->
    <h4 class="fw-bold text-dark mb-2">
      <i class="${iconoCausa} me-2 text-danger"></i>${causa.nombre}
    </h4>
    <span class="badge bg-light text-dark border mb-3">${causa.tipo_causa || 'General'}</span>

    <!-- Fundación -->
    ${fundacion ? `
    <div class="d-flex align-items-center mb-4">
      <img src="${imagenFundacionUrl}" 
           class="rounded-circle border border-primary me-3"
           alt="${fundacion.nombre}"
           style="width: 45px; height: 45px; object-fit: cover;">
      <div>
        <h6 class="mb-1 fw-semibold text-dark">${fundacion.nombre}</h6>
        <small class="text-muted"><i class="fas fa-building me-1"></i>NIT: ${causa.nit_fundacion}</small>
      </div>
    </div>
    ` : ''}

    <!-- Descripción -->
    <p class="card-text text-muted mb-4">
      ${causa.descripcion || 'Sin descripción disponible'}
    </p>

    <!-- Bloque de detalles (meta y fecha) -->
    <ul class="list-group list-group-flush mb-4">
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-dark">Meta de Recaudación</span>
        <span class="fw-bold text-dark">${metaFormateada}</span>
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-dark">Fecha de Creación</span>
        <span class="fw-bold text-dark">
          ${new Date(causa.fecha_creacion).toLocaleDateString('es-CO')}
        </span>
      </li>
    </ul>

    <!-- Botón -->
    <div class="d-grid">
      <button type="button"
              class="btn btn-lg btn-donar-modal fw-semibold"
              style="background-color: hsl(252, 30%, 17%); color: white; border-radius: 8px;"
              data-id="${causa.id_causa}">
        <i class="fas fa-heart me-2 text-danger"></i> Donar a esta causa
      </button>
    </div>

  </div>
</div>


    `;

    modalBody.innerHTML = contenidoModal;

    // ✅ INICIALIZAR EVENTOS DEL MODAL
    inicializarEventosModalCausa();
    
    // Mostrar el modal si no está visible
    const modalElement = document.getElementById('modal-causa-detalle');
    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
    modal.show();
}

// Función para animar entrada de cartas
function animarEntradaCartas() {
    const cartas = document.querySelectorAll('.carta-causa');
    cartas.forEach((carta, index) => {
        carta.style.opacity = '0';
        carta.style.transform = 'translateY(20px)';
        carta.style.transition = 'all 0.5s ease';
        
        setTimeout(() => {
            carta.style.opacity = '1';
            carta.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// ✅ INICIALIZACIÓN DE EVENTOS DEL DOM - LIMPIADO
document.addEventListener("DOMContentLoaded", () => {
    const botones = document.querySelectorAll(".btn-cargar-cartelCausa");

    botones.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            cargarCarteleraCausas();
        });
    });
});