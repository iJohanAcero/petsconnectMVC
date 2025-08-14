// =========== CRUD DE MASCOTAS =========== //

function cargarCrudMascota() {
    fetch("view/mascota/MascotaView.php")
        .then(response => response.text())
        .then(data => {
            const mainContainer = document.getElementById("main-content") ||
                document.getElementById("crud-container");

            if (mainContainer) {
                mainContainer.innerHTML = data;
                
            setTimeout(() => {
                    inicializarMascota();
                }, 100);
            }
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}
window.cargarCrudMascota = cargarCrudMascota;

function abrirModalCrearMascota() {
    const modalElement = document.getElementById("modal-mascota");
    const modalBootstrap = new bootstrap.Modal(modalElement);
    modalBootstrap.show();
}

function inicializarMascota() {
    // Modal crear mascota
    const btnAbrirModal = document.getElementById("btn-abrir-modal-mascota");
    if (btnAbrirModal) {
        btnAbrirModal.addEventListener("click", abrirModalCrearMascota);
    }

    // ✅ REGISTRAR MASCOTA
    const formRegistrar = document.getElementById("form-registrar-mascota");

    if (formRegistrar) {
        formRegistrar.onsubmit = function (e) {
            e.preventDefault();
            const formData = new FormData(formRegistrar);

            fetch(`${window.BASE_URL}/controller/mascota/MascotaController.php`, {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    if (data.toLowerCase().includes("correctamente")) {
                        alert(data);

                        const modalElement = document.getElementById("modal-mascota");
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) modal.hide();

                        formRegistrar.reset();
                        cargarCrudMascota();
                    } else {
                        alert("Error: " + data);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                });
        };
    }

    // ✅ BOTONES EDITAR
    const botonesEditar = document.querySelectorAll(".btn-editar-mascota");

botonesEditar.forEach(btn => {
    btn.addEventListener("click", function () {
        const idMascota = this.dataset.id;
        console.log("🔍 ID Mascota:", idMascota);

        fetch(`view/mascota/MascotaEditView.php?id=${encodeURIComponent(idMascota)}`)
            .then(response => {
                console.log("📡 Response status:", response.status);
                return response.text();
            })
            .then(html => {
                console.log("📄 HTML recibido - Longitud:", html.length);
                
                const contenidoDiv = document.getElementById("contenido-editar");
                if (!contenidoDiv) {
                    console.error("❌ No se encontró #contenido-editar");
                    return;
                }
                
                contenidoDiv.innerHTML = html;
                console.log("✅ HTML insertado en contenido-editar");
                
                // 🔧 SOLUCIÓN: Forzar la aparición del modal
                const modalElement = document.getElementById("modal-editar-mascota");
                if (!modalElement) {
                    console.error("❌ No se encontró #modal-editar-mascota");
                    return;
                }
                
                // ⭐ CAMBIO CRÍTICO: Eliminar instancias previas
                const existingModal = bootstrap.Modal.getInstance(modalElement);
                if (existingModal) {
                    existingModal.dispose();
                }
                
                // ⭐ FORZAR LA VISUALIZACIÓN
                modalElement.style.display = 'block';
                modalElement.classList.add('show');
                document.body.classList.add('modal-open');
                
                // Crear backdrop manualmente
                let backdrop = document.querySelector('.modal-backdrop');
                if (!backdrop) {
                    backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);
                }
                
                // Crear nueva instancia del modal
                const modal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: false
                });
                
                console.log("✅ Modal forzado a mostrarse");

                // Configurar el botón de cerrar
                const btnCerrar = modalElement.querySelector('[data-bs-dismiss="modal"]');
                if (btnCerrar) {
                    btnCerrar.onclick = function() {
                        modalElement.style.display = 'none';
                        modalElement.classList.remove('show');
                        document.body.classList.remove('modal-open');
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) backdrop.remove();
                    };
                }

                // Configurar eventos del formulario
                setTimeout(() => {
                    const formEditar = document.getElementById("form-editar-mascota");
                    if (formEditar) {
                        console.log("✅ Configurando formulario...");
                        
                        formEditar.addEventListener("submit", function (e) {
                            e.preventDefault();
                            const formData = new FormData(formEditar);

                            fetch(`${window.BASE_URL}/controller/mascota/MascotaController.php`, {
                                method: "POST",
                                body: formData
                            })
                                .then(res => res.text())
                                .then(data => {
                                    alert(data);
                                    // Cerrar modal manualmente
                                    modalElement.style.display = 'none';
                                    modalElement.classList.remove('show');
                                    document.body.classList.remove('modal-open');
                                    const backdrop = document.querySelector('.modal-backdrop');
                                    if (backdrop) backdrop.remove();
                                    
                                    cargarCrudMascota();
                                })
                                .catch(error => {
                                    console.error("Error:", error);
                                });
                        });
                    } else {
                        console.error("❌ Formulario no encontrado");
                    }
                }, 100);
            })
            .catch(error => {
                console.error("💥 Error en fetch:", error);
            });
    });
});

    // ✅ BOTONES ELIMINAR
    const botonesEliminar = document.querySelectorAll(".btn-eliminar-mascota");
    botonesEliminar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idMascota = this.dataset.id;
            if (confirm("¿Estás seguro de que deseas eliminar esta mascota?")) {
                const formData = new FormData();
                formData.append('accion', 'eliminar');
                formData.append('id_mascota', idMascota);

                fetch(`${window.BASE_URL}/controller/mascota/MascotaController.php`, {
                    method: "POST",
                    body: formData
                })
                    .then(res => res.text())
                    .then(data => {
                        alert(data);
                        cargarCrudMascota();
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });
            }
        });
    });
}

// Inicializar cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {
    const btnMascota = document.getElementById("btn-cargar-mascota");
    if (btnMascota) {
        btnMascota.addEventListener("click", function (e) {
            e.preventDefault();
            cargarCrudMascota();
        });
    }
});

window.cargarCrudMascota = cargarCrudMascota;