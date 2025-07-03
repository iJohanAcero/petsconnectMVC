window.cargarPerfilGuardian = function () {
    fetch("view/perfil/perfilGuardian.php")
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



    document.addEventListener("DOMContentLoaded", () => {
    const btnCargarPerfil = document.getElementById("btn-cargar-perfilGuardian");

    if (btnCargarPerfil) {
        btnCargarPerfil.addEventListener("click", function (e) {
            e.preventDefault();
            cargarPerfilGuardian();
        });
    }
});