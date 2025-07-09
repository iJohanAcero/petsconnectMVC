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

function abrirModalGuardian() {
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
                    document.getElementById("contenido-editar").innerHTML = html;
                        const modalElement = document.getElementById("modal-editar-perfilGuardian");
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();

                        const inputImagen = document.getElementById("input-imagen");
const previewImagen = document.getElementById("preview-imagen");

if (inputImagen && previewImagen) {
    inputImagen.addEventListener("change", function () {
        const archivo = this.files[0];
        if (archivo) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImagen.src = e.target.result;
            };
            reader.readAsDataURL(archivo);
        }
    });
}
                        const formEditar = document.getElementById("form-editar-perfilGuardian");
                    if (formEditar) {
                        formEditar.addEventListener("submit", function (e) {
                            e.preventDefault();
                            const formData = new FormData(formEditar);

                                    fetch(`${window.BASE_URL}/controller/perfil/PerfilController.php`, {
                                        method: "POST",
                                        body: formData
                                    })
                                        .then(res => res.text()) 
                                        .then(data => {
                                            alert(data);
                                            const modalElement = document.getElementById("modal-editar-perfilGuardian");
                                            const modal = bootstrap.Modal.getInstance(modalElement);
                                            if (modal) modal.hide();
                                            cargarPerfilGuardian();
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