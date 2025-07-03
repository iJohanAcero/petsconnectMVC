window.cargarPerfilFundacion = function () {
    fetch("view/perfil/perfilFundacion.php")
        .then(response => {
            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            const mainContainer = document.getElementById("main-content");

            if (mainContainer) {
                mainContainer.innerHTML = data;

                setTimeout(() => {
                    inicializarEventosPerfilFundacion();
                }, 100);
            }
        })
        .catch(error => {
            console.error("❌ Error al cargar PHP:", error);
        });
};



    document.addEventListener("DOMContentLoaded", () => {
    const btnCargarPerfilFundacion = document.getElementById("btn-cargar-perfilFundacion");

    if (btnCargarPerfilFundacion) {
        btnCargarPerfilFundacion.addEventListener("click", function (e) {
            e.preventDefault();
            cargarPerfilFundacion();
        });
    }
});