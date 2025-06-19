const BASE_URL = window.location.origin + "/petsconnectMVC";
window.BASE_URL = window.BASE_URL || '';

// =========== CRUD DE PRODUCTOS =========== //
window.cargarCrudProductos = function () {
    fetch("view/producto/ProductoView.php")
        .then(response => response.text())
        .then(data => {
            // Cambia el objetivo al contenedor del main
            const mainContainer = document.getElementById("main-content") ||
                document.getElementById("crud-container") ||
                document.getElementById("crud");

            if (mainContainer) {
                mainContainer.innerHTML = data;
=======
            const mainContainer = document.querySelector(".main-content #crud-container") ||
                document.getElementById("crud-container") ||
                document.getElementById("crud");


            if (mainContainer) {
                mainContainer.innerHTML = data;

>>>>>>> ProductoCrud
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


// CRUD TIPO MASCOTA VISTA 
window.cargarCrudTipoMascota = function () {
    fetch("view/tipo_mascota/Tipo_mascota_view.php")
        .then(response => response.text())
        .then(data => {
            // Cambia el objetivo al contenedor del main
            const mainContainer = document.getElementById("main-content")
                var tabla = document.querySelector("#tipo_mascota");
                var tablaMascota = new DataTable(tabla);

            if (mainContainer) {
                mainContainer.innerHTML = data;
                
            } else {
                console.error("No se encontró el contenedor principal para el CRUD");
            }
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}