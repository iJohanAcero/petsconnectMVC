
// ===================== FUNCIÓN PRINCIPAL PARA CARGAR CRUD ===================== //
window.cargarCrudFundacion = function () {
    fetch("view/fundacion/FundacionView.php")
        .then(response => {
            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            const mainContainer = document.getElementById("main-content");

            if (mainContainer) {
                mainContainer.innerHTML = data;

                setTimeout(() => {
                    inicializarEventosFundacion();
                }, 100);
            }
        })
        .catch(error => {
            console.error("❌ Error al cargar PHP:", error);
        });
};

// ===================== MODAL DE REGISTRO ===================== //
function abrirModalCrearFundacion() {
    const modalElement = document.getElementById("modal-fundacion");
    if (modalElement) {
        const modalBootstrap = new bootstrap.Modal(modalElement);
        modalBootstrap.show();
    }
}

// ===================== EVENTOS DEL CRUD ===================== //
function inicializarEventosFundacion() {
    // Abrir modal de registro
    const btnAbrirModal = document.getElementById("btn-abrir-modal-fundacion");
    if (btnAbrirModal) {
        btnAbrirModal.addEventListener("click", abrirModalCrearFundacion);
    }

    // FORMULARIO DE REGISTRO
    const formRegistrar = document.getElementById("form-registrar-fundacion");

    if (formRegistrar) {
        formRegistrar.onsubmit = function (e) {
            e.preventDefault();
            const formData = new FormData(formRegistrar);

            fetch(`${window.BASE_URL}/controller/fundacion/FundacionController.php`, {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    const modalElement = document.getElementById("modal-fundacion");
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                    formRegistrar.reset();
                    cargarCrudFundacion();
                })
                .catch(error => {
                    console.error("Error:", error);
                });
        };
    }

    //BOTONES DE EDITAR
    const botonesEditar = document.querySelectorAll(".btn-editar-fundacion");
    botonesEditar.forEach(boton => {
        boton.addEventListener("click", function () {
            const nit = this.dataset.id;

            fetch(`view/fundacion/FundacionEdit.php?id=${encodeURIComponent(nit)}`)
                .then(response => {
                    if (!response.ok) throw new Error("No se pudo cargar el formulario de edición");
                    return response.text();
                })
                .then(html => {
                    const contenedor = document.getElementById("contenido-editar");
                    contenedor.innerHTML = html;

                    const modal = new bootstrap.Modal(document.getElementById("modal-editar-fundacion"));
                    modal.show();

                    const formEditar = document.getElementById("form-editar-fundacion");

                    if (formEditar) {
                        formEditar.onsubmit = function (e) {
                            e.preventDefault();
                            const formData = new FormData(formEditar);

                            fetch(`${window.BASE_URL}/controller/fundacion/FundacionController.php`, {
                                method: "POST",
                                body: formData
                            })
                                .then(response => response.text())
                                .then(data => {
                                    if (data.trim()) {
                                        alert(data);
                                    }
                                    modal.hide();
                                    cargarCrudFundacion();
                                });
                        };
                    }
                })
                .catch(error => {
                    console.error("Error al cargar FundacionEdit.php:", error);
                });
        });
    });

    // BOTONES DE ELIMINAR
    const botonesEliminar = document.querySelectorAll(".btn-eliminar-fundacion");
    botonesEliminar.forEach(btn => {
        btn.addEventListener("click", function () {
            const nit = this.dataset.id;
            if (confirm("¿Estás seguro de que deseas eliminar esta fundación?")) {
                const formData = new FormData();
                formData.append('accion', 'eliminar');
                formData.append('nit', nit);

                fetch(`${window.BASE_URL}/controller/fundacion/FundacionController.php`, {
                    method: "POST",
                    body: formData
                })
                    .then(res => res.text())
                    .then(data => {
                        alert(data);
                        cargarCrudFundacion();
                    })
                    .catch(error => {
                        console.error("Error al eliminar:", error);
                    });
            }
        });
    });
}

// ===================== INICIALIZAR BOTÓN DE MENÚ ===================== //
document.addEventListener("DOMContentLoaded", () => {
    const btnCargarFundaciones = document.getElementById("btn-cargar-fundaciones");

    if (btnCargarFundaciones) {
        btnCargarFundaciones.addEventListener("click", function (e) {
            e.preventDefault();
            cargarCrudFundacion();
        });
    }
});
