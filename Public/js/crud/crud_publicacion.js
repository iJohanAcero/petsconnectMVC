
// =========== CRUD DE Publicacion =========== //
window.cargarCrudPublicacion = function () {
    fetch("view/publicacion/PublicacionView.php")
        .then(response => {
            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            // Cambia el objetivo al contenedor del main
            const mainContainer = document.getElementById("main-content")

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


function abrirModalCrearPublicacion() {
    const modalElement = document.getElementById("modal-publicacion");
    if (modalElement) {
        const modalBootstrap = new bootstrap.Modal(modalElement);
        modalBootstrap.show();
    }
}

function inicializarEventosPublicacion() {
    // Modal crear Publicacion
    const btnAbrirModal = document.getElementById("btn-abrir-modal-publicacion");
    if (btnAbrirModal) {
        btnAbrirModal.addEventListener("click", abrirModalCrearPublicacion);
    }

    // ✅ REGISTRAR Publicacion
    const formRegistrar = document.getElementById("form-registrar-publicacion");

    if (formRegistrar) {
        // Usamos onsubmit en vez de addEventListener, así no se repite el evento
        formRegistrar.onsubmit = function (e) {
            e.preventDefault(); // Evita que se recargue la página
            const formData = new FormData(formRegistrar); // Captura los datos del formulario

            fetch(`${BASE_URL}/controller/publicacion/PublicacionController.php`, {
                method: "POST",
                body: formData
            })
                .then(response => response.text()) // Espera respuesta del servidor como texto
                .then(data => {
                        alert(data);
                        const modalElement = document.getElementById("modal-publicacion");
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) modal.hide();
                        formRegistrar.reset(); // Limpia el formulario
                        cargarCrudPublicacion(); // Vuelve a cargar la lista actualizada
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Error en la comunicación con el servidor");
                });
        };
    }

    // ✅ BOTONES EDITAR
    const botonesEditar = document.querySelectorAll(".btn-editar-publicacion");

    botonesEditar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idPublicacion = this.dataset.id;

            fetch(`view/publicacion/PublicacionEditView.php?id=${idPublicacion}`)
                .then(response => {
                    if (!response.ok) throw new Error("No se pudo cargar el formulario de edición");
                    return response.text();
                })
                .then(html => {
                    document.getElementById("contenido-editar").innerHTML = html;
                    const modalElement = document.getElementById("modal-editar-publicacion");
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();

                    const formEditar = document.getElementById("form-editar-publicacion");
                    if (formEditar) {
                        formEditar.addEventListener("submit", function (e) {
                            e.preventDefault();
                            const formData = new FormData(formEditar);

                            fetch(`${BASE_URL}/controller/publicacion/PublicacionController.php`, {
                                method: "POST",
                                body: formData
                            })
                                .then(res => res.text()) // no json
                                .then(data => {
                                    alert(data);
                                    const modalElement = document.getElementById("modal-editar-publicacion");
                                    const modal = bootstrap.Modal.getInstance(modalElement);
                                    if (modal) modal.hide();
                                    cargarCrudPublicacion();
                                })
                                .catch(error => {
                                    console.error("Error:", error);
                                    mostrarAlerta('error', 'Error en la comunicación con el servidor');
                                });
                        });
                    }
                });
        });
    });

    // 🗑️ BOTONES DE ELIMINAR
    const botonesEliminar = document.querySelectorAll(".btn-eliminar-publicacion");

    botonesEliminar.forEach(btn => {
        btn.addEventListener("click", function () {
            const id = this.dataset.id;

            if (confirm("¿Estás seguro de que deseas eliminar esta publicacion?")) {
                const formData = new FormData();
                formData.append("eliminar", "true");
                formData.append("id", id);

                fetch(`${BASE_URL}/controller/publicacion/PublicacionController.php`, {
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

document.addEventListener("DOMContentLoaded", () => {
    const btnCargarPublicacion = document.getElementById("btn-cargar-publicacion");

    if (btnCargarPublicacion) {
        btnCargarPublicacion.addEventListener("click", function (e) {
            e.preventDefault();
            cargarCrudPublicacion();
        });
    }
});

