// ===================== CONFIGURACIÓN BASE ===================== //
// Definir la URL base para todas las peticiones
const BASE_URL = window.location.origin + "/petsconnectMVC";
window.BASE_URL = window.BASE_URL || BASE_URL;

// ===================== FUNCIÓN PRINCIPAL PARA CARGAR CRUD ===================== //
window.cargarCrudFundacion = function () {
    console.log("📥 Ejecutando fetch a FundacionView.php...");

    fetch("view/fundacion/FundacionView.php")
        .then(response => {
            console.log("📦 Respuesta recibida:", response);
            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            console.log("📄 HTML cargado:", data);

            const mainContainer = document.getElementById("main-content");

            if (mainContainer) {
                mainContainer.innerHTML = data;
                console.log("✅ Contenido insertado en #main-content");

                // ⏳ Esperar unos ms para asegurarse que el HTML ya está en el DOM
                setTimeout(() => {
                    inicializarEventosFundacion();
                    console.log("✅ Se ejecutó inicializarEventosFundacion después del render");
                }, 100);
            } else {
                console.error("❌ No se encontró el contenedor principal para el CRUD");
                alert("Error: No se pudo cargar la interfaz");
            }
        })
        .catch(error => {
            console.error("❌ Error al cargar PHP:", error);
            alert("Error al cargar la página. Por favor recarga.");
        });
};

// ===================== MODAL DE REGISTRO ===================== //
function abrirModalCrearFundacion() {
    const modalElement = document.getElementById("modal-fundacion");
    if (modalElement) {
        const modalBootstrap = new bootstrap.Modal(modalElement);
        modalBootstrap.show();
    } else {
        console.error("❌ No se encontró el modal para crear fundación");
    }
}

// ===================== EVENTOS DEL CRUD ===================== //
function inicializarEventosFundacion() {
    // ➕ Abrir modal de registro
    const btnAbrirModal = document.getElementById("btn-abrir-modal-fundacion");
    if (btnAbrirModal) {
        btnAbrirModal.addEventListener("click", abrirModalCrearFundacion);
    }

    // ✅ FORMULARIO DE REGISTRO
    const formRegistrar = document.getElementById("form-registrar-fundacion");

    if (formRegistrar) {
        formRegistrar.onsubmit = function (e) {
            e.preventDefault();
            const formData = new FormData(formRegistrar);

            fetch(`${BASE_URL}/controller/fundacion/FundacionController.php`, {
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
                    cargarCrudFundacion(); // 🔄 Recargar tabla
                })
                .catch(error => {
                    console.error("❌ Error:", error);
                    alert("Error en la comunicación con el servidor");
                });
        };
    }

    // ✏️ BOTONES DE EDITAR (CORREGIDO: este bloque estaba fuera)
    const botonesEditar = document.querySelectorAll(".btn-editar-fundacion");

    botonesEditar.forEach(boton => {
        boton.addEventListener("click", function () {
            const nit = this.dataset.id;
            console.log("✏️ Clic en editar fundación con NIT:", nit);

            fetch(`view/fundacion/FundacionEdit.php?id=${encodeURIComponent(nit)}`)
                .then(response => {
                    console.log("📦 Respuesta de FundacionEdit.php:", response);
                    if (!response.ok) throw new Error("No se pudo cargar el formulario de edición");
                    return response.text();
                })
                .then(html => {
                    const contenedor = document.getElementById("contenido-editar");
                    contenedor.innerHTML = html;
                    console.log("✅ HTML de edición insertado en el modal");

                    // Mostrar modal
                    const modal = new bootstrap.Modal(document.getElementById("modal-editar-fundacion"));
                    modal.show();

                    // Enviar formulario de edición (dentro del HTML cargado)
                    const formEditar = document.getElementById("form-editar-fundacion");

                    if (formEditar) {
                        formEditar.onsubmit = function (e) {
                            e.preventDefault();
                            const formData = new FormData(formEditar);

                            fetch(`${BASE_URL}/controller/fundacion/FundacionController.php`, {
                                method: "POST",
                                body: formData
                            })
                                .then(response => response.text())
                                .then(data => {
                                    if (data.trim()) {
                                        alert(data);
                                    } else {
                                        console.warn("⚠️ Respuesta vacía del servidor");
                                    }
                                    modal.hide();
                                    cargarCrudFundacion(); // Recargar la tabla
                                })
                        };
                    } else {
                        console.warn("⚠️ No se encontró el formulario de edición");
                    }
                })
                .catch(error => {
                    console.error("❌ Error al cargar FundacionEdit.php:", error);
                    alert("Error al abrir el formulario de edición");
                });
        });
    });

    // 🗑️ BOTONES DE ELIMINAR
    const botonesEliminar = document.querySelectorAll(".btn-eliminar-fundacion");

    botonesEliminar.forEach(btn => {
        btn.addEventListener("click", function () {
            const nit = this.dataset.id;

            if (confirm("¿Estás seguro de que deseas eliminar esta fundación?")) {
                const formData = new FormData();
                formData.append("eliminar", "true");
                formData.append("nit", nit);

                fetch(`${BASE_URL}/controller/fundacion/FundacionController.php`, {
                    method: "POST",
                    body: formData
                })
                    .then(res => res.text())
                    .then(data => {
                        alert(data);
                        cargarCrudFundacion(); // Recargar después de eliminar
                    })
                    .catch(error => {
                        console.error("❌ Error al eliminar:", error);
                        alert("Error en la comunicación con el servidor");
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
            cargarCrudFundacion(); // Llamamos a la función principal
        });
    } else {
        console.warn("⚠️ No se encontró el botón #btn-cargar-fundaciones");
    }
});
