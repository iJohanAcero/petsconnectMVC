// const BASE_URL = window.location.origin + "/petsconnectMVC";
// window.BASE_URL = window.BASE_URL || '';

// =========== CRUD DE FUNDACION =========== //
window.cargarCrudFundacion = function () {
    fetch("view/fundacion/FundacionView.php")
        .then(response => response.text())
        .then(data => {
            // Cambia el objetivo al contenedor del main
            const mainContainer = document.getElementById("main-content")

            if (mainContainer) {
                mainContainer.innerHTML = data;
                inicializarEventosFundacion();
            } else {
                console.error("No se encontró el contenedor principal para el CRUD");
            }
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}


function abrirModalCrearFundacion() {
    const modalElement = document.getElementById("modal-fundacion");
    const modalBootstrap = new bootstrap.Modal(modalElement);
    modalBootstrap.show();
}

function inicializarEventosFundacion() {
    // Modal crear Fundacion
    const btnAbrirModal = document.getElementById("btn-abrir-modal-fundacion");
    if (btnAbrirModal) {
        btnAbrirModal.addEventListener("click", abrirModalCrearFundacion);
    }

    // ✅ REGISTRAR FUNDACION
    const formRegistrar = document.getElementById("form-registrar-fundacion");

    if (formRegistrar) {
        // Usamos onsubmit en vez de addEventListener, así no se repite el evento
        formRegistrar.onsubmit = function (e) {
            e.preventDefault(); // Evita que se recargue la página

            const formData = new FormData(formRegistrar); // Captura los datos del formulario

            fetch(`${BASE_URL}/controller/fundacion/FundacionController.php`, {
                method: "POST",
                body: formData
            })
                .then(response => response.text()) // Espera respuesta del servidor como texto
                .then(data => {
                    // Si el texto dice que todo salió bien...
                    if (data.toLowerCase().includes("correctamente")) {
                        alert(data); // Muestra un mensaje (puedes usar sweetalert después)

                        // Cierra el modal de Bootstrap
                        const modalElement = document.getElementById("modal-fundacion");
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) modal.hide();

                        formRegistrar.reset(); // Limpia el formulario

                        cargarCrudFundacion(); // Vuelve a cargar la lista actualizada
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
    const botonesEditar = document.querySelectorAll(".btn-editar-fundacion");
    botonesEditar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idFundacion = this.dataset.id;
            fetch(`view/producto/ProductoEditView.php?id=${idProducto}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById("contenido-editar").innerHTML = html;
                    const modalElement = document.getElementById("modal-editar-producto");
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();

                    const formEditar = document.getElementById("form-editar-producto");
                    if (formEditar) {
                        formEditar.addEventListener("submit", function (e) {
                            e.preventDefault();
                            const formData = new FormData(formEditar);

                            fetch(`${BASE_URL}/controller/producto/ProductoController.php`, {
                                method: "POST",
                                body: formData
                            })
                                .then(res => res.text()) // no json
                                .then(data => {
                                    alert(data);
                                    const modalElement = document.getElementById("modal-editar-producto");
                                    const modal = bootstrap.Modal.getInstance(modalElement);
                                    if (modal) modal.hide();
                                    cargarCrudProductos();
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
    const botonesEliminar = document.querySelectorAll(".btn-eliminar-fundacion");
    botonesEliminar.forEach(btn => {
        btn.addEventListener("click", function () {
            const nit = this.dataset.id;
            if (confirm("¿Estás seguro de que deseas eliminar esta fundación?")) {
                const formData = new FormData();
                formData.append('eliminar', 'true');
                formData.append('nit', nit);

                fetch(`${BASE_URL}/controller/fundacion/FundacionController.php`, {
                    method: "POST",
                    body: formData
                })
                    .then(res => res.text()) // ya no json
                    .then(data => {
                        alert(data); // Cambiamos mostrarAlerta por alert simple
                        cargarCrudFundacion(); // Recargar la tabla
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        mostrarAlerta('error', 'Error en la comunicación con el servidor');
                    });
            }
        });
    });
}