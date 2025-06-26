// =========== CRUD DE Publicacion =========== //
window.cargarCrudPublicacion = function () {
    fetch("view/publicacion/PublicacionView.php")
        .then(response => response.text())
        .then(data => {
            // Cambia el objetivo al contenedor del main
            const mainContainer = document.getElementById("main-content")

            if (mainContainer) {
                mainContainer.innerHTML = data;
                inicializarEventosPublicacion();
            } else {
                console.error("No se encontró el contenedor principal para el CRUD");
            }
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}


function abrirModalCrearPublicacion() {
    const modalElement = document.getElementById("modal-publicacion");
    const modalBootstrap = new bootstrap.Modal(modalElement);
    modalBootstrap.show();
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
                    // Si el texto dice que todo salió bien...
                    if (data.toLowerCase().includes("correctamente")) {
                        alert(data); // Muestra un mensaje (puedes usar sweetalert después)

                        // Cierra el modal de Bootstrap
                        const modalElement = document.getElementById("modal-publicacion");
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) modal.hide();

                        formRegistrar.reset(); // Limpia el formulario

                        cargarCrudPublicacion(); // Vuelve a cargar la lista actualizada
                    } else {
                        alert("Error: " + data); // Si hubo error, lo muestra
                    }
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
                .then(response => response.text())
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

    // ✅ BOTONES ELIMINAR
    const botonesEliminar = document.querySelectorAll(".btn-eliminar-Publicacion");
    botonesEliminar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idPublicacion = this.dataset.id;
            if (confirm("¿Estás seguro de que deseas eliminar este Publicacion?")) {
                const formData = new FormData();
                formData.append('eliminar', 'true');
                formData.append('id', idPublicacion);

                fetch(`${BASE_URL}/controller/Publicacion/PublicacionController.php`, {
                    method: "POST",
                    body: formData
                })
                    .then(res => res.text()) // ya no json
                    .then(data => {
                        alert(data); // Cambiamos mostrarAlerta por alert simple
                        cargarCrudPublicacion(); // Recargar la tabla
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        mostrarAlerta('error', 'Error en la comunicación con el servidor');
                    });
            }
        });
    });
}
