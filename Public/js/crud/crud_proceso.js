// =========== CRUD DE ADOPCION =========== //

function cargarCrudAdopcion() {
    fetch("view/adopcion/AdopcionView.php")
        .then(response => response.text())
        .then(data => {
            const mainContainer = document.getElementById("main-content") ||
                document.getElementById("crud-container");

            if (mainContainer) {
                mainContainer.innerHTML = data;
                
            setTimeout(() => {
                    inicializarAdopcion();
                }, 100);
            }
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}
window.cargarCrudAdopcion = cargarCrudAdopcion;

function abrirModalCrearAdopcion() {
    const modalElement = document.getElementById("modal-adopcion");
    const modalBootstrap = new bootstrap.Modal(modalElement);
    modalBootstrap.show();
}

function inicializarAdopcion() {
    // BOTONES EDITAR 
    const botonesEditar = document.querySelectorAll(".btn-editar-adopcion");
    botonesEditar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idAdopcion = this.dataset.id;
            const estadoActual = this.dataset.estadoActual;
            
            //VERIFICAR QUE EL MODAL EXISTE ANTES DE USARLO
            const modalElement = document.getElementById("modalActualizarEstado");
            const procesoIdInput = document.getElementById("procesoIdEstado");
            const nuevoEstadoSelect = document.getElementById("nuevoEstadoMascota");

            if (!modalElement) {
                console.error("Modal 'modalActualizarEstado' no encontrado");
                alert("Error: Modal no encontrado. Asegúrate de que el modal esté incluido en la página.");
                return;
            }
            
            if (!procesoIdInput || !nuevoEstadoSelect) {
                console.error("Elementos del formulario no encontrados:", {
                    procesoIdInput: !!procesoIdInput,
                    nuevoEstadoSelect: !!nuevoEstadoSelect,
                });
                alert("Error: Formulario incompleto. Verifica que todos los campos estén presentes.");
                return;
            }
            
            // Establecer los valores en el modal
            procesoIdInput.value = idAdopcion;
            nuevoEstadoSelect.value = estadoActual || "";
            
            // Mostrar el modal
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        });
    });

    const btnGuardarEstado = document.getElementById("btnGuardarEstado");
    if (btnGuardarEstado) {
        
        const newBtn = btnGuardarEstado.cloneNode(true);
        btnGuardarEstado.parentNode.replaceChild(newBtn, btnGuardarEstado);
        
        newBtn.addEventListener("click", function() {
    const formActualizarEstado = document.getElementById("formActualizarEstado");
    if (!formActualizarEstado) {
        console.error("Formulario 'formActualizarEstado' no encontrado");
        alert("Error: Formulario no encontrado");
        return;
    }

    const formData = new FormData(formActualizarEstado);
    formData.append('action', 'actualizar_estado');

    // Captura el estado de la mascota si existe el campo
    const nuevoEstadoMascota = document.getElementById("nuevoEstadoMascota");
    if (nuevoEstadoMascota) {
        formData.append('nuevo_estado_mascota', nuevoEstadoMascota.value);
    }

    fetch(`${window.BASE_URL}/controller/adopcion/AdopcionController.php`, {
        method: "POST",
        body: formData
    })
            .then(response => {
                if (!response.ok) throw new Error("Error en la respuesta del servidor");
                return response.text();
            })
            .then(data => {
                alert(data);
                
                // Cerrar el modal
                const modalElement = document.getElementById("modalActualizarEstado");
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                }
                
                cargarCrudAdopcion();
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Error al actualizar el estado");
            });
        });
    }

    // BOTONES DESCARGAR PDF
const botonesDescargarPDF = document.querySelectorAll(".btn-descargar-pdf");
botonesDescargarPDF.forEach(btn => {
    btn.addEventListener("click", function () {
        const idFormulario = this.dataset.formularioId;
        
        // Crear enlace temporal para descarga
        const url = `${window.BASE_URL}/controller/adopcion/AdopcionController.php?accion=generar_pdf&id_formulario=${idFormulario}`;
        
        // Abrir en nueva ventana/tab para descarga
        window.open(url, '_blank');
    });
});

    // BOTONES ELIMINAR
    const botonesEliminar = document.querySelectorAll(".btn-eliminar-adopcion");
    botonesEliminar.forEach(btn => {
        btn.addEventListener("click", function () {
            const idProceso = this.dataset.formularioId;
            if (confirm("¿Estás seguro de que deseas eliminar esta adopcion?")) {
                const formData = new FormData();
                formData.append('accion', 'eliminar');
                formData.append('id_proceso', idProceso);

                fetch(`${window.BASE_URL}/controller/adopcion/AdopcionController.php`, {
                    method: "POST",
                    body: formData
                })
                    .then(res => res.text())
                    .then(data => {
                        alert(data);
                        cargarCrudAdopcion();
                    })
                    .catch(error => {
                        console.error("Error:", error);
                    });
            }
        });
    });
}

// Inicializar cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {
    const btnAdopcion = document.getElementById("btn-cargar-adopcion");
    if (btnAdopcion) {
        btnAdopcion.addEventListener("click", function (e) {
            e.preventDefault();
            cargarCrudAdopcion();
        });
    }
});

window.cargarCrudAdopcion = cargarCrudAdopcion;