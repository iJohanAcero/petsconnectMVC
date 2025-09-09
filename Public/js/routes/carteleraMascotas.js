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
                    inicializarFormularioAdopcion();
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
    
    const CLOUDINARY_CLOUD_NAME = 'dzhg8fznk';
    
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
    const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');
    const btnAplicarFiltros = document.getElementById('btnAplicarFiltros');

    if (btnAplicarFiltros) {
        btnAplicarFiltros.addEventListener('click', aplicarFiltrosMascotas);
    }

    if (btnLimpiarFiltros) {
        btnLimpiarFiltros.addEventListener('click', limpiarFiltrosMascotas);
    }
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

    const loading = document.getElementById('loadingMascota');
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
    const loading = document.getElementById('loadingMascota');
    const mascotasContainer = document.getElementById('mascotasContainer');
    const mensajeVacio = document.getElementById('mensajeVacioMascota');

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
    const loading = document.getElementById('loadingMascota');
    const mascotasContainer = document.getElementById('mascotasContainer');
    const mensajeVacio = document.getElementById('mensajeVacioMascota');

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

    inicializarEventosCartasMascotas();
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

    // Calcular edad legible
    const edadTexto = edadMeses < 12 
        ? `${edadMeses} meses`
        : `${Math.floor(edadMeses / 12)} años`;

    // Icono según especie
    const iconoEspecie = especie.toLowerCase() === 'canino' ? 'fas fa-dog' : 'fas fa-cat';

    // Color según estado
    const colorEstado = tipoEstado === 'EN ADOPCION' ? 'bg-success' : 'bg-warning';

    // Generar URL de imagen
    const imagenUrl = generarUrlCloudinary(
        mascota.imagen || mascota.public_id,
        'w_300,h_250,c_fill,g_center,q_auto,f_auto'
    );

    return `
<div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-3">
    <div class="card h-100 shadow-lg border-0 carta-mascota" data-id="${mascota.id_mascota}">
        <div class="position-relative overflow-hidden">
            <img src="${imagenUrl}"
                class="card-img-top"
                alt="${nombre}"
                style="height: 250px; object-fit: cover;">
           
            <div class="position-absolute top-0 end-0 m-3">
                <span class="badge ${colorEstado} fs-6 px-3 py-2">${tipoEstado}</span>
            </div>
        </div>
       
        <div class="card-body p-4">
            <!-- Nombre con icono -->
            <div class="text-center mb-3">
                <h4 class="card-title text-primary2 mb-1 fw-bold">
                    <i class="${iconoEspecie} me-2 fs-4"></i>
                    ${nombre}
                </h4>
            </div>
            
            <!-- Información principal destacada -->
            <div class="row text-center mb-4">
                <div class="col-6">
                    <div class="border-end">
                        <div class="fs-5 fw-bold text-primary2">${edadTexto}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="fs-5 fw-bold text-primary2">${sexo}</div>
                </div>
            </div>
            <!-- Botones de acción -->
            <div class="d-grid gap-2">
                <div class="row g-2">
                    <div class="col-6">
                        <button type="button"
                                class="btn btn-outline-primary2 w-100 btn-ver-perfil"
                                data-id="${mascota.id_mascota}">
                            <i class="fas fa-eye me-1"></i>
                            <small>Ver perfil</small>
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button"
                                class="btn btn-primary2 w-100 btn-adoptar"
                                data-id="${mascota.id_mascota}">
                            <i class="fas fa-heart me-1"></i>
                            <small>Adoptar</small>
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
function inicializarEventosCartasMascotas() {
    // Eventos para botones "Ver perfil"
    document.querySelectorAll('.btn-ver-perfil').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            verPerfilMascota(id);
        });
    });

    // EVENTOS PARA BOTONES "ADOPTAR"
    document.querySelectorAll('.btn-adoptar').forEach(btn => {
        btn.addEventListener('click', function() {
            const idMascota = this.getAttribute('data-id');
            mostrarModalAdopcion(idMascota);
        });
    });

    // Evento click en toda la carta
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
function mostrarPerfilCompletoEnModal(mascota, fundacion) {
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
        mascota.fundacion_imagen || fundacion?.imagen,
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

                <!-- BOTÓN DE ADOPTAR EN EL MODAL PERFIL -->
                <div class="d-grid">
                    <button type="button" 
                            class="btn btn-primary2 btn-lg btn-adoptar-modal" 
                            data-id="${mascota.id_mascota}">
                        <i class="fas fa-heart me-2"></i>
                        ¡Quiero adoptarlo!
                    </button>
                </div>
            </div>
        </div>
    `;

    modalBody.innerHTML = contenidoModal;

    // AGREGAR EVENTO AL BOTÓN DE ADOPTAR DEL MODAL PERFIL
    const btnAdoptarModal = document.querySelector('.btn-adoptar-modal');
    if (btnAdoptarModal) {
        btnAdoptarModal.addEventListener('click', function() {
            const idMascota = this.getAttribute('data-id');
            mostrarModalAdopcion(idMascota);
        });
    }
}

// FUNCIÓN PARA INICIALIZAR FORMULARIO DE ADOPCIÓN
function inicializarFormularioAdopcion() {
    const formRegistrar = document.getElementById("form-registrar-adopcion");

    if (formRegistrar) {
        formRegistrar.onsubmit = function (e) {
            e.preventDefault();
            const formData = new FormData(formRegistrar);

            fetch(`${window.BASE_URL}/controller/adopcion/AdopcionController.php`, {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    if (data.toLowerCase().includes("correctamente")) {
                        alert(data);

                        // Cerrar modal de adopción
                        const modalElement = document.getElementById("modalAdopcion") || document.getElementById("modal-adopcion");
                        if (modalElement) {
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) modal.hide();
                        }

                        // Limpiar formulario
                        formRegistrar.reset();
                        
                        // Recargar las mascotas para actualizar estados
                        cargarMascotasDesdeBackend();
                    } else {
                        alert("Error: " + data);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Error al procesar la adopción. Inténtalo de nuevo.");
                });
        };
    }
}

// FUNCIÓN PARA MOSTRAR MODAL DE ADOPCIÓN
function mostrarModalAdopcion(idMascota) {
    // Cerrar el modal de perfil si está abierto
    const modalPerfil = document.getElementById('modal-perfil-mascota');
    if (modalPerfil) {
        const bsModalPerfil = bootstrap.Modal.getInstance(modalPerfil);
        if (bsModalPerfil) bsModalPerfil.hide();
    }
    
    // Abrir el modal de adopción
    const modalAdopcion = document.getElementById("modalAdopcion") || document.getElementById("modal-adopcion");
    if (modalAdopcion) {
        const bsModal = new bootstrap.Modal(modalAdopcion);
        bsModal.show();
        
        // Asignar el ID de la mascota al campo hidden del formulario
        setTimeout(() => {
            const inputIdMascota = document.getElementById('id_mascota') || document.querySelector('input[name="id_mascota"]');
            if (inputIdMascota) {
                inputIdMascota.value = idMascota;
            }
            
            // Reinicializar el formulario por si acaso
            inicializarFormularioAdopcion();
        }, 100);
    }
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

//  INICIALIZACIÓN DE EVENTOS DEL DOM 
document.addEventListener("DOMContentLoaded", () => {
    const botones = document.querySelectorAll(".btn-cargar-cartelMascota");

    botones.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            cargarCarteleraMascotas();
        });
    });
    
    inicializarFormularioAdopcion();
});