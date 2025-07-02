// ================= BASE_URL seguro =================
window.BASE_URL = window.BASE_URL || (window.location.origin + "/petsconnectMVC");

// =========== CRUD DE MASCOTAS =========== //
function cargarCrudMascotas() {
    fetch("view/mascota/MascotaView.php")
        .then(response => response.text())
        .then(data => {
            const mainContainer = document.getElementById("main-content") ||
                document.getElementById("crud-container");

            if (mainContainer) {
                mainContainer.innerHTML = data;
                inicializarEventosMascotas();
            } else {
                console.error("❌ No se encontró el contenedor principal para el CRUD de mascotas");
            }
        })
        .catch(error => console.error("Error al cargar MascotaView.php:", error));
}
window.cargarCrudMascotas = cargarCrudMascotas;
document
function abrirModalCrearMascota() {
    const modalElement = document.getElementById("modal-mascotas");
    const modalBootstrap = new bootstrap.Modal(modalElement);
    modalBootstrap.show();
}

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

            fetch(`${BASE_URL}/controller/mascota/MascotaController.php`, {
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

    // ✅ ASIGNAR EVENTO EDITAR (como producto)
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
                            fetch(`${BASE_URL}/controller/mascota/MascotaController.php`, {
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
}

// Inicializar al cargar el DOM
document.addEventListener("DOMContentLoaded", () => {
    const btnMascotas = document.getElementById("btn-cargar-mascotas");
    if (btnMascotas) {
        console.log("✅ Botón Mascotas encontrado y preparado");
        btnMascotas.addEventListener("click", function (e) {
            e.preventDefault();
            cargarCrudMascotas();
        });
    } else {
        console.error("❌ No se encontró el botón 'btn-cargar-mascotas'");
    }
});

console.log("🐾 Función cargarCrudMascotas:", typeof cargarCrudMascotas);
