// Variables globales para Stripe
let stripe = null;
let elements = null;
let cardElement = null;

// Inicializar Stripe cuando se carga la página
function inicializarStripe() {
    // clave pública de Stripe
    stripe = Stripe('pk_test_51S37XRRvWIJHWRYjyP4skV97jocTLdVtZEy34wu2vCPy2a9V3vpengsX0hE9G9sLgaasA54MpkZGg2eOovd0W5gJ00ckt6LW7E');
}

function cargarCausasDesdeBackend() {
    const loading = document.getElementById('loadingCausa');
    const causasContainer = document.getElementById('causasContainer');
    const mensajeVacio = document.getElementById('mensajeVacioCausa');

    if (!loading || !causasContainer || !mensajeVacio) {
        return;
    }

    loading.style.display = 'block';
    causasContainer.style.display = 'none';
    mensajeVacio.style.display = 'none';

    const formData = new FormData();
    formData.append('accion', 'getAllCausasCarrusel');

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
            try {
                const causas = JSON.parse(data);
                const fundacion = JSON.parse(data);
                mostrarCausas(causas);
            } catch (e) {
            }
        })
        .catch(error => {
        });
}

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
        });
};

// CLOUDINARY FUNCIONES
function generarUrlCloudinary(publicId, transformaciones = '') {
    if (!publicId || publicId.trim() === '') {
        return generarImagenPorDefecto();
    }
    
    if (publicId.includes('res.cloudinary.com')) {
        if (transformaciones && !publicId.includes('w_')) {
            return publicId.replace('/upload/', `/upload/${transformaciones}/`);
        }
        return publicId;
    }
    
    const CLOUDINARY_CLOUD_NAME = 'dzhg8fznk';
    
    const cleanPublicId = publicId.replace(/\.(jpg|jpeg|png|gif|webp)$/i, '');
    
    let url = `https://res.cloudinary.com/${CLOUDINARY_CLOUD_NAME}/image/upload/`;
    
    if (transformaciones) {
        url += `${transformaciones}/`;
    }
    
    url += `${cleanPublicId}`;
    
    return url;
}

// EVENTOS DE LAS CARTAS

function inicializarEventosCarteleraCausas() {
    if (!document.getElementById('causasContainer')) {
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


// Función para mostrar las causas en cartas
function mostrarCausas(causas) {
    const loading = document.getElementById('loadingCausa');
    const causasContainer = document.getElementById('causasContainer');
    const mensajeVacio = document.getElementById('mensajeVacioCausa');

    loading.style.display = 'none';

    if (!causas || causas.length === 0) {
        mensajeVacio.style.display = 'block';
        mensajeVacio.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                <p class="fs-5">No hay causas disponibles en este momento.</p>
            </div>`;
        return;
    }

    causasContainer.style.display = 'flex';
    causasContainer.innerHTML = causas.map(crearCartaCausa).join('');

    inicializarEventosCartasCausas();
    animarEntradaCartas();

    document.querySelectorAll('.btn-ver-causa').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const idCausa = this.getAttribute('data-id');
            cargarDetalleCausa(idCausa);
        });
    });
}

// Función para crear HTML carta
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
        'ESTERILIZACION': 'uil uil-heart'
    }[tipoCausa?.toUpperCase()] || 'uil uil-heart';
    
    // Color según estado
    const colorEstado = {
        'ACTIVA': 'bg-success',
        'PAUSADA': 'bg-warning',
        'FINALIZADA': 'bg-secondary',
        'CANCELADA': 'bg-danger'
    }[estadoCausa?.toUpperCase()] || 'bg-info';
    
    // Generar URL de imagen 
    const imagenUrl = generarUrlCloudinary(
        causa.imagen_url || causa.public_id,
        'w_300,h_250,c_fill,g_center,q_auto,f_auto'
    );
    
    // Truncar descripción para vista previa
    const descripcionCorta = descripcion.length > 70 ? 
        descripcion.substring(0, 70) + '...' : descripcion;
    
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

    document.querySelectorAll('.btn-donar').forEach(btn => {
        btn.addEventListener('click', function() {
            const idCausa = this.getAttribute('data-id');
            mostrarModalDonacion(idCausa);
        });
    });
}

// BOTON "VER MÁS"
document.querySelectorAll('.btn-ver-causa').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const idCausa = this.getAttribute('data-id');
        cargarDetalleCausa(idCausa);
    });
});

//Cargar detalle de causa para el modal
function cargarDetalleCausa(idCausa) {
    if (!idCausa) {
        return;
    }
    mostrarLoadingModal();

    // datos para enviar al backend
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
        try {
            const resultado = JSON.parse(data);
            
            if (resultado.error) {
                mostrarErrorEnModal(resultado.error);
                return;
            }
            
            mostrarCausaCompletoEnModal(resultado.causa, resultado.fundacion);
            
        } catch (e) {
            mostrarErrorEnModal('Error al procesar la respuesta del servidor');
        }
    })
    .catch(error => {
        mostrarErrorEnModal('Error de conexión al cargar el detalle');
    });
}

// Inicializar eventos del modal de causa
function inicializarEventosModalCausa() {
    const btnDonarModal = document.querySelector('.btn-donar-modal');
    if (btnDonarModal) {
        btnDonarModal.addEventListener('click', function() {
            const idCausa = this.getAttribute('data-id');

            const modal = bootstrap.Modal.getInstance(document.getElementById('modal-causa-detalle'));
            if (modal) modal.hide();
            
            setTimeout(() => {
                mostrarModalDonacion(idCausa);
            }, 300);
        });
    }
}

// Mostrar modal de donación CON STRIPE
function mostrarModalDonacion(idCausa) {
    if (!idCausa) {
        return;
    }

    // Verificar que Stripe esté inicializado
    if (!stripe) {
        alert('Error: Sistema de pagos no disponible');
        return;
    }

    crearModalDonacion(idCausa);
}

// Crear modal de donación con Stripe
function crearModalDonacion(idCausa) {

    let modalExistente = document.getElementById('modal-donacion-causa');
    if (modalExistente) {
        modalExistente.remove();
    }

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
                            <div class="row g-2 mb-3">
                                <div class="col-4">
                                    <button type="button" class="btn btn-outline-success w-100 btn-monto-rapido" data-monto="10000">$10.000</button>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="btn btn-outline-success w-100 btn-monto-rapido" data-monto="25000">$25.000</button>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="btn btn-outline-success w-100 btn-monto-rapido" data-monto="50000">$50.000</button>
                                </div>
                            </div>
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

                        <!-- Método de pago con Stripe -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-credit-card me-1"></i>
                                Información de tarjeta
                            </label>
                            <!-- Campo de tarjeta Stripe -->
                            <div id="card-element" class="form-control p-3" style="min-height: 40px;">
                                <!-- Stripe inyecta aquí el campo de tarjeta -->
                            </div>
                            <div id="card-errors" class="text-danger small mt-2"></div>
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
                    <button type="button" class="btn btn-success btn-lg" id="btn-procesar-donacion" disabled>
                        <i class="fas fa-heart me-2"></i>
                        <span class="btn-text">Procesar Donación</span>
                        <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
`;

    document.body.insertAdjacentHTML('beforeend', modalHTML);

    const modal = new bootstrap.Modal(document.getElementById('modal-donacion-causa'));
    modal.show();

    // INICIALIZAR STRIPE DESPUÉS DE QUE SE MUESTRE EL MODAL
    modal._element.addEventListener('shown.bs.modal', function() {
        inicializarStripeElements();
        inicializarEventosModalDonacion();
    });

    document.getElementById('modal-donacion-causa').addEventListener('hidden.bs.modal', function () {
        if (cardElement) {
            cardElement.destroy();
            cardElement = null;
        }
        if (elements) {
            elements = null;
        }
        this.remove();
    });
}

// Inicializar Stripe en el modal
function inicializarStripeElements() {
    if (!stripe) {
        return;
    }

    elements = stripe.elements();

    cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#424770',
                '::placeholder': {
                    color: '#aab7c4',
                },
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        },
        hidePostalCode: true
    });

    cardElement.mount('#card-element');

    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        const btnProcesar = document.getElementById('btn-procesar-donacion');
        
        if (event.error) {
            displayError.textContent = event.error.message;
            btnProcesar.disabled = true;
        } else {
            displayError.textContent = '';

            const monto = document.getElementById('monto-donacion').value;
            btnProcesar.disabled = !monto || monto < 1000;
        }
    });
}

// Inicializar eventos del modal de donación CON STRIPE
function inicializarEventosModalDonacion() {
    document.querySelectorAll('.btn-monto-rapido').forEach(btn => {
        btn.addEventListener('click', function() {
            const monto = this.getAttribute('data-monto');
            document.getElementById('monto-donacion').value = monto;
            
            document.querySelectorAll('.btn-monto-rapido').forEach(b => {
                b.classList.remove('btn-success');
                b.classList.add('btn-outline-success');
            });
            this.classList.remove('btn-outline-success');
            this.classList.add('btn-success');

            habilitarBotonProcesar();
        });
    });

    const montoInput = document.getElementById('monto-donacion');
    if (montoInput) {
        montoInput.addEventListener('input', function() {

            document.querySelectorAll('.btn-monto-rapido').forEach(b => {
                b.classList.remove('btn-success');
                b.classList.add('btn-outline-success');
            });
            
            habilitarBotonProcesar();
        });
    }

    // Procesar donación con Stripe
    const btnProcesar = document.getElementById('btn-procesar-donacion');
    if (btnProcesar) {
        btnProcesar.addEventListener('click', function() {
            procesarDonacionStripe();
        });
    }
}

// Habilitar/deshabilitar botón según validaciones
function habilitarBotonProcesar() {
    const btnProcesar = document.getElementById('btn-procesar-donacion');
    const monto = document.getElementById('monto-donacion').value;
    const terminos = document.getElementById('terminos-donacion').checked;
    
    if (btnProcesar) {
        btnProcesar.disabled = !monto || monto < 1000 || !terminos;
    }
}

// Procesar donación con Stripe
async function procesarDonacionStripe() {
    const btnProcesar = document.getElementById('btn-procesar-donacion');
    const btnText = btnProcesar.querySelector('.btn-text');
    const spinner = btnProcesar.querySelector('.spinner-border');
    
    btnProcesar.disabled = true;
    btnText.textContent = 'Procesando...';
    spinner.classList.remove('d-none');

    try {
        // 1. Obtener datos del formulario
        const formData = new FormData(document.getElementById('form-donacion-causa'));
        const monto = parseInt(formData.get('monto'));
        const idCausa = formData.get('id_causa');
        const nombreDonante = formData.get('nombre_donante');
        const emailDonante = formData.get('email_donante');

        // Validaciones básicas
        if (!monto || monto < 1000) {
            throw new Error('El monto debe ser mínimo $1.000 COP');
        }

        if (!nombreDonante || !emailDonante) {
            throw new Error('Nombre y email son obligatorios');
        }

        // 2. Crear PaymentIntent en el backend
        const paymentData = new FormData();
        paymentData.append('accion', 'crearDonacion');
        paymentData.append('id_causa', idCausa);
        paymentData.append('monto', monto);
        paymentData.append('nombre_donante', nombreDonante);
        paymentData.append('email_donante', emailDonante);

        const response = await fetch('controller/Donacion/DonacionController.php', {
            method: 'POST',
            body: paymentData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const responseText = await response.text();

        let resultado;
        try {
            resultado = JSON.parse(responseText);
        } catch (e) {
            throw new Error('Respuesta inválida del servidor');
        }

        if (!resultado || resultado.status === 'error') {
            throw new Error(resultado?.message || 'Error al crear la donación');
        }

        // 3. Confirmar pago con Stripe
        const {error, paymentIntent} = await stripe.confirmCardPayment(resultado.clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: nombreDonante,
                    email: emailDonante
                }
            }
        });

        if (error) {
            throw new Error(error.message || 'Error al procesar el pago');
        }

        // 4. Pago exitoso
        if (paymentIntent.status === 'succeeded') {
            mostrarExitoDonacion(paymentIntent, monto);
        }

    } catch (error) {
        
    } finally {
        btnProcesar.disabled = false;
        btnText.textContent = 'Procesar Donación';
        spinner.classList.add('d-none');
    }
}

//Mostrar éxito de donación
function mostrarExitoDonacion(paymentIntent, monto) {
    const modalBody = document.querySelector('#modal-donacion-causa .modal-body');
    const modalFooter = document.querySelector('#modal-donacion-causa .modal-footer');
    const modalTitle = document.querySelector('#modal-donacion-causa .modal-title');

    modalTitle.innerHTML = '<i class="fas fa-check-circle me-2"></i>¡Donación Exitosa!';

    modalBody.innerHTML = `
        <div class="text-center py-5">
            <i class="fas fa-check-circle fa-4x text-success mb-4"></i>
            <h4 class="text-success mb-3">¡Gracias por tu donación!</h4>
            <p class="lead mb-3">Tu donación de <strong>${new Intl.NumberFormat('es-CO').format(monto)} COP</strong> ha sido procesada exitosamente.</p>
            <div class="bg-light rounded-3 p-3 mb-4">
                <small class="text-muted">ID de transacción: <strong>${paymentIntent.id}</strong></small>
            </div
        </div>
    `;

    modalFooter.innerHTML = `
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">
            <i class="fas fa-check me-1"></i>
            Entendido
        </button>
    `;
}

//Mostrar loading 
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
    
    const modal = new bootstrap.Modal(document.getElementById('modal-causa-detalle'));
    modal.show();
}

// Mostrar error en modal
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

// Mostrar causa en modal
function mostrarCausaCompletoEnModal(causa, fundacion) {
    const modalBody = document.getElementById('contenido-causa');
    
    if (!causa) {
        mostrarErrorEnModal('No se pudo cargar la información de la causa');
        return;
    }

    const imagenUrl = generarUrlCloudinary(
        causa.imagen_url || causa.public_id,
        'w_400,h_300,c_fill,g_center,q_auto,f_auto'
    );

    const imagenFundacionUrl = generarUrlCloudinary(
        fundacion?.imagen_url || fundacion?.public_id,
        'w_60,h_60,c_fill,g_face,q_auto,f_auto'
    );

    const metaFormateada = causa.meta ? 
        new Intl.NumberFormat('es-CO', { 
            style: 'currency', 
            currency: 'COP' 
        }).format(causa.meta) : 'Meta no definida';

    // Icono según tipo de causa
    const iconoCausa = {
        'ALIMENTACION': 'uil fa-utensils',
        'MEDICINA': 'uil fa-heartbeat',
        'ESTERILIZACION': 'uil fa-heart'
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
  <div class="row g-0">
    
    <!-- Imagen principal -->
    <div class="col-md-5 position-relative">
      <img src="${imagenUrl}" 
           class="img-fluid h-100 w-100 rounded-start" 
           alt="${causa.nombre}" 
           style="object-fit: cover; min-height: 100%;">
      <span class="badge ${colorEstado} position-absolute top-0 end-0 m-2 fs-6 px-3 py-2">
        ${causa.estado_causa}
      </span>
    </div>

    <!-- Contenido -->
    <div class="col-md-7">
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
               width="45" height="45"
               style="object-fit: cover;">
          <div>
            <h6 class="mb-0 fw-semibold">${fundacion.nombre}</h6>
            <small class="text-muted"><i class="fas fa-building me-1"></i>NIT: ${causa.nit_fundacion}</small>
          </div>
        </div>
        ` : ''}

        <!-- Descripción -->
        <p class="text-muted mb-4">
          ${causa.descripcion || 'Sin descripción disponible'}
        </p>

        <!-- Bloque de detalles -->
        <ul class="list-group list-group-flush mb-4">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="fw-semibold text-dark">Meta de Recaudación</span>
            <span class="fw-bold text-success">${metaFormateada}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="fw-semibold text-dark">Fecha de Creación</span>
            <span class="fw-bold text-dark">
              ${new Date(causa.fecha_creacion).toLocaleDateString('es-CO')}
            </span>
          </li>
        </ul>

      </div>
    </div>

  </div>
</div>

    `;

    modalBody.innerHTML = contenidoModal;

    inicializarEventosModalCausa();
    
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

document.addEventListener("DOMContentLoaded", () => {
    inicializarStripe();

    const botones = document.querySelectorAll(".btn-cargar-cartelCausa");
    botones.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            cargarCarteleraCausas();
        });
    });

    document.addEventListener('change', function(e) {
        if (e.target && e.target.id === 'terminos-donacion') {
            habilitarBotonProcesar();
        }
    });
});