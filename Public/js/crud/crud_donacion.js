// =========== CRUD DE DONACIONES =========== //

function cargarCrudDonacion() {
    fetch("view/donacion/DonacionView.php")
        .then(response => response.text())
        .then(data => {
            const mainContainer = document.getElementById("main-content") ||
                document.getElementById("crud-container");

            if (mainContainer) {
                mainContainer.innerHTML = data;
                setTimeout(() => {
                    inicializarDonacion();
                }, 100);
            }
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}
window.cargarCrudDonacion = cargarCrudDonacion;

function abrirModalCrearDonacion() {
    const modalElement = document.getElementById("modal-donacion");
    const modalBootstrap = new bootstrap.Modal(modalElement);
    modalBootstrap.show();
}

function inicializarDonacion() {
    // Modal crear donacion
    const btnAbrirModal = document.getElementById("btn-abrir-modal-donacion");
    if (btnAbrirModal) {
        btnAbrirModal.addEventListener("click", abrirModalCrearDonacion);
    }

    // ✅ REGISTRAR DONACION
    const formRegistrar = document.getElementById("form-registrar-donacion");

    if (formRegistrar) {
        formRegistrar.onsubmit = function (e) {
            e.preventDefault();
            const formData = new FormData(formRegistrar);

            fetch(`${window.BASE_URL}/controller/donacion/DonacionController.php`, {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    if (data.toLowerCase().includes("correctamente")) {
                        alert(data);

                        const modalElement = document.getElementById("modal-donacion");
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) modal.hide();

                        formRegistrar.reset();
                        cargarCrudDonacion();
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
    const botonesEditar = document.querySelectorAll(".btn-editar-donacion");

    botonesEditar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idDonacion = this.dataset.id;

            fetch(`view/donacion/DonacionEdit.php?id=${encodeURIComponent(idDonacion)}`)
                .then(response => {
                    if (!response.ok) throw new Error("No se pudo cargar el formulario de edición");
                    return response.text();
                })
                .then(html => {
                    document.getElementById("contenido-editar").innerHTML = html;
                    const modalElement = document.getElementById("modal-editar-donacion");
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

                    const formEditar = document.getElementById("form-editar-donacion");
                    if (formEditar) {
                        formEditar.addEventListener("submit", function (e) {
                            e.preventDefault();
                            const formData = new FormData(formEditar);

                            fetch(`${window.BASE_URL}/controller/donacion/DonacionController.php`, {
                                method: "POST",
                                body: formData
                            })
                                .then(res => res.text())
                                .then(data => {
                                    alert(data);
                                    const modalElement = document.getElementById("modal-editar-donacion");
                                    const modal = bootstrap.Modal.getInstance(modalElement);
                                    if (modal) modal.hide();
                                    cargarCrudDonacion();
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
    const botonesEliminar = document.querySelectorAll(".btn-eliminar-donacion");
    botonesEliminar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idDonacion = this.dataset.id;
            if (confirm("¿Estás seguro de que deseas eliminar esta donación?")) {
                const formData = new FormData();
                formData.append('accion', 'eliminar');
                formData.append('id_donacion', idDonacion);

                fetch(`${window.BASE_URL}/controller/donacion/DonacionController.php`, {
                    method: "POST",
                    body: formData
                })
                    .then(res => res.text())
                    .then(data => {
                        alert(data);
                        cargarCrudDonacion();
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });
            }
        });
    });

     // ✅ BOTONES RECIBO (Factura PDF)
    const botonesRecibo = document.querySelectorAll(".btn-recibo-donacion");
    botonesRecibo.forEach(btn => {
        btn.addEventListener("click", function () {
            const idDonacion = this.dataset.id;

            if (!idDonacion) {
                alert("ID de donación no válido");
                return;
            }

            // 🔹 Redirige al controlador que genera el PDF
            window.location.href = `${window.BASE_URL}/controller/donacion/DonacionController.php?action=generarFacturaDonacionPDF&id_donacion=${idDonacion}`;
        });
    });
}


// Inicializar cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {
    const btnDonacion = document.getElementById("btn-cargar-donaciones");
    if (btnDonacion) {
        btnDonacion.addEventListener("click", function (e) {
            e.preventDefault();
            cargarCrudDonacion();
        });
    }
});

window.cargarCrudDonacion = cargarCrudDonacion;