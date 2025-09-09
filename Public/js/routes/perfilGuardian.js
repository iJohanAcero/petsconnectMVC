window.cargarPerfilGuardian = function () {
    fetch("view/guardian/perfilGuardian.php")
        .then(response => {
            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            const mainContainer = document.getElementById("main-content");

            if (mainContainer) {
                mainContainer.innerHTML = data;

                setTimeout(() => {
                    inicializarEventosPerfilGuardian();
                }, 100);
            }
        })
        .catch(error => {
            console.error("⚠ Error al cargar PHP:", error);
        });
};

function abrirModalGuardian() {
    const modalElement = document.getElementById("modal-guardian");
    if (modalElement) {
        const modalBootstrap = new bootstrap.Modal(modalElement);
        modalBootstrap.show();
    }
}

// ===================== FUNCIONES AUXILIARES ===================== //

// Función para preview de imagen con validaciones
function previewImagen(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validar tamaño (5MB)
        if (file.size > 5 * 1024 * 1024) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Archivo muy grande',
                    text: 'La imagen no puede superar los 5MB',
                    confirmButtonColor: '#ffc107'
                });
            } else {
                alert('La imagen no puede superar los 5MB');
            }
            input.value = '';
            return;
        }
        
        // Validar tipo
        const tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!tiposPermitidos.includes(file.type)) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Formato no válido',
                    text: 'Solo se permiten archivos JPG, PNG y GIF',
                    confirmButtonColor: '#ffc107'
                });
            } else {
                alert('Solo se permiten archivos JPG, PNG y GIF');
            }
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('preview-imagen');
            if (previewImg) {
                previewImg.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
    }
}

// Función para configurar contador de caracteres
function configurarContadorCaracteres() {
    const descripcionTextarea = document.getElementById('descripcion');
    const contadorCaracteres = document.getElementById('caracteres-actuales');
    const contenedorContador = document.getElementById('contador-caracteres');

    if (descripcionTextarea && contadorCaracteres && contenedorContador) {
        descripcionTextarea.addEventListener('input', function() {
            const actual = this.value.length;
            contadorCaracteres.textContent = actual;
            
            // Cambiar colores según cantidad de caracteres
            if (actual > 250) {
                contenedorContador.classList.add('text-warning');
                contenedorContador.classList.remove('text-muted');
            } else if (actual > 280) {
                contenedorContador.classList.add('text-danger');
                contenedorContador.classList.remove('text-warning', 'text-muted');
            } else {
                contenedorContador.classList.remove('text-warning', 'text-danger');
                contenedorContador.classList.add('text-muted');
            }
        });
    }
}

// Función para configurar eventos del formulario
function configurarEventosFormulario() {
    // Configurar preview de imagen
    const inputImagen = document.getElementById("input-imagen");
    if (inputImagen) {
        inputImagen.addEventListener("change", function () {
            previewImagen(this);
        });
    }

    // Configurar contador de caracteres
    configurarContadorCaracteres();

    // Configurar envío del formulario
    const formEditar = document.getElementById("form-editar-perfilGuardian");
    if (formEditar) {
        formEditar.addEventListener("submit", function (e) {
            e.preventDefault();
            
            // Validar formulario
            if (!formEditar.checkValidity()) {
                formEditar.classList.add('was-validated');
                return;
            }

            const formData = new FormData(formEditar);
            const btnGuardar = document.getElementById('btn-guardar');
            
            // Mostrar estado de carga en el botón
            if (btnGuardar) {
                btnGuardar.disabled = true;
                btnGuardar.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Guardando...';
            }

            fetch(`${window.BASE_URL}/controller/perfil/PerfilController.php`, {
                method: "POST",
                body: formData
            })
            .then(res => {
                const contentType = res.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    return res.json();
                } else {
                    return res.text().then(text => ({ success: false, message: text }));
                }
            })
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.message,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#198754'
                        });
                    } else {
                        alert(data.message);
                    }
                    
                    // Cerrar modal y recargar perfil
                    const modalElement = document.getElementById("modal-editar-perfilGuardian");
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                    cargarPerfilGuardian();
                } else {
                    throw new Error(data.message || 'Error al actualizar el perfil');
                }
            })
            .catch(error => {
                console.error("Error:", error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Error al actualizar el perfil',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545'
                    });
                } else {
                    alert('Error: ' + (error.message || 'Error al actualizar el perfil'));
                }
            })
            .finally(() => {
                if (btnGuardar) {
                    btnGuardar.disabled = false;
                    btnGuardar.innerHTML = '<i class="uil uil-save me-1"></i>Guardar cambios';
                }
            });
        });
    }
}

// ===================== EVENTOS DEL PERFIL ===================== //
function inicializarEventosPerfilGuardian() {
    // BOTONES DE EDITAR
    const botonesEditar = document.querySelectorAll(".btn-editar-perfilGuardian");

    botonesEditar.forEach(boton => {
        boton.addEventListener("click", function () {
            const id = this.dataset.id;

            // Mostrar loader en el modal
            const contenidoEditar = document.getElementById("contenido-editar");
            if (contenidoEditar) {
                contenidoEditar.innerHTML = `
                    <div class="text-center py-5" id="modal-loader">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3 text-muted">Cargando formulario de edición...</p>
                    </div>
                `;
            }

            // Abrir modal primero
            const modalElement = document.getElementById("modal-editar-perfilGuardian");
            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            // Cargar contenido
            fetch(`${window.BASE_URL}/view/guardian/GuardianEdit.php?id=${encodeURIComponent(id)}`)
                .then(response => {
                    if (!response.ok) throw new Error("No se pudo cargar el formulario de edición");
                    return response.text();
                })
                .then(html => {
                    contenidoEditar.innerHTML = html;
                    
                    // Configurar todos los eventos del formulario
                    setTimeout(() => {
                        configurarEventosFormulario();
                    }, 100);
                })
                .catch(error => {
                    console.error("Error:", error);
                    if (contenidoEditar) {
                        contenidoEditar.innerHTML = `
                            <div class="alert alert-danger text-center">
                                <i class="uil uil-exclamation-triangle fs-2"></i>
                                <h5 class="mt-2">Error al cargar</h5>
                                <p class="mb-0">No se pudo cargar el formulario de edición</p>
                            </div>
                        `;
                    }
                });
        });
    });
}

// ===================== INICIALIZACIÓN ===================== //
document.addEventListener("DOMContentLoaded", () => {
    const botones = document.querySelectorAll(".btn-cargar-perfilGuardian");

    botones.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            cargarPerfilGuardian();
        });
    });
});