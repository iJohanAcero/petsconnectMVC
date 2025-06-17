const BASE_URL = window.location.origin + "/petsconnectMVC";

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

function cargarCrudProductos() {
    fetch("view/producto/ProductoView.php") // Nombre del archivo PHP a incluir
        .then(response => response.text()) // Convertir respuesta en texto
        .then(data => {
            document.getElementById("crud").innerHTML = data; // Incluir contenido

            inicializarModalProductos();
            inicializarModalProductosEdit();
            inicializarFormularioRegistro();
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}

// =========== MODAL DE PRODUCTOS =========== //

function inicializarModalProductos() {

    const btnAbrirModal = document.getElementById("btn-abrir-modal-producto");
    if (btnAbrirModal) {
        btnAbrirModal.addEventListener("click", () => {
            const modalElement = document.getElementById("modal-productos");
            const modalBootstrap = new bootstrap.Modal(modalElement);
            modalBootstrap.show();
        });
    }
}



// =========== MODAL DE PRODUCTOS formulario =========== //
function inicializarFormularioRegistro() {
    // Obtenemos el formulario de registro por su ID
    const formulario = document.getElementById("form-registrar-producto");

    if (formulario) {
        formulario.addEventListener("submit", function (event) {
            event.preventDefault(); // 🛑 Evita el envío clásico del formulario

            // 👇 Capturamos todos los datos del formulario
            const formData = new FormData(formulario);

            // ✅ Enviamos los datos usando fetch al controlador PHP
            fetch(`${BASE_URL}/controller/producto/ProductoController.php`, {
                method: "POST",
                body: formData
            })
                .then(response => response.text()) // ⏳ Esperamos respuesta como texto
                .then(result => {
                    // 🟢 Mostramos alerta de éxito (puedes reemplazar con toast Bootstrap si quieres)
                    alert("Producto registrado con éxito ✅");

                    // 🔽 Cerramos el modal correctamente usando Bootstrap
                    const modalElement = document.getElementById("modal-productos");
                    const modalBootstrap = bootstrap.Modal.getInstance(modalElement);
                    if (modalBootstrap) {
                        modalBootstrap.hide();
                    }

                    // 🔄 Recargamos la vista del CRUD
                    const contenedorCrud = document.getElementById("crud");
                    if (contenedorCrud) {
                        fetch("view/producto/ProductoView.php") // Asegúrate de que esta ruta es correcta
                            .then(response => response.text())
                            .then(html => {
                                contenedorCrud.innerHTML = html;

                                // 🔁 Re-inicializamos todos los scripts necesarios
                                inicializarFormularioRegistro(); // Para reactivar evento en nuevo formulario
                                inicializarModalProductos();     // Para el botón de abrir modal
                                inicializarModalProductosEdit(); // Para los botones de editar
                            });
                    }
                })
                .catch((error) => {
                    alert("❌ Ocurrió un error al registrar el producto");
                    console.error("Error completo:", error);
                });
        });
    }
}



// =========== MODAL DE PRODUCTOS EDITAR =========== //

function inicializarModalProductosEdit() {
    // Buscar todos los botones con la clase .btn-editar-producto
    const botonesEditar = document.querySelectorAll(".btn-editar-producto");

    // Recorrer cada botón y agregar el evento click
    botonesEditar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idProducto = this.dataset.id;

            // Cargar el formulario de edición con fetch
            fetch(`view/producto/ProductoEditView.php?id=${idProducto}`)
                .then(response => response.text())
                .then(html => {
                    // Mostrar el contenido en el modal y abrirlo
                    document.getElementById("modal-edit-form").innerHTML = html;
                    document.getElementById("modal-edit-productos").style.display = "block";

                    inicializarFormularioEdicion();
                })
                .catch(error => console.error("Error al cargar el modal de edición:", error));
        });
    });
}

// =========== FORMULARIO DE EDICIÓN =========== //
function inicializarFormularioEdicion() {
    const formEditar = document.getElementById("form-editar-producto");

    if (formEditar) {
        formEditar.addEventListener("submit", function (event) {
            event.preventDefault(); // 🚫 Evita el envío clásico

            const formData = new FormData(formEditar);

            fetch(`${BASE_URL}/controller/producto/ProductoController.php`, {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(result => {
                    alert("Producto actualizado con éxito ✅");

                    // Cerrar el modal
                    const modalElement = document.getElementById("modal-edit-productos");
                    const modalBootstrap = bootstrap.Modal.getInstance(modalElement);
                    if (modalBootstrap) {
                        modalBootstrap.hide();
                    }

                    // Recargar tabla
                    cargarCrudProductos();
                })
                .catch(error => {
                    console.error("Error al actualizar producto:", error);
                    alert("❌ Ocurrió un error al actualizar.");
                });
        });
    }
}

document.addEventListener("submit", function (e) {
    if (e.target && e.target.id === "formEditarProducto") {
        e.preventDefault(); // evita que se recargue la página

        const form = e.target;
        const formData = new FormData(form);

        fetch(`${BASE_URL}/controller/producto/ProductoController.php`, {
            method: "POST",
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Producto actualizado correctamente");
                    cerrarModal();      // función que cierra el modal
                    cargarProductos();  // función que vuelve a cargar la tabla/lista
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(err => {
                console.error("Error en fetch:", err);
            });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    inicializarModalProductos(); // <== Este es el que activa el botón
});
