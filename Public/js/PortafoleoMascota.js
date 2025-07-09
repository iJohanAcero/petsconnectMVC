document.addEventListener("DOMContentLoaded", function () {
    // 1️⃣ Cargar las tarjetas desde el servidor (archivo PHP)
    fetch("../../view/mascota/MascotaPortafoleoCards.php")
        .then(response => response.text())
        .then(html => {
            const contenedor = document.querySelector(".carousel-inner");
            contenedor.innerHTML = html;

            // 2️⃣ Activar el evento click en cada tarjeta
            document.querySelectorAll(".pet-card").forEach(card => {
                card.addEventListener("click", function () {
                    const nombre = this.dataset.name;
                    const edad = this.dataset.age;
                    const sexo = this.dataset.sexo;
                    const especie = this.dataset.especie;
                    const raza = this.dataset.raza;
                    const tamaño = this.dataset.tamaño;
                    const pelaje = this.dataset.pelaje;
                    const estado = this.dataset.estado;
                    const imagen = this.dataset.imagen;

                    // 3️⃣ Mostrar en el modal
                    document.getElementById("petModalTitle").textContent = nombre;
                    document.getElementById("petModalBody").innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <img src="${imagen}" class="img-fluid mb-3" alt="${nombre}">
                            </div>
                            <div class="col-md-6">
                                <p><strong>Edad:</strong> ${edad}</p>
                                <p><strong>Sexo:</strong> ${sexo}</p>
                                <p><strong>Especie:</strong> ${especie}</p>
                                <p><strong>Raza:</strong> ${raza}</p>
                                <p><strong>Tamaño:</strong> ${tamaño}</p>
                                <p><strong>Pelaje:</strong> ${pelaje}</p>
                                <p><strong>Estado:</strong> ${estado}</p>
                            </div>
                        </div>
                    `;

                    // 4️⃣ Mostrar el modal
                    const modal = new bootstrap.Modal(document.getElementById("petModal"));
                    modal.show();
                });
            });
        })
        .catch(error => {
            console.error("Error al cargar las mascotas:", error);
        });
});
