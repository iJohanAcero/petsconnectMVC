// =========== CRUD DE CAUSAS =========== //

function cargarCrudCausa() {
    fetch("view/causa/CausaView.php")
        .then(response => response.text())
        .then(data => {
            const mainContainer = document.getElementById("main-content") ||
                document.getElementById("crud-container");

            if (mainContainer) {
                mainContainer.innerHTML = data;
                
            setTimeout(() => {
                    inicializarCausa();
                }, 100);
            }
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}
window.cargarCrudCausa = cargarCrudCausa;

function abrirModalCrearCausa() {
    const modalElement = document.getElementById("modal-causa");
    const modalBootstrap = new bootstrap.Modal(modalElement);
    modalBootstrap.show();
}

function inicializarCausa() {
    // Modal crear causa
    const btnAbrirModal = document.getElementById("btn-abrir-modal-causa");
    if (btnAbrirModal) {
        btnAbrirModal.addEventListener("click", abrirModalCrearCausa);
    }

    // ✅ REGISTRAR CAUSA
    const formRegistrar = document.getElementById("form-registrar-causa");

    if (formRegistrar) {
        formRegistrar.onsubmit = function (e) {
            e.preventDefault();
            const formData = new FormData(formRegistrar);

            fetch(`${window.BASE_URL}/controller/causa/CausaController.php`, {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    if (data.toLowerCase().includes("correctamente")) {
                        alert(data);

                        const modalElement = document.getElementById("modal-causa");
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) modal.hide();

                        formRegistrar.reset();
                        cargarCrudCausa();
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
    const botonesEditar = document.querySelectorAll(".btn-editar-causa");
    botonesEditar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idCausa = this.dataset.id;
            fetch(`view/causa/CausaEdit.php?id=${idCausa}`)
                .then(response => {
                    if (!response.ok) throw new Error("No se pudo cargar el formulario de edición");
                    return response.text();
                })
                .then(html => {
                    document.getElementById("contenido-editar").innerHTML = html;
                    const modalElement = document.getElementById("modal-editar-causa");
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();

                    const formEditar = document.getElementById("form-editar-causa");
                    if (formEditar) {
                        formEditar.addEventListener("submit", function (e) {
                            e.preventDefault();
                            const formData = new FormData(formEditar);

                            fetch(`${window.BASE_URL}/controller/causa/CausaController.php`, {
                                method: "POST",
                                body: formData
                            })
                                .then(res => res.text())
                                .then(data => {
                                    alert(data);
                                    const modalElement = document.getElementById("modal-editar-causa");
                                    const modal = bootstrap.Modal.getInstance(modalElement);
                                    if (modal) modal.hide();
                                    cargarCrudCausa();
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
    const botonesEliminar = document.querySelectorAll(".btn-eliminar-causa");
    botonesEliminar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idCausa = this.dataset.id;
            if (confirm("¿Estás seguro de que deseas eliminar esta causa?")) {
                const formData = new FormData();
                formData.append('accion', 'eliminar');
                formData.append('id_causa', idCausa);

                fetch(`${window.BASE_URL}/controller/causa/CausaController.php`, {
                    method: "POST",
                    body: formData
                })
                    .then(res => res.text())
                    .then(data => {
                        alert(data);
                        cargarCrudCausa();
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
    const btnCausa = document.getElementById("btn-cargar-causa");
    if (btnCausa) {
        btnCausa.addEventListener("click", function (e) {
            e.preventDefault();
            cargarCrudCausa();
        });
    }
});

// Solo si estás usando type="module"
window.cargarCrudCausa = cargarCrudCausa;

console.log("📢 Función cargarCrudCausa:", typeof cargarCrudCausa);
