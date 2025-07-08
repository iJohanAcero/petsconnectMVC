window.cargarPerfilGuardian = function () {
    fetch("view/guardian/perfilGuardian.php")
        .then(response => {
            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            const mainContainer = document.getElementById("main-content");

            if (mainContainer) {
                mainContainer.innerHTML = data;

                setTimeout(() => {
                    inicializarEventosPerfilGuardian();
                }, 100);
            }
        })
        .catch(error => {
            console.error("❌ Error al cargar PHP:", error);
        });
};

function abrirModalCrearGuardian() {
    const modalElement = document.getElementById("modal-guardian");
    if (modalElement) {
        const modalBootstrap = new bootstrap.Modal(modalElement);
        modalBootstrap.show();
    }
}

// ===================== EVENTOS DEL PERFIL ===================== //
function inicializarEventosPerfilGuardian() {
    // ✏️ BOTONES DE EDITAR
    const botonesEditar = document.querySelectorAll(".btn-editar-perfilGuardian");

    botonesEditar.forEach(boton => {
        boton.addEventListener("click", function () {
            const id = this.dataset.id;

            fetch(`view/guardian/GuardianEdit.php?id=${encodeURIComponent(id)}`)
                .then(response => {
                    if (!response.ok) throw new Error("No se pudo cargar el formulario de edición");
                    return response.text();
                })
                .then(html => {
                    const contenedor = document.getElementById("contenido-editar");
                    contenedor.innerHTML = html;

                    const modal = new bootstrap.Modal(document.getElementById("modal-editar-perfilGuardian"));
                    modal.show();

                    const formEditar = document.getElementById("form-editar-perfilGuardian");

                    if (formEditar) {
                        formEditar.onsubmit = function (e) {
                            e.preventDefault();
                            const formData = new FormData(formEditar);

                            fetch(`${window.BASE_URL}/controller/guardian/GuardianController.php`, {
                                method: "POST",
                                body: formData
                            })
                                .then(response => response.text())
                                .then(data => {
                                    if (data.trim()) {
                                        alert(data);
                                    }
                                    modal.hide();
                                    cargarPerfilGuardian();
                                });
                        };
                    }
                })
                .catch(error => {
                    console.error("❌ Error al cargar perfil guardian:", error);
                });
        });
    });
}


    document.addEventListener("DOMContentLoaded", () => {
    const botones = document.querySelectorAll(".btn-cargar-perfilGuardian");

    botones.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            cargarPerfilGuardian();
        });
    });
});