// Definir BASE_URL correctamente (descomentada y ajustada)
const BASE_URL = window.location.origin + "/petsconnectMVC";
window.BASE_URL = window.BASE_URL || BASE_URL;

// =========== CRUD DE FUNDACION =========== //
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
                inicializarEventosFundacion();
            } else {
                console.error("No se encontró el contenedor principal para el CRUD");
                alert("Error: No se pudo cargar la interfaz");
            }
        })
        .catch(error => {
            console.error("Error al cargar PHP:", error);
            alert("Error al cargar la página. Por favor recarga.");
        });
}


function abrirModalCrearFundacion() {
    const modalElement = document.getElementById("modal-fundacion");
    if (modalElement) {
        const modalBootstrap = new bootstrap.Modal(modalElement);
        modalBootstrap.show();
    } else {
        console.error("No se encontró el modal para crear fundación");
    }
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
        formRegistrar.onsubmit = function (e) {
            e.preventDefault();
            const formData = new FormData(formRegistrar);

            fetch(`${BASE_URL}/controller/fundacion/FundacionController.php`, {
                method: "POST",
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error("Error en la respuesta del servidor");
                return response.text();
            })
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
                alert("Error en la comunicación con el servidor");
            });
        };
    }

    // ✅ BOTONES EDITAR - CORREGIDO (cambié idProducto por idFundacion)
    const botonesEditar = document.querySelectorAll(".btn-editar-fundacion");
    botonesEditar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idFundacion = this.dataset.id;
            fetch(`view/fundacion/FundacionEditView.php?id=${idFundacion}`) // Cambié la ruta a fundacion
                .then(response => {
                    if (!response.ok) throw new Error("Error al cargar formulario de edición");
                    return response.text();
                })
                .then(html => {
                    const contenidoEditar = document.getElementById("contenido-editar");
                    if (contenidoEditar) {
                        contenidoEditar.innerHTML = html;
                        const modalElement = document.getElementById("modal-editar-fundacion"); // Cambié el ID del modal
                        if (modalElement) {
                            const modal = new bootstrap.Modal(modalElement);
                            modal.show();
                        }
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Error al cargar formulario de edición");
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

document.addEventListener("DOMContentLoaded", function () {
    const btnFundaciones = document.getElementById("btn-cargar-fundaciones");
    if (btnFundaciones) {
        btnFundaciones.addEventListener("click", function (e) {
            e.preventDefault(); // evita que recargue la página
            cargarCrudFundacion(); // llama al CRUD
        });
    }
});
