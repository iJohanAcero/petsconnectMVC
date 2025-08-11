// =========== CRUD DE MASCOTAS =========== //

window.cargarCrudMascotas = function () {
    fetch("view/mascota/MascotaView.php")
        .then(response => {
            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            const mainContainer = document.getElementById("main-content");

            if (mainContainer) {
                mainContainer.innerHTML = data;

                setTimeout(() => {
                    inicializarEventosMascotas();
                }, 100);
            }
        })
        .catch(error => {
            console.error("❌ Error al cargar PHP:", error);
        });
};

// ===================== MODAL DE REGISTRO ===================== //
function abrirModalCrearMascota() {
    const modalElement = document.getElementById("modal-mascotas");
    if (modalElement) {
        const modalBootstrap = new bootstrap.Modal(modalElement);
        modalBootstrap.show();
    }
}


// ===================== EVENTOS DEL CRUD ===================== //
function inicializarEventosMascotas() {
    // Botón para abrir modal
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
                        const modalElement = document.getElementById("modal-mascotas");
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) modal.hide();
                        formRegistrar.reset();
                        cargarCrudMascotas();
                    } else {
                        alert("Error: " + data);
                    }
                })
                .catch(error => {
                    console.error("Error al registrar mascota:", error);
                });
        };
    }

    // ✅ ASIGNAR EVENTO EDITAR 
    const botonesEditar = document.querySelectorAll(".btn-editar-mascota");
    botonesEditar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idMascota = this.dataset.id;
            fetch(`view/mascota/MascotaEditView.php?id=${idMascota}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById("contenido-editar-mascota").innerHTML = html;
                    const modalElement = document.getElementById("modal-editar-mascota");
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();

                    // Evento para guardar edición
                    const formEditar = document.getElementById("form-editar-mascota");
                    if (formEditar) {
                        formEditar.addEventListener("submit", function (e) {
                            e.preventDefault();
                            const formData = new FormData(this);
                            fetch(`${window.BASE_URL}/controller/mascota/MascotaController.php`, {
                                method: "POST",
                                body: formData
                            })
                                .then(res => res.text())
                                .then(data => {
                                    alert(data);
                                    const modal = bootstrap.Modal.getInstance(modalElement);
                                    if (modal) modal.hide();
                                    cargarCrudMascotas();
                                })
                                .catch(error => {
                                    console.error("❌ Error al editar mascota:", error);
                                });
                        });
                    }
                })
                .catch(error => {
                    console.error("❌ Error al cargar MascotaEditView.php:", error);
                });
        });
    });


// 🗑️ BOTONES DE ELIMINAR
    const botonesEliminar = document.querySelectorAll(".btn-eliminar-mascota");
    botonesEliminar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idMascota = this.dataset.id;
            if (confirm("¿Estás seguro de que deseas eliminar esta mascota?")) {
                const formData = new FormData();
                formData.append('accion', 'eliminar');
                formData.append('id_mascota', idMascota);

                fetch(`${window.BASE_URL}/controller/Mascota/MascotaController.php`, {
                    method: "POST",
                    body: formData
                })
                    .then(res => res.text())
                    .then(data => {
                        alert(data);
                        cargarCrudMascotas();
                    })
                    .catch(error => {
                        console.error("❌ Error al eliminar:", error);
                    });
            }
        });
    });
}

// Inicializar al cargar el DOM
document.addEventListener("DOMContentLoaded", () => {
    const btnMascotas = document.getElementById("btn-cargar-mascotas");

    if (btnMascotas) {
        btnMascotas.addEventListener("click", function (e) {
            e.preventDefault();
            cargarCrudMascotas();
        });
    }
});

