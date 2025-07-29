window.cargarCartelera = function () {
    fetch("view/cartelera/cartelera.php")
        .then(response => {
            if (!response.ok) throw new Error("Error en la red");
            return response.text();
        })
        .then(data => {
            const mainContainer = document.getElementById("main-content");

            if (mainContainer) {
                mainContainer.innerHTML = data;

                setTimeout(() => {
                    renderizarCarteleraMascotas(); // <-- Aquí llamas la función para mostrar las cards
                    // inicializarEventosCartelera(); // Si tienes otros eventos, déjalos aquí
                }, 100);
            }
        })
        .catch(error => {
            console.error("❌ Error al cargar PHP:", error);
        });
};

function renderizarCarteleraMascotas() {
    fetch("controller/mascota/MascotaApi.php")
        .then(response => response.json())
        .then(mascotas => {
            const cardsContainer = document.querySelector('.cards');
            if (!cardsContainer) return;

            cardsContainer.innerHTML = ''; // Limpiar cartelera

            mascotas.forEach(mascota => {
                const card = document.createElement('div');
                card.className = 'card m-2';
                card.style.width = '18rem';

                card.innerHTML = `
                    <img src="Public/images/mascotas/${mascota.imagen}" class="card-img-top" alt="${mascota.nombre}">
                    <div class="card-body">
                        <h5 class="card-title">${mascota.nombre}</h5>
                        <p class="card-text">
                            Edad: ${mascota.edad_meses} meses<br>
                            Sexo: ${mascota.sexo}<br>
                            Especie: ${mascota.especie}<br>
                            Raza: ${mascota.raza}<br>
                            Tamaño: ${mascota.tamaño}<br>
                            Pelaje: ${mascota.tipo_pelaje}<br>
                            Estado: ${mascota.tipo_estado}
                        </p>
                    </div>
                `;
                cardsContainer.appendChild(card);
            });
        })
        .catch(error => {
            console.error('Error al cargar mascotas:', error);
        });
}

    document.addEventListener("DOMContentLoaded", () => {
    const botones = document.querySelectorAll(".btn-cargar-cartelera");

    botones.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            cargarCartelera();
        });
    });
});