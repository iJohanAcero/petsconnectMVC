const BASE_URL = window.location.origin + "/petsconnectMVC";
window.BASE_URL = window.BASE_URL || '';

// =========== CRUD DE PROCESOS DE ADOPCION =========== //

function cargarCrudProcesos() {
    fetch("Views/crud_procesoAdopcion.php") // Nombre del archivo PHP a incluir
        .then(response => response.text()) // Convertir respuesta en texto
        .then(data => {
            document.getElementById("crud").innerHTML = data; // Incluir contenido

            inicializarModalProcesos();
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}


// =========== MODAL DE PROCESOS DE ADOPCION =========== //

function inicializarModalProcesos() { // funcion para inicializar el modal 
    setTimeout(() => {
        const modal = document.getElementById("modal-procesos");
        const btn = document.getElementById("openModal");
        const close = document.querySelector(".close");

        btn.onclick = function () {
            modal.style.display = "block"; //le decimos que bloquee el display cuando se haga click en el boton
        };

        close.onclick = function () {
            modal.style.display = "none"; //le decimos que cuando de click en close(X) se desaparezca el display
        };

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none"; //le decimos que cuando de click en otro lugar fuera del modal, este desaparezca.
            }
        };
    }, 100); // Pequeño delay para asegurar que el DOM se haya actualizado
}


// =========== CRUD DE VACUNACION =========== //

function cargarCrudVacunas() {
    fetch("Views/crud_vacunas.php")// Nombre del archivo PHP a incluir
        .then(response => response.text()) // Convertir respuesta en texto
        .then(data => {
            document.getElementById("crud").innerHTML = data; // Incluir contenido

            inicializarModalVacunas();
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}

// =========== MODAL DE VACUNACION =========== //

function inicializarModalVacunas() { // funcion para inicializar el modal 
    setTimeout(() => {
        const modal = document.getElementById("modal-vacunas");
        const btn = document.getElementById("openModal");
        const close = document.querySelector(".close");

        btn.onclick = function () {
            modal.style.display = "block"; //le decimos que bloquee el display cuando se haga click en el boton
        };

        close.onclick = function () {
            modal.style.display = "none"; //le decimos que cuando de click en close(X) se desaparezca el display
        };

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none"; //le decimos que cuando de click en otro lugar fuera del modal, este desaparezca.
            }
        };
    }, 100); // Pequeño delay para asegurar que el DOM se haya actualizado
}



// =========== CRUD DE TIPO DE MASCOTA =========== //

function cargarCrudTipoMascota() {
    fetch("view/tipo_mascota/Tipo_mascota_view.php") // Nombre del archivo PHP a incluir
        .then(response => response.text()) // Convertir respuesta en texto
        .then(data => {
            document.getElementById("crud").innerHTML = data; // Incluir contenido

            inicializarModalTipoMascota();
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}

// =========== MODAL DE TIPO DE MASCOTA =========== //


function inicializarModalTipoMascota() {
    setTimeout(() => {
        const modal = document.getElementById("modal-tipoMascota");
        const btn = document.getElementById("openModal");
        const close = document.querySelector(".close");

        // Abrir modal
        btn.onclick = function () {
            modal.style.display = "block";
        };

        //Cerrar modal por (X)
        close.onclick = function () {
            modal.style.display = "none";
        };

        // Cerrar haciendo clic fuera del modal
        window.onclick = function (event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };

        // Enviar formulario vía fetch + FormData
        const form = document.querySelector(".form-modal");

        if (form) {
            form.addEventListener("submit", function (e) {
                e.preventDefault();

                const formData = new FormData(form);

                fetch("controller/tipo_mascota/add.php", {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.text())
                    .then(data => {
                        form.reset();
                        modal.style.display = "none";
                        cargarCrudTipoMascota();
                    })
                    .catch(error => {
                        console.error("Error en la petición:", error);
                    });
            });
        }

    }, 100); // Delay para que el DOM se cargue si viene por innerHTML
}


// =========== CRUD DE FUNDACION =========== //

function cargarCrudFundaciones() {
    fetch("Views/crud_fundaciones.php") // Nombre del archivo PHP a incluir
        .then(response => response.text()) // Convertir respuesta en texto
        .then(data => {
            document.getElementById("crud").innerHTML = data; // Incluir contenido

            inicializarModalFundaciones();
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}

// =========== MODAL DE FUNDACION =========== //

function inicializarModalFundaciones() { // funcion para inicializar el modal 
    setTimeout(() => {
        const modal = document.getElementById("modal-fundaciones");
        const btn = document.getElementById("openModal");
        const close = document.querySelector(".close");

        btn.onclick = function () {
            modal.style.display = "block"; //le decimos que bloquee el display cuando se haga click en el boton
        };

        close.onclick = function () {
            modal.style.display = "none"; //le decimos que cuando de click en close(X) se desaparezca el display
        };

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none"; //le decimos que cuando de click en otro lugar fuera del modal, este desaparezca.
            }
        };
    }, 100); // Pequeño delay para asegurar que el DOM se haya actualizado
}

// =========== CRUD DE PRODUCTOS =========== //
window.cargarCrudProductos = function () {
    fetch("view/producto/ProductoView.php")
        .then(response => response.text())
        .then(data => {
            // Cambia el objetivo al contenedor del main
            const mainContainer = document.querySelector(".main-content #crud-container") ||
                document.getElementById("crud-container") ||
                document.getElementById("crud");

            if (mainContainer) {
                mainContainer.innerHTML = data;
                inicializarEventosProductos();
            } else {
                console.error("No se encontró el contenedor principal para el CRUD");
            }
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}

function abrirModalCrearProducto() {
    const modalElement = document.getElementById("modal-productos");
    const modalBootstrap = new bootstrap.Modal(modalElement);
    modalBootstrap.show();
}

function inicializarEventosProductos() {
    // Modal para crear producto
    const btnAbrirModal = document.getElementById("btn-abrir-modal-producto");
    if (btnAbrirModal) {
        btnAbrirModal.addEventListener("click", abrirModalCrearProducto);
    }

    // // Formulario de registro
    // const formularioRegistro = document.getElementById("form-registrar-producto");
    // if (formularioRegistro) {
    //     formularioRegistro.addEventListener("submit", function (event) {
    //         event.preventDefault();
    //         const formData = new FormData(formularioRegistro);

    //         fetch(`${BASE_URL}/controller/producto/ProductoController.php`, {
    //             method: "POST",
    //             body: formData
    //         })
    //             // .then(response => response.json())

    //             .then(data => {
    //                 if (data.success) {
    //                     mostrarAlerta('success', 'Producto registrado correctamente');
    //                     const modalElement = document.getElementById("modal-productos");
    //                     const modal = bootstrap.Modal.getInstance(modalElement);
    //                     if (modal) modal.hide();
    //                     cargarCrudProductos();
    //                 } else {
    //                     mostrarAlerta('error', data.message || 'Error al registrar producto');
    //                 }
    //             })
    //             .catch(error => {
    //                 console.error("Error:", error);
    //                 mostrarAlerta('error', 'Error en la comunicación con el servidor');
    //             });
    //     });
    // }
    // Versión con diagnóstico extendido

    const formularioRegistro = document.getElementById("form-registrar-producto");
    if (formularioRegistro) {
        formularioRegistro.addEventListener("submit", async function (event) {
            event.preventDefault();

            try {
                const formData = new FormData(formularioRegistro);
                console.log("Datos enviados:", Object.fromEntries(formData));

                const response = await fetch(`${BASE_URL}/controller/producto/ProductoController.php`, {
                    method: "POST",
                    body: formData
                });

                console.log("Estado HTTP:", response.status);
                const responseText = await response.text();
                console.log("Respuesta bruta:", responseText);

                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    throw new Error(`La respuesta no es JSON válido: ${responseText.substring(0, 100)}`);
                }

                if (!response.ok) {
                    throw new Error(data.message || `Error HTTP ${response.status}`);
                }

                if (data.success) {
                    mostrarAlerta('success', data.message);
                    bootstrap.Modal.getInstance(document.getElementById("modal-productos"))?.hide();
                    cargarCrudProductos();
                } else {
                    mostrarAlerta('error', data.message);
                }

            } catch (error) {
                console.error("Error completo:", error);
                mostrarAlerta('error', error.message);
            }
        });
    }

    // Botones de edición
    const botonesEditar = document.querySelectorAll(".btn-editar-producto");
    botonesEditar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idProducto = this.dataset.id;
            fetch(`view/producto/ProductoEditView.php?id=${idProducto}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById("modal-edit-form").innerHTML = html;
                    const modalElement = document.getElementById("modal-edit-productos");
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();

                    // Inicializar formulario de edición
                    const formEditar = document.getElementById("form-editar-producto");
                    if (formEditar) {
                        formEditar.addEventListener("submit", function (e) {
                            e.preventDefault();
                            const formData = new FormData(formEditar);

                            fetch(`${BASE_URL}/controller/producto/ProductoController.php`, {
                                method: "POST",
                                body: formData
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        mostrarAlerta('success', 'Producto actualizado correctamente');
                                        const modalElement = document.getElementById("modal-edit-productos");
                                        const modal = bootstrap.Modal.getInstance(modalElement);
                                        if (modal) modal.hide();
                                        cargarCrudProductos();
                                    } else {
                                        mostrarAlerta('error', data.message || 'Error al actualizar producto');
                                    }
                                })
                                .catch(error => {
                                    console.error("Error:", error);
                                    mostrarAlerta('error', 'Error en la comunicación con el servidor');
                                });
                        });
                    }
                })
                .catch(error => console.error("Error al cargar el modal de edición:", error));
        });
    });

    // Botones de eliminación
    const botonesEliminar = document.querySelectorAll(".btn-eliminar-producto");
    botonesEliminar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idProducto = this.dataset.id;
            if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
                const formData = new FormData();
                formData.append('eliminar', 'true');
                formData.append('id', idProducto);

                fetch(`${BASE_URL}/controller/producto/ProductoController.php`, {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            mostrarAlerta('success', 'Producto eliminado correctamente');
                            cargarCrudProductos();
                        } else {
                            mostrarAlerta('error', data.message || 'Error al eliminar producto');
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        mostrarAlerta('error', 'Error en la comunicación con el servidor');
                    });
            }
        });
    });
}

// Función auxiliar para mostrar alertas con SweetAlert o similar
function mostrarAlerta(tipo, mensaje) {
    // Puedes implementar SweetAlert o usar alertas de Bootstrap
    if (tipo === 'success') {
        alert(mensaje); // Reemplaza esto con tu sistema de alertas preferido
    } else {
        alert(mensaje); // Reemplaza esto con tu sistema de alertas preferido
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {
    // Opcional: cargar el CRUD automáticamente al entrar al módulo
    // cargarCrudProductos();
});