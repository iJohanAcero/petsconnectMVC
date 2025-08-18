window.cargarPerfilFundacion = function () {
    fetch("view/fundacion/perfilFundacion.php")
        .then(response => {
            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            const mainContainer = document.getElementById("main-content");

            if (mainContainer) {
                mainContainer.innerHTML = data;

                setTimeout(() => {
                    inicializarEventosPerfilFundacion();
                }, 100);
            }
        });
};

function abrirModalFundacion() {
    const modalElement = document.getElementById("modal-Fundacion");
    if (modalElement) {
        const modalBootstrap = new bootstrap.Modal(modalElement);
        modalBootstrap.show();
    }
}

// ===================== FUNCIONES PARA REDES SOCIALES ===================== //
let redesSocialesIndex = 0;

window.agregarRedSocial = function() {
    
    const container = document.getElementById('redes-sociales-container');
    if (!container) {
        return;
    }
    
    // Remover mensaje de "no hay redes"
    const mensajeVacio = container.querySelector('.text-muted.text-center');
    if (mensajeVacio) {
        mensajeVacio.remove();
    }
};

window.eliminarRedSocial = function(button) {
    const redSocialItem = button.closest('.red-social-item');
    
    // Animación de salida
    redSocialItem.style.transition = 'opacity 0.3s ease';
    redSocialItem.style.opacity = '0';
    
    setTimeout(() => {
        redSocialItem.remove();
        actualizarPreviewRedes();
    }, 300);
};

window.actualizarPreviewRedes = function() {
    const previewContainer = document.getElementById('preview-redes');
    if (!previewContainer) return;
    
    previewContainer.innerHTML = '';
    
    const redesItems = document.querySelectorAll('.red-social-item');
    let tieneRedes = false;
    
    redesItems.forEach(item => {
        const tipoSelect = item.querySelector('select[name*="tipo_red"]');
        const urlInput = item.querySelector('input[name*="url_red"]');
        
        if (tipoSelect && urlInput && tipoSelect.value && urlInput.value) {
            tieneRedes = true;
            const enlace = document.createElement('a');
            enlace.href = urlInput.value;
            enlace.target = '_blank';
            enlace.className = 'text-decoration-none me-3 fs-4';
            
            let iconoHtml = '';
            switch(tipoSelect.value) {
                case 'facebook': iconoHtml = '<i class="uil uil-facebook-f text-primary"></i>'; break;
                case 'instagram': iconoHtml = '<i class="uil uil-instagram-alt text-danger"></i>'; break;
                case 'pagina_web': iconoHtml = '<i class="uil uil-globe text-info"></i>'; break;
            }
            
            enlace.innerHTML = iconoHtml;
            previewContainer.appendChild(enlace);
        }
    });
    
    if (!tieneRedes) {
        previewContainer.innerHTML = `
            <span class="text-muted">
                <i class="uil uil-link-broken"></i> Sin redes sociales
            </span>
        `;
    }
};

window.previewImagen = function(input) {
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
};

// ===================== EVENTOS DEL PERFIL ===================== //
function inicializarEventosPerfilFundacion() {
    // ✏️ BOTONES DE EDITAR
    const botonesEditar = document.querySelectorAll(".btn-editar-perfilFundacion");

    botonesEditar.forEach(boton => {
        boton.addEventListener("click", function () {
            const id = this.dataset.id;

            fetch(`view/fundacion/FundacionEditPerfil.php?id=${encodeURIComponent(id)}`)
                .then(response => {
                    if (!response.ok) throw new Error("No se pudo cargar el formulario de edición");
                    return response.text();
                })
                .then(html => {
                    document.getElementById("contenido-editar").innerHTML = html;
                    const modalElement = document.getElementById("modal-editar-perfilFundacion");
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();

                    // Inicializar contador de redes sociales basado en los datos cargados
                    const redesExistentes = document.querySelectorAll('.red-social-item');
                    redesSocialesIndex = redesExistentes.length;

                    // Configurar eventos del formulario
                    configurarEventosFormulario();
                })
                .catch(error => {
                    alert("Error al cargar el formulario de edición");
                });
        });
    });
}

function configurarEventosFormulario() {

    const inputImagen = document.getElementById("input-imagen");
    if (inputImagen) {
        inputImagen.addEventListener("change", function () {
            previewImagen(this);
        });
    }

    // Configurar contador de caracteres
    const descripcionTextarea = document.getElementById('descripcion');
    const contadorCaracteres = document.getElementById('caracteres-actuales');

    if (descripcionTextarea && contadorCaracteres) {
        descripcionTextarea.addEventListener('input', function() {
            contadorCaracteres.textContent = this.value.length;
            
            const contador = document.getElementById('contador-caracteres');
            if (contador) {
                if (this.value.length > 900) {
                    contador.classList.add('text-warning');
                    contador.classList.remove('text-muted');
                } else if (this.value.length > 950) {
                    contador.classList.add('text-danger');
                    contador.classList.remove('text-warning', 'text-muted');
                } else {
                    contador.classList.remove('text-warning', 'text-danger');
                    contador.classList.add('text-muted');
                }
            }
        });
    }

    // CONFIGURAR BOTÓN AGREGAR RED SOCIAL
    const btnAgregarRed = document.getElementById('btn-agregar-red');

    if (btnAgregarRed) {
        btnAgregarRed.addEventListener('click', function(e) {
            e.preventDefault();
            agregarRedSocialConEventListeners();
        });
    }

    // CONFIGURAR BOTONES ELIMINAR EXISTENTES
    configurarBotonesEliminar();

    // Configurar eventos para redes sociales existentes
    configurarEventosRedesSociales();

    // Actualizar preview inicial
    actualizarPreviewRedes();

    // Configurar envío del formulario
    const formEditar = document.getElementById("form-editar-perfilFundacion");
    if (formEditar) {
        formEditar.addEventListener("submit", function (e) {
            e.preventDefault();
            
            if (!formEditar.checkValidity()) {
                formEditar.classList.add('was-validated');
                return;
            }

            const formData = new FormData(formEditar);
            const btnGuardar = document.getElementById('btn-guardar');
            
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
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito!',
                            text: data.message,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#198754'
                        });
                    } else {
                        alert(data.message);
                    }
                    
                    const modalElement = document.getElementById("modal-editar-perfilFundacion");
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                    cargarPerfilFundacion();
                } else {
                    throw new Error(data.message || 'Error al actualizar el perfil');
                }
            })
            .catch(error => {
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
                    btnGuardar.innerHTML = '<i class="uil uil-save"></i> Guardar cambios';
                }
            });
        });
    }
}

// NUEVA FUNCIÓN PARA AGREGAR RED SOCIAL CON EVENT LISTENERS
function agregarRedSocialConEventListeners() {
    const container = document.getElementById('redes-sociales-container');
    if (!container) {
        return;
    }
    
    const nuevaRed = document.createElement('div');
    nuevaRed.className = 'red-social-item border p-3 mb-3 rounded bg-white';
    nuevaRed.setAttribute('data-index', redesSocialesIndex);
    
    nuevaRed.innerHTML = `
        <div class="row align-items-center">
            <div class="col-4">
                <select name="redes_sociales[${redesSocialesIndex}][tipo_red]" 
                        class="form-select form-select-sm tipo-red-select">
                    <option value="">Seleccionar</option>
                    <option value="facebook">Facebook</option>
                    <option value="instagram">Instagram</option>
                    <option value="pagina_web">Página Web</option>
                </select>
            </div>
            <div class="col-6">
                <input type="url" name="redes_sociales[${redesSocialesIndex}][url_red]" 
                       class="form-control form-control-sm url-red-input" 
                       placeholder="https://...">
            </div>
            <div class="col-2 text-end">
                <button type="button" class="btn btn-danger btn-sm btn-eliminar-red">
                    <i class="uil uil-trash-alt"></i>
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(nuevaRed);
    
    // Configurar event listeners para la nueva red
    const nuevoSelect = nuevaRed.querySelector('.tipo-red-select');
    const nuevoInput = nuevaRed.querySelector('.url-red-input');
    const nuevoBotonEliminar = nuevaRed.querySelector('.btn-eliminar-red');
    
    if (nuevoSelect) {
        nuevoSelect.addEventListener('change', actualizarPreviewRedes);
    }
    
    if (nuevoInput) {
        nuevoInput.addEventListener('input', actualizarPreviewRedes);
    }
    
    if (nuevoBotonEliminar) {
        nuevoBotonEliminar.addEventListener('click', function(e) {
            e.preventDefault();
            eliminarRedSocialConEventListeners(this);
        });
    }
    
    // Animación de entrada
    nuevaRed.style.opacity = '0';
    setTimeout(() => {
        nuevaRed.style.transition = 'opacity 0.3s ease';
        nuevaRed.style.opacity = '1';
    }, 10);
}

// NUEVA FUNCIÓN PARA ELIMINAR RED SOCIAL CON EVENT LISTENERS
function eliminarRedSocialConEventListeners(button) {
    const redSocialItem = button.closest('.red-social-item');
    
    // Animación de salida
    redSocialItem.style.transition = 'opacity 0.3s ease';
    redSocialItem.style.opacity = '0';
    
    setTimeout(() => {
        redSocialItem.remove();
        actualizarPreviewRedes();
        
        // Si no quedan redes, mostrar mensaje
        const container = document.getElementById('redes-sociales-container');
        if (container && container.querySelectorAll('.red-social-item').length === 0) {
            container.innerHTML = `
                <div class="text-muted text-center py-3" id="mensaje-sin-redes">
                    <i class="uil uil-link-add fs-2"></i>
                    <p class="mb-0">No hay redes sociales configuradas</p>
                    <small class="text-muted">Haz clic en "Agregar Red Social" para comenzar</small>
                </div>
            `;
        }
    }, 300);
}

// FUNCIÓN PARA CONFIGURAR BOTONES ELIMINAR EXISTENTES
function configurarBotonesEliminar() {
    const botonesEliminar = document.querySelectorAll('.btn-eliminar-red');
    
    botonesEliminar.forEach((boton, index) => {
        // Remover event listeners anteriores si existen
        boton.replaceWith(boton.cloneNode(true));
        const nuevoBoton = document.querySelectorAll('.btn-eliminar-red')[index];
        
        nuevoBoton.addEventListener('click', function(e) {
            e.preventDefault();
            eliminarRedSocialConEventListeners(this);
        });
    });
}

// FUNCIÓN PARA CONFIGURAR EVENTOS DE REDES SOCIALES EXISTENTES
function configurarEventosRedesSociales() {
    const tipoSelects = document.querySelectorAll('select[name*="tipo_red"]');
    const urlInputs = document.querySelectorAll('input[name*="url_red"]');
    
    tipoSelects.forEach(select => {
        // Clonar para remover event listeners anteriores
        const nuevoSelect = select.cloneNode(true);
        select.parentNode.replaceChild(nuevoSelect, select);
        nuevoSelect.addEventListener('change', actualizarPreviewRedes);
    });
    
    urlInputs.forEach(input => {
        // Clonar para remover event listeners anteriores
        const nuevoInput = input.cloneNode(true);
        input.parentNode.replaceChild(nuevoInput, input);
        nuevoInput.addEventListener('input', actualizarPreviewRedes);
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const botones = document.querySelectorAll(".btn-cargar-perfilFundacion");

    botones.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            cargarPerfilFundacion();
        });
    });
});