// Definir BASE_URL correctamente (descomentada y ajustada)
const BASE_URL = window.location.origin + "/petsconnectMVC";
window.BASE_URL = window.BASE_URL || BASE_URL;

// =========== CRUD DE FUNDACION =========== //
// window.cargarCrudFundacion = function () {
//     fetch("view/fundacion/FundacionView.php")
//         .then(response => {
//             if (!response.ok) throw new Error("Error en la red");
//             return response.text();
//         })
//         .then(data => {
//             const mainContainer = document.getElementById("main-content");

//             if (mainContainer) {
//                 mainContainer.innerHTML = data;
//                 inicializarEventosFundacion();
//             } else {
//                 console.error("No se encontró el contenedor principal para el CRUD");
//                 alert("Error: No se pudo cargar la interfaz");
//             }
//         })
//         .catch(error => {
//             console.error("Error al cargar PHP:", error);
//             alert("Error al cargar la página. Por favor recarga.");
//         });
// }
window.cargarCrudFundacion = function () {
    console.log("📥 Ejecutando fetch a FundacionView.php...");

    fetch("view/fundacion/FundacionView.php")
        .then(response => {
            console.log("📦 Respuesta recibida:", response);

            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            console.log("📄 HTML cargado:", data); // Mostramos el contenido

            const mainContainer = document.getElementById("main-content");

            if (mainContainer) {
                mainContainer.innerHTML = data;
                console.log("✅ Contenido insertado en #main-content");
                inicializarEventosFundacion();
            } else {
                console.error("❌ No se encontró el contenedor principal para el CRUD");
                alert("Error: No se pudo cargar la interfaz");
            }
        })
        .catch(error => {
            console.error("❌ Error al cargar PHP:", error);
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

    // ✅ BOTONES EDITAR  

    const formEditar = document.getElementById("form-editar-fundacion");

    if (formEditar) {
        formEditar.onsubmit = function (e) {
            e.preventDefault(); // ⛔ Evita que se recargue la página

            const formData = new FormData(formEditar); // 📦 Prepara datos del formulario
            
            fetch(`${BASE_URL}/controller/fundacion/FundacionController.php`, {
                method: "POST",
                body: formData
            })
                .then(response => response.text()) // ✅ Recibimos texto simple
                .then(data => {
                    alert(data); // ✅ Mostramos mensaje simple
                    const modal = bootstrap.Modal.getInstance(document.getElementById("modal-editar-fundacion"));
                    if (modal) modal.hide(); // ✅ Cerramos el modal
                    cargarCrudFundacion(); // 🔄 Recargamos la tabla
                })
                .catch(error => {
                    console.error("❌ Error:", error);
                    alert("Error al actualizar la fundación");
                });
        };
    }

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

// document.addEventListener("DOMContentLoaded", function () {
//     const btnFundaciones = document.getElementById("btn-cargar-fundaciones");

//     if (btnFundaciones) {
//         console.log("✅ Botón Fundaciones encontrado");

//         btnFundaciones.addEventListener("click", function (e) {
//             e.preventDefault();

//             console.log("✅ Se hizo clic en el botón Fundaciones");

//             cargarCrudFundacion(); // llama al CRUD
//         });
//     } else {
//         console.error("❌ No se encontró el botón Fundaciones");
//     }
// });

// Esto solo corre si el archivo fue cargado como módulo (que sí lo es en admin_home.php)

// Esperamos a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", () => {
    // Buscamos el botón del menú lateral
    const btnCargarFundaciones = document.getElementById("btn-cargar-fundaciones");

    // Si el botón existe, le agregamos el evento
    if (btnCargarFundaciones) {
        btnCargarFundaciones.addEventListener("click", function (e) {
            e.preventDefault(); // ⛔ Evita que el enlace redireccione
            cargarCrudFundacion(); // 🔄 Llama la función principal que ya definiste
        });
    } else {
        console.warn("⚠️ No se encontró el botón #btn-cargar-fundaciones");
    }
});
