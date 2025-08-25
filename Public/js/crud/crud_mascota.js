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

            fetch(`view/mascota/MascotaEditView.php?id=${encodeURIComponent(idMascota)}`)
                .then(response => {
                    if (!response.ok) throw new Error("No se pudo cargar el formulario de edición");
                    return response.text();
                })
                .then(html => {
                    document.getElementById("contenido-editar").innerHTML = html;
                    const modalElement = document.getElementById("modal-editar-mascota");
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();

                    const inputImagen = document.getElementById("input-imagen");
                    const previewImagen = document.getElementById("preview-imagen");

                    if (inputImagen && previewImagen) {
                        inputImagen.addEventListener("change", function () {
                            const archivo = this.files[0];
                            if (archivo) {
                                const reader = new FileReader();
                                reader.onload = function (e) {
                                    previewImagen.src = e.target.result;
                                };
                                reader.readAsDataURL(archivo);
                            }
                        });
                    }

                    const formEditar = document.getElementById("form-editar-mascota");
                    if (formEditar) {
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
                                    const modalElement = document.getElementById("modal-editar-mascota");
                                    const modal = bootstrap.Modal.getInstance(modalElement);
                                    if (modal) modal.hide();
                                    cargarCrudMascota();
                                })
                                .catch(error => {
                                    console.error("Error:", error);
                                });
                        });
                    }
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