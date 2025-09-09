// ===================== FUNCIÓN PRINCIPAL PARA CARGAR CRUD ===================== //
window.cargarCrudGuardian = function () {
    fetch("view/guardian/GuardianView.php")
        .then(response => {
            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            const mainContainer = document.getElementById("main-content");

            if (mainContainer) {
                mainContainer.innerHTML = data;

                setTimeout(() => {
                    inicializarEventosGuardian();
                }, 100);
            }
        })
        .catch(error => {
            console.error("Error al cargar PHP:", error);
        });
};

// ===================== MODAL DE REGISTRO ===================== //
function abrirModalCrearGuardian() {
    const modalElement = document.getElementById("modal-guardian");
    if (modalElement) {
        const modalBootstrap = new bootstrap.Modal(modalElement);
        modalBootstrap.show();
    }
}

// ===================== EVENTOS DEL CRUD ===================== //
function inicializarEventosGuardian() {
    // Abrir modal de registro
    const btnAbrirModal = document.getElementById("btn-abrir-modal-guardian");
    if (btnAbrirModal) {
        btnAbrirModal.addEventListener("click", abrirModalCrearGuardian);
    }

    // FORMULARIO DE REGISTRO
    const formRegistrar = document.getElementById("form-registrar-guardian");

    if (formRegistrar) {
        formRegistrar.onsubmit = function (e) {
            e.preventDefault();
            const formData = new FormData(formRegistrar);

            fetch(`${window.BASE_URL}/controller/guardian/GuardianController.php`, {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    const modalElement = document.getElementById("modal-guardian");
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                    formRegistrar.reset();
                    cargarCrudGuardian();
                })
                .catch(error => {
                    console.error("Error:", error);
                });
        };
    }

    // BOTONES DE EDITAR
    const botonesEditar = document.querySelectorAll(".btn-editar-guardian");
    botonesEditar.forEach(boton => {
        boton.addEventListener("click", function () {
            const id_usuario = this.dataset.id; // CORREGIDO: usar id_usuario

            fetch(`view/guardian/GuardianEditCrud.php?id_usuario=${encodeURIComponent(id_usuario)}`) // CORREGIDO: pasar id_usuario
                .then(response => {
                    if (!response.ok) throw new Error("No se pudo cargar el formulario de edición");
                    return response.text();
                })
                .then(html => {
                    const contenedor = document.getElementById("contenido-editar");
                    contenedor.innerHTML = html;

                    const modal = new bootstrap.Modal(document.getElementById("modal-editar-guardian"));
                    modal.show();

                    const formEditar = document.getElementById("form-editar-guardian");

                    if (formEditar) {
                        formEditar.onsubmit = function (e) {
                            e.preventDefault();
                            const formData = new FormData(formEditar);

                            fetch(`${window.BASE_URL}/controller/guardian/GuardianController.php`, {
                                method: "POST",
                                body: formData
                            })
                                .then(response => response.text())
                                .then(data => {
                                    if (data.trim()) {
                                        alert(data);
                                    }
                                    modal.hide();
                                    cargarCrudGuardian();
                                });
                        };
                    }
                })
                .catch(error => {
                    console.error("Error al cargar GuardianEdit.php:", error);
                });
        });
    });

    // BOTONES DE ELIMINAR - CORREGIDO
    const botonesEliminar = document.querySelectorAll(".btn-eliminar-guardian");
    botonesEliminar.forEach(btn => {
        btn.addEventListener("click", function () {
            const id_usuario = this.dataset.id; // CORREGIDO: usar id_usuario
            if (confirm("¿Estás seguro de que deseas eliminar este guardián?")) {
                const formData = new FormData();
                formData.append('accion', 'eliminar');
                formData.append('id_usuario', id_usuario); // CORREGIDO: enviar id_usuario en lugar de nit

                fetch(`${window.BASE_URL}/controller/guardian/GuardianController.php`, {
                    method: "POST",
                    body: formData
                })
                    .then(res => res.text())
                    .then(data => {
                        alert(data);
                        cargarCrudGuardian();
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
    const btnCargarGuardianes = document.getElementById("btn-cargar-guardianes");

    if (btnCargarGuardianes) {
        btnCargarGuardianes.addEventListener("click", function (e) {
            e.preventDefault();
            cargarCrudGuardian();
        });
    }
});