
// ===================== FUNCIÓN PRINCIPAL PARA CARGAR CRUD ===================== //
window.cargarCrudPublicacion = function () {
    fetch("view/publicacion/PublicacionView.php")
        .then(response => {
            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            const mainContainer = document.getElementById("main-content");

            if (mainContainer) {
                mainContainer.innerHTML = data;

                setTimeout(() => {
                    inicializarEventosPublicacion();
                }, 100);
            }
        })
        .catch(error => {
            console.error("❌ Error al cargar PHP:", error);
        });
};

// ===================== MODAL DE REGISTRO ===================== //
function abrirModalCrearPublicacion() {
    const modalElement = document.getElementById("modal-publicacion");
    if (modalElement) {
        const modalBootstrap = new bootstrap.Modal(modalElement);
        modalBootstrap.show();
    }
}

// ===================== EVENTOS DEL CRUD ===================== //
function inicializarEventosPublicacion() {
    // ➕ Abrir modal de registro
    const btnAbrirModal = document.getElementById("btn-abrir-modal-publicacion");
    if (btnAbrirModal) {
        btnAbrirModal.addEventListener("click", abrirModalCrearPublicacion);
    }

    // ✅ FORMULARIO DE REGISTRO
    const formRegistrar = document.getElementById("form-registrar-publicacion");

    if (formRegistrar) {
        formRegistrar.onsubmit = function (e) {
            e.preventDefault();
            const formData = new FormData(formRegistrar);

            fetch(`${window.BASE_URL}/controller/publicacion/PublicacionController.php`, {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    const modalElement = document.getElementById("modal-publicacion");
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                    formRegistrar.reset();
                    cargarCrudPublicacion();
                })
                .catch(error => {
                    console.error("❌ Error:", error);
                });
        };
    }

    // ✏️ BOTONES DE EDITAR
    const botonesEditar = document.querySelectorAll(".btn-editar-publicacion");

    botonesEditar.forEach(boton => {
        boton.addEventListener("click", function () {
            const idPublicacion = this.dataset.id;

            fetch(`view/publicacion/PublicacionEditView.php?id=${idPublicacion}`)
                .then(response => {
                    if (!response.ok) throw new Error("No se pudo cargar el formulario de edición");
                    return response.text();
                })
                .then(html => {
                    const contenedor = document.getElementById("contenido-editar");
                    contenedor.innerHTML = html;

                    const modal = new bootstrap.Modal(document.getElementById("modal-editar-publicacion"));
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

                    const formEditar = document.getElementById("form-editar-publicacion");

                    if (formEditar) {
                        formEditar.onsubmit = function (e) {
                            e.preventDefault();
                            const formData = new FormData(formEditar);

                            fetch(`${window.BASE_URL}/controller/publicacion/PublicacionController.php`, {
                                method: "POST",
                                body: formData
                            })
                                .then(response => response.text())
                                .then(data => {
                                    if (data.trim()) {
                                        alert(data);
                                    }
                                    modal.hide();
                                    cargarCrudPublicacion();
                                });
                        };
                    }
                })
                .catch(error => {
                    console.error("❌ Error al cargar PublicacionEdit.php:", error);
                });
        });
    });

    // 🗑️ BOTONES DE ELIMINAR
    const botonesEliminar = document.querySelectorAll(".btn-eliminar-publicacion");
    botonesEliminar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idPublicacion = this.dataset.id;
            if (confirm("¿Estás seguro de que deseas eliminar esta publicación?")) {
                const formData = new FormData();
                formData.append('accion', 'eliminar');
                formData.append('id', idPublicacion);

                fetch(`${window.BASE_URL}/controller/publicacion/PublicacionController.php`, {
                    method: "POST",
                    body: formData
                })
                    .then(res => res.text())
                    .then(data => {
                        alert(data);
                        cargarCrudPublicacion();
                    })
                    .catch(error => {
                        console.error("❌ Error al eliminar:", error);
                    });
            }
        });
    });
}

// ===================== INICIALIZAR BOTÓN DE MENÚ ===================== //
document.addEventListener("DOMContentLoaded", () => {
    const btnCargarPublicaciones = document.getElementById("btn-cargar-publicacion");

    if (btnCargarPublicaciones) {
        btnCargarPublicaciones.addEventListener("click", function (e) {
            e.preventDefault();
            cargarCrudPublicacion();
        });
    }
});
