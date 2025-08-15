// carteleraFundacion.js - Actualizado para manejo de cartas de fundaciones

window.cargarCarteleraFundacion = function () {
    fetch("view/cartelera/FundacionesCartelera.php")
        .then(response => {
            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            const mainContainer = document.getElementById("main-content");

            if (mainContainer) {
                mainContainer.innerHTML = data;

                // Esperar a que el HTML se cargue antes de inicializar eventos
                setTimeout(() => {
                    inicializarEventosCarteleraFundacion();
                    cargarFundacionesDesdeBackend();
                }, 100);
            }
        })
        .catch(error => {
            console.error("❌ Error al cargar PHP:", error);
            mostrarErrorCarga();
        });
};

function inicializarEventosCarteleraFundacion() {
    if (!document.getElementById('fundacionesContainer')) {
        console.warn("Elementos de cartelera no encontrados");
        return;
    }
    
}

// Función para cargar fundaciones desde el backend
function cargarFundacionesDesdeBackend() {
    const loading = document.getElementById('loading');
    const fundacionesContainer = document.getElementById('fundacionesContainer');
    const mensajeVacio = document.getElementById('mensajeVacio');

    // Verificar que los elementos existan
    if (!loading || !fundacionesContainer || !mensajeVacio) {
        console.error("❌ Elementos del DOM no encontrados para cartelera");
        return;
    }

    // Mostrar loading
    loading.style.display = 'block';
    fundacionesContainer.style.display = 'none';
    mensajeVacio.style.display = 'none';

    
    fetch('controller/Fundacion/FundacionController.php?action=getAllFundacionesCarrusel')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text(); // Cambiar temporalmente a text() para ver qué devuelve
        })
        .then(data => {
            if (!data || data.trim() === '') {
                mostrarErrorFundaciones();
                return;
            }
            
            try {
                const fundaciones = JSON.parse(data);
                mostrarFundaciones(fundaciones);
            } catch (e) {
                mostrarErrorFundaciones();
            }
        })
        .catch(error => {
            console.error("❌ Error completo al cargar fundaciones:", error);
            mostrarErrorFundaciones();
        });
}

// Función para mostrar las fundaciones en cartas
function mostrarFundaciones(fundaciones) {
    const loading = document.getElementById('loading');
    const fundacionesContainer = document.getElementById('fundacionesContainer');
    const mensajeVacio = document.getElementById('mensajeVacio');
    
    loading.style.display = 'none';
    
    if (!fundaciones || fundaciones.length === 0) {
        mensajeVacio.style.display = 'block';
        return;
    }

    // Mostrar container y actualizar contador
    fundacionesContainer.style.display = 'flex';
    fundacionesContainer.innerHTML = fundaciones.map(crearCartaFundacion).join('');

    // Inicializar eventos de las cartas
    inicializarEventosCartas();
    
    // Animación de entrada
    animarEntradaCartas();
}

// Función para crear HTML de cada carta
function crearCartaFundacion(fundacion) {
    // Validar que la fundación tenga los campos necesarios
    const nombre = fundacion.nombre || 'Nombre no disponible';
    const descripcion = fundacion.descripcion || 'Descripción no disponible';
    const idPerfil = fundacion.id_perfil || fundacion.idPerfil || 0;
    let imagenUrl = '';
    if (fundacion.imagen && fundacion.imagen.trim() !== '') {
        // Ajusta esta ruta según donde tengas guardadas las imágenes
        imagenUrl = `${window.BASE_URL}/Public/images/perfil/${fundacion.imagen}`;
    }

    return `
        <div class="col-lg-4 col-md-6 col-sm-12 ">
            <div class="card h-100 shadow-sm carta-fundacion" data-id="${idPerfil}">
                <img src="${imagenUrl}" 
                    class="card-img-top" 
                    alt="${nombre}"
                    style="height: 200px; object-fit: cover;"
                    onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22200%22><rect width=%22300%22 height=%22200%22 fill=%22%23e9ecef%22/><text x=%2250%%22 y=%2250%%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%236c757d%22 font-size=%2214%22>Sin imagen</text></svg>'">
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-primary2 mb-3">
                        <i class="fas fa-heart me-2"></i>
                        ${nombre}
                    </h5>
                    
                    <p class="card-text text-muted flex-grow-1">
                        ${descripcion}
                    </p>
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-id-badge me-1"></i>
                                ID: ${idPerfil}
                            </small>
                            
                            <div class="btn-group">
                                <button type="button" 
                                        class="btn btn-outline-primary2 btn-sm btn-ver-detalles" 
                                        data-id="${idPerfil}">
                                    <i class="fas fa-eye me-1"></i>
                                    Ver perfil
                                </button>
                                
                                <button type="button" 
                                        class="btn btn-primary2 btn-sm btn-contactar" 
                                        data-id="${idPerfil}">
                                    <i class="fas fa-envelope me-1"></i>
                                    Contactar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Función para inicializar eventos de las cartas
function inicializarEventosCartas() {
    // Eventos para botones "Ver más"
    document.querySelectorAll('.btn-ver-detalles').forEach(btn => {
        btn.addEventListener('click', function() {
            const idPerfil = this.getAttribute('data-id');
            verDetallesFundacion(idPerfil);
        });
    });

    // Eventos para botones "Contactar"
    document.querySelectorAll('.btn-contactar').forEach(btn => {
        btn.addEventListener('click', function() {
            const idPerfil = this.getAttribute('data-id');
            contactarFundacion(idPerfil);
        });
    });

    // Evento click en toda la carta (opcional)
    document.querySelectorAll('.carta-fundacion').forEach(carta => {
        carta.addEventListener('click', function(e) {
            // Solo si no se clickeó un botón
            if (!e.target.classList.contains('btn') && !e.target.closest('.btn')) {
                const idPerfil = this.getAttribute('data-id');
                verDetallesFundacion(idPerfil);
            }
        });
    });
}

// Función para ver detalles de una fundación
function verDetallesFundacion(idPerfil) {
    
    // Aquí puedes implementar:
    // 1. Abrir un modal con más información
    // 2. Navegar a una página de detalles
    // 3. Hacer otra llamada AJAX para obtener más datos
    
    // Ejemplo básico con modal de Bootstrap
    if (typeof bootstrap !== 'undefined') {
        const modal = document.getElementById('modalDetalles');
        if (modal) {
            cargarDetallesModal(idPerfil);
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
    }
}

// Función para contactar una fundación
function contactarFundacion(idPerfil) {
    
    const modalContacto = document.getElementById('modalContacto');
    const modalContactoBody = document.getElementById('modalContactoBody');
    const modalContactoLabel = document.getElementById('modalContactoLabel');
    
    if (!modalContacto || !modalContactoBody) {
        alert('Error: Modal de contacto no encontrado');
        return;
    }
    
    // Mostrar loading en el modal
    modalContactoBody.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary2" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-3">Obteniendo información de contacto...</p>
        </div>
    `;
    
    // Mostrar el modal
    const bsModal = new bootstrap.Modal(modalContacto);
    bsModal.show();
    
    // Llamada para obtener información de contacto
    fetch(`controller/Fundacion/FundacionController.php?action=getContactoFundacion&id=${idPerfil}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(fundacion => {
            modalContactoLabel.innerHTML = `
                <i class="fas fa-envelope me-2"></i>
                Contactar: ${fundacion.nombre || 'Fundación'}
            `;
            
            modalContactoBody.innerHTML = `
                <div class="text-center mb-4">
                    <img src="Public/images/perfil/${fundacion.imagen}" 
                         class="rounded-circle mb-3" 
                         alt="${fundacion.nombre}"
                         style="width: 80px; height: 80px; object-fit: cover;"
                         onerror="this.style.display='none'">
                    <h4 class="text-primary2">${fundacion.nombre}</h4>
                </div>
                
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-envelope text-primary2 me-2"></i>
                                    Correo Electrónico
                                </h6>
                                <p class="card-text">
                                    <a href="mailto:${fundacion.email}" class="text-decoration-none">
                                        ${fundacion.email}
                                    </a>
                                </p>
                                <button class="btn btn-outline-primary2 btn-sm" onclick="copiarTexto('${fundacion.email}')">
                                    <i class="fas fa-copy me-1"></i>
                                    Copiar correo
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-12 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-phone text-success me-2"></i>
                                    Teléfono
                                </h6>
                                <p class="card-text">
                                    <a href="tel:${fundacion.telefono}" class="text-decoration-none">
                                        ${fundacion.telefono}
                                    </a>
                                </p>
                                <button class="btn btn-outline-primary2 btn-sm" onclick="copiarTexto('${fundacion.telefono}')">
                                    <i class="fas fa-copy me-1"></i>
                                    Copiar teléfono
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    ${fundacion.direccion ? `
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-map-marker-alt text-info me-2"></i>
                                    Dirección
                                </h6>
                                <p class="card-text">${fundacion.direccion}</p>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                </div>
            `;
        })
        .catch(error => {
            console.error('Error al cargar información de contacto:', error);
            modalContactoBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error al cargar la información de contacto. Por favor, intenta de nuevo.
                </div>
            `;
        });
}

// Función auxiliar para copiar texto al portapapeles
function copiarTexto(texto) {
    navigator.clipboard.writeText(texto).then(function() {
        // Mostrar mensaje de éxito
        const toast = document.createElement('div');
        toast.className = 'toast-message';
        toast.innerHTML = `
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check me-2"></i>
                Copiado al portapapeles: ${texto}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        document.body.appendChild(toast);
        
        // Remover el mensaje después de 3 segundos
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }).catch(function(err) {
        console.error('Error al copiar: ', err);
        alert('No se pudo copiar al portapapeles');
    });
}

// Función para cargar detalles en el modal
function cargarDetallesModal(idPerfil) {
    const modalBody = document.getElementById('modalDetallesBody');
    const modalTitle = document.getElementById('modalDetallesLabel');
    
    if (!modalBody) return;
    
    // Mostrar loading en el modal
    modalBody.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary2" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-3">Cargando detalles...</p>
        </div>
    `;
    
    // Llamada para obtener detalles específicos
    fetch(`/petsconnectmvc/index.php?controller=fundacion&action=getDetallesFundacion&id=${idPerfil}`)
        .then(response => response.json())
        .then(fundacion => {
            modalTitle.textContent = fundacion.nombre || 'Detalles de la Fundación';
            modalBody.innerHTML = `
                <div class="text-center mb-4">
                    <img src="${fundacion.imagen}" 
                         class="img-fluid rounded" 
                         alt="${fundacion.nombre}"
                         style="max-height: 300px; object-fit: cover;"
                         onerror="this.style.display='none'">
                </div>
                <h4 class="text-primary2 mb-3">
                    <i class="fas fa-heart me-2"></i>
                    ${fundacion.nombre}
                </h4>
                <p class="lead">${fundacion.descripcion}</p>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <strong>ID Perfil:</strong> ${fundacion.id_perfil}
                    </div>
                    <div class="col-6 text-end">
                        <button class="btn btn-primary" onclick="contactarFundacion(${fundacion.id_perfil})">
                            <i class="fas fa-envelope me-1"></i>
                            Contactar
                        </button>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error al cargar detalles:', error);
            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error al cargar los detalles de la fundación.
                </div>
            `;
        });
}

// Función para animar entrada de cartas
function animarEntradaCartas() {
    const cartas = document.querySelectorAll('.carta-fundacion');
    cartas.forEach((carta, index) => {
        setTimeout(() => {
            carta.style.opacity = '0';
            carta.style.transform = 'translateY(20px)';
            carta.style.transition = 'all 0.5s ease';
            
            setTimeout(() => {
                carta.style.opacity = '1';
                carta.style.transform = 'translateY(0)';
            }, 100);
        }, index * 100);
    });
}

// Función para mostrar error al cargar fundaciones
function mostrarErrorFundaciones() {
    const loading = document.getElementById('loading');
    const fundacionesContainer = document.getElementById('fundacionesContainer');
    const mensajeVacio = document.getElementById('mensajeVacio');
    
    loading.style.display = 'none';
    fundacionesContainer.style.display = 'none';
    
    if (mensajeVacio) {
        mensajeVacio.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3 text-danger"></i>
                    <h4>Error al cargar las fundaciones</h4>
                    <p class="mb-3">No se pudieron cargar las fundaciones. Por favor, intenta de nuevo.</p>
                    <button class="btn btn-primary" onclick="cargarFundacionesDesdeBackend()">
                        <i class="fas fa-redo me-1"></i>
                        Reintentar
                    </button>
                </div>
            </div>
        `;
        mensajeVacio.style.display = 'block';
    }
}

// Función para mostrar error al cargar la vista completa
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
                            <p class="mb-3">No se pudo cargar la cartelera de fundaciones.</p>
                            <button class="btn btn-primary" onclick="cargarCarteleraFundacion()">
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

// Inicialización de eventos del DOM
document.addEventListener("DOMContentLoaded", () => {
    const botones = document.querySelectorAll(".btn-cargar-cartelFundacion");

    botones.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            cargarCarteleraFundacion();
        });
    });
});