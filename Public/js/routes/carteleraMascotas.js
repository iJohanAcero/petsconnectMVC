window.cargarCarteleraMascotas = function () {
    fetch("view/cartelera/MascotasCartelera.php")
        .then(response => {
            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            const mainContainer = document.getElementById("main-content");

            if (mainContainer) {
                mainContainer.innerHTML = data;

                setTimeout(() => {
                    inicializarEventosCarteleraMascotas();
                    cargarMascotasDesdeBackend();
                    inicializarFiltrosMascotas();
                }, 100);
            }
        })
        .catch(error => {
            console.error("⚠️ Error al cargar PHP:", error);
            mostrarErrorCarga();
        });
};

// Función para generar URL de Cloudinary
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

function inicializarEventosCarteleraMascotas() {
    if (!document.getElementById('mascotasContainer')) {
        console.warn("Elementos de cartelera no encontrados");
        return;
    }
}

// Función para inicializar filtros de mascotas
function inicializarFiltrosMascotas() {
    // Eventos para filtros si existen en el HTML
    const filtroEspecie = document.getElementById('filtroEspecie');
    const filtroTamano = document.getElementById('filtroTamano');
    const filtroEdad = document.getElementById('filtroEdad');
    const filtroGenero = document.getElementById('filtroGenero');
    const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');
    const btnAplicarFiltros = document.getElementById('btnAplicarFiltros');

    if (btnAplicarFiltros) {
        btnAplicarFiltros.addEventListener('click', aplicarFiltrosMascotas);
    }

    if (btnLimpiarFiltros) {
        btnLimpiarFiltros.addEventListener('click', limpiarFiltrosMascotas);
    }

    // Filtrado en tiempo real (opcional)
    [filtroEspecie, filtroTamano, filtroEdad, filtroGenero].forEach(filtro => {
        if (filtro) {
            filtro.addEventListener('change', aplicarFiltrosMascotas);
        }
    });
}

// Función para aplicar filtros
function aplicarFiltrosMascotas() {
    const filtroEspecie = document.getElementById('filtroEspecie')?.value || '';
    const filtroEdad = document.getElementById('filtroEdad')?.value || '';
    const filtroGenero = document.getElementById('filtroGenero')?.value || '';

    const params = new URLSearchParams();
    if (filtroEspecie) params.append('especie', filtroEspecie);
    if (filtroEdad) params.append('edad', filtroEdad);
    if (filtroGenero) params.append('genero', filtroGenero);

    const loading = document.getElementById('loading');
    const mascotasContainer = document.getElementById('mascotasContainer');

    if (loading) loading.style.display = 'block';
    if (mascotasContainer) mascotasContainer.style.display = 'none';

    fetch(`controller/Mascota/MascotaController.php?action=filtrarMascotas&${params.toString()}`)
        .then(response => response.json())
        .then(mascotas => {
            mostrarMascotas(mascotas);
        })
        .catch(error => {
            console.error("Error al filtrar mascotas:", error);
            mostrarErrorMascotas();
        });
}

// Función para limpiar filtros
function limpiarFiltrosMascotas() {
    const filtros = ['filtroEspecie', 'filtroEdad', 'filtroGenero'];
    filtros.forEach(filtroId => {
        const filtro = document.getElementById(filtroId);
        if (filtro) filtro.value = '';
    });
    
    cargarMascotasDesdeBackend();
}

// Función para cargar mascotas desde el backend
function cargarMascotasDesdeBackend() {
    const loading = document.getElementById('loading');
    const mascotasContainer = document.getElementById('mascotasContainer');
    const mensajeVacio = document.getElementById('mensajeVacio');

    // Verificar que los elementos existan
    if (!loading || !mascotasContainer || !mensajeVacio) {
        console.error("⚠️ Elementos del DOM no encontrados para cartelera");
        return;
    }

    // Mostrar loading
    loading.style.display = 'block';
    mascotasContainer.style.display = 'none';
    mensajeVacio.style.display = 'none';

    fetch('controller/Mascota/MascotaController.php?action=getAllMascotasCarrusel')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(data => {
            if (!data || data.trim() === '') {
                mostrarErrorMascotas();
                return;
            }
            
            try {
                const mascotas = JSON.parse(data);
                mostrarMascotas(mascotas);
            } catch (e) {
                mostrarErrorMascotas();
            }
        })
        .catch(error => {
            console.error("⚠️ Error completo al cargar mascotas:", error);
            mostrarErrorMascotas();
        });
}

// Función para mostrar las mascotas en cartas
function mostrarMascotas(mascotas) {
    const loading = document.getElementById('loading');
    const mascotasContainer = document.getElementById('mascotasContainer');
    const mensajeVacio = document.getElementById('mensajeVacio');
    
    loading.style.display = 'none';

    if (!mascotas || mascotas.length === 0) {
        mensajeVacio.style.display = 'block';
        mensajeVacio.innerHTML = `
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-paw fa-3x mb-3 text-info"></i>
                    <h4>No hay mascotas disponibles</h4>
                    <p class="mb-0">No se encontraron mascotas que coincidan con los filtros seleccionados.</p>
                </div>
            </div>
        `;
        return;
    }

    // Mostrar container y actualizar contador
    mascotasContainer.style.display = 'flex';
    mascotasContainer.innerHTML = mascotas.map(crearCartaMascota).join('');

    // Inicializar eventos de las cartas
    inicializarEventosCartas();
    
    // Animación de entrada
    animarEntradaCartas();
}

// Función para crear HTML de cada carta mascota
function crearCartaMascota(mascota) {
    const nombre = mascota.nombre;
    const especie = mascota.especie;
    const edadMeses = mascota.edad_meses;
    const sexo = mascota.sexo;
    const tipoEstado = mascota.tipo_estado;
    const nombreFundacion = mascota.nombre_fundacion;
    const categoriaEdad = mascota.categoria_edad || 'adulto';

    // Calcular edad legible
    const edadTexto = edadMeses < 12 
        ? `${edadMeses} meses`
        : `${Math.floor(edadMeses / 12)} años`;

    // Icono según especie
    const iconoEspecie = especie.toLowerCase() === 'canino' ? 'fas fa-dog' : 'fas fa-cat';

    // Color del badge según estado
    const colorEstado = tipoEstado === 'EN ADOPCION' ? 'bg-success' : 'bg-warning';

    // Generar URL de imagen optimizada
    const imagenUrl = generarUrlCloudinary(
        mascota.imagen || mascota.public_id,
        'w_300,h_250,c_fill,g_center,q_auto,f_auto'
    );

    return `
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="card h-100 shadow-sm carta-mascota" data-id="${mascota.id_mascota}">
                <div class="position-relative">
                    <img src="${imagenUrl}" 
                        class="card-img-top" 
                        alt="${nombre}"
                        style="height: 250px; object-fit: cover;">
                    
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge ${colorEstado}">${tipoEstado}</span>
                    </div>

                </div>
                
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title text-primary2 mb-0">
                            <i class="${iconoEspecie} me-2"></i>
                            ${nombre}
                        </h5>
                        <h5 class="text-muted">${sexo}</h5>
                    </div>
                    
                    <div class="mb-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted d-block">Edad</small>
                                <span class="badge bg-light text-dark">${edadTexto}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3 flex-grow-1">
                        <small class="text-muted d-block mb-1">Fundación</small>
                        <p class="card-text small">
                            <i class="fas fa-home me-1"></i>
                            ${nombreFundacion}
                        </p>
                    </div>
                    
                    <div class="mt-auto">
                        <div class="d-flex gap-2">
                            <button type="button" 
                                    class="btn btn-outline-primary2 btn-sm flex-fill btn-ver-perfil" 
                                    data-id="${mascota.id_mascota}">
                                <i class="fas fa-eye me-1"></i>
                                Ver perfil
                            </button>
                            
                            <button type="button" 
                                    class="btn btn-primary2 btn-sm flex-fill btn-adoptar" 
                                    data-id="${mascota.id_mascota}">
                                <i class="fas fa-heart me-1"></i>
                                Adoptar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Función para inicializar eventos de las cartas
function inicializarEventosCartas() {
    // Eventos para botones "Ver perfil"
    document.querySelectorAll('.btn-ver-perfil').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            verPerfilMascota(id);
        });
    });

    // Eventos para botones "Adoptar"
    document.querySelectorAll('.btn-adoptar').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            mostrarModalAdopcion(id);
        });
    });

    // Evento click en toda la carta (opcional)
    document.querySelectorAll('.carta-mascota').forEach(carta => {
        carta.addEventListener('click', function(e) {
            // Solo si no se clickeó un botón
            if (!e.target.classList.contains('btn') && !e.target.closest('.btn')) {
                const id = this.getAttribute('data-id');
                verPerfilMascota(id);
            }
        });
    });
}

// Función para ver perfil completo de la mascota
function verPerfilMascota(id) {
    const modalPerfil = document.getElementById('modal-perfil-mascota');
    const modalBody = document.getElementById('contenido-perfil-mascota');
    
    if (!modalPerfil || !modalBody) {
        console.error('Modal de perfil no encontrado');
        return;
    }

    // Mostrar loading
    modalBody.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary2" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="mt-3">Cargando información de la mascota...</p>
        </div>
    `;

    // Mostrar modal
    const bsModal = new bootstrap.Modal(modalPerfil);
    bsModal.show();

    // Cargar datos del perfil completo
    fetch(`controller/Mascota/MascotaController.php?action=getPerfilCompleto&id=${id}`)
        .then(response => response.json())
        .then(mascota => {
            if (mascota && mascota.id_mascota) {
                mostrarPerfilCompletoEnModal(mascota);
            } else {
                mostrarErrorPerfilModal('No se encontró información de la mascota');
            }
        })
        .catch(error => {
            console.error('Error al cargar perfil:', error);
            mostrarErrorPerfilModal('Error al cargar la información');
        });
}

// Función para mostrar perfil completo en modal
function mostrarPerfilCompletoEnModal(mascota) {
    const modalBody = document.getElementById('contenido-perfil-mascota');

    // Calcular edad legible
    const edadMeses = mascota.edad_meses || 0;
    const edadTexto = edadMeses < 12 
        ? `${edadMeses} meses`
        : `${Math.floor(edadMeses / 12)} años y ${edadMeses % 12} meses`;

    // Generar imagen optimizada
    const imagenUrl = generarUrlCloudinary(
        mascota.imagen || mascota.public_id,
        'w_400,h_300,c_fill,g_center,q_auto,f_auto'
    );

    const imagenFundacionUrl = generarUrlCloudinary(
        mascota.fundacion_imagen,
        'w_60,h_60,c_fill,g_face,q_auto,f_auto'
    );

    const contenidoModal = `
        <div class="row">
            <!-- Imagen principal -->
            <div class="col-lg-5 mb-4">
                <img src="${imagenUrl}" 
                     class="img-fluid rounded shadow" 
                     alt="${mascota.nombre}"
                     style="width: 100%; height: 300px; object-fit: cover;"
                     onerror="this.src='${generarImagenPorDefecto()}'">
            </div>
            
            <!-- Información principal -->
            <div class="col-lg-7">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h3 class="text-primary2 mb-1">
                            <i class="fas fa-${mascota.especie === 'Canino' ? 'dog' : 'cat'} me-2"></i>
                            ${mascota.nombre}
                        </h3>
                        <span class="badge ${mascota.tipo_estado === 'EN ADOPCION' ? 'bg-success' : 'bg-warning'} mb-2">
                            ${mascota.tipo_estado}
                        </span>
                    </div>
                </div>

                <!-- Información básica -->
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="card bg-light border-0">
                            <div class="card-body p-2 text-center">
                                <i class="fas fa-calendar text-primary2 mb-1"></i>
                                <div class="small text-muted">Edad</div>
                                <div class="fw-bold">${edadTexto}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-light border-0">
                            <div class="card-body p-2 text-center">
                                <i class="fas fa-venus-mars text-primary2 mb-1"></i>
                                <div class="small text-muted">Sexo</div>
                                <div class="fw-bold">${mascota.sexo}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-light border-0">
                            <div class="card-body p-2 text-center">
                                <i class="fas fa-ruler text-primary2 mb-1"></i>
                                <div class="small text-muted">Tamaño</div>
                                <div class="fw-bold">${mascota.tamano_estimado || 'Mediano'}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-light border-0">
                            <div class="card-body p-2 text-center">
                                <i class="fas fa-tag text-primary2 mb-1"></i>
                                <div class="small text-muted">Categoría</div>
                                <div class="fw-bold">${mascota.categoria_edad}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de la fundación -->
                <div class="card border-0 bg-light mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <img src="${imagenFundacionUrl}" 
                                 class="rounded-circle me-3" 
                                 style="width: 50px; height: 50px; object-fit: cover;"
                                 alt="${mascota.nombre_fundacion}"
                                 onerror="this.src='${generarImagenPorDefecto()}'">
                            <div>
                                <h6 class="mb-0">${mascota.nombre_fundacion}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    modalBody.innerHTML = contenidoModal;

    // Inicializar eventos del modal
    inicializarEventosModalPerfil();
}

// Función para inicializar eventos del modal de perfil
function inicializarEventosModalPerfil() {

}

// Función para mostrar modal de adopción
function mostrarModalAdopcion(idMascota) {

}

// Función para mostrar formulario de adopción
function mostrarFormularioAdopcion(mascota) {
}

// Función para enviar solicitud de adopción
function enviarSolicitudAdopcion() {
}

// Función para animar entrada de cartas
function animarEntradaCartas() {
    const cartas = document.querySelectorAll('.carta-mascota');
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

// Inicialización de eventos del DOM
document.addEventListener("DOMContentLoaded", () => {
    const botones = document.querySelectorAll(".btn-cargar-cartelMascota");

    botones.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            cargarCarteleraMascotas();
        });
    });
});