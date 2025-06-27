window.cargarDashboard = function () {
    fetch("view/dashboard/dashboard.php")
        .then(response => response.text())
        .then(data => {
            // Cambia el objetivo al contenedor del main
            const mainContainer = document.getElementById("main-content")

            if (mainContainer) {
                mainContainer.innerHTML = data;
            } else {
                console.error("No se encontró el contenedor principal");
            }
        })
        .catch(error => console.error("Error al cargar PHP:", error));
};

window.cargarPerfil = function () {
    fetch("view/perfil/perfilGuardian.php")
        .then(response => response.text())
        .then(data => {
            // Cambia el objetivo al contenedor del main
            const mainContainer = document.getElementById("main-content")

            if (mainContainer) {
                mainContainer.innerHTML = data;
            } else {
                console.error("No se encontró el contenedor principal");
            }
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}