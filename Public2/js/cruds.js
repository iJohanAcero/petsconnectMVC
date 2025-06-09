
// =========== CRUD DE PROCESOS DE ADOPCION =========== //

function cargarCrudProcesos() {
    fetch("Views/crud_procesoAdopcion.php") // Nombre del archivo PHP a incluir
        .then(response => response.text()) // Convertir respuesta en texto
        .then(data => {
            document.getElementById("crud").innerHTML = data; // Incluir contenido

            inicializarModalProcesos();
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}


// =========== MODAL DE PROCESOS DE ADOPCION =========== //

function inicializarModalProcesos() { // funcion para inicializar el modal 
    setTimeout(() => {
        const modal = document.getElementById("modal-procesos");
        const btn = document.getElementById("openModal");
        const close = document.querySelector(".close");

        btn.onclick = function () {
            modal.style.display = "block"; //le decimos que bloquee el display cuando se haga click en el boton
        };

        close.onclick = function () {
            modal.style.display = "none"; //le decimos que cuando de click en close(X) se desaparezca el display
        };

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none"; //le decimos que cuando de click en otro lugar fuera del modal, este desaparezca.
            }
        };
    }, 100); // Pequeño delay para asegurar que el DOM se haya actualizado
}


// =========== CRUD DE VACUNACION =========== //

function cargarCrudVacunas() {
    fetch("Views/crud_vacunas.php")// Nombre del archivo PHP a incluir
        .then(response => response.text()) // Convertir respuesta en texto
        .then(data => {
            document.getElementById("crud").innerHTML = data; // Incluir contenido

            inicializarModalVacunas();
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}

// =========== MODAL DE VACUNACION =========== //

function inicializarModalVacunas() { // funcion para inicializar el modal 
    setTimeout(() => {
        const modal = document.getElementById("modal-vacunas");
        const btn = document.getElementById("openModal");
        const close = document.querySelector(".close");

        btn.onclick = function () {
            modal.style.display = "block"; //le decimos que bloquee el display cuando se haga click en el boton
        };

        close.onclick = function () {
            modal.style.display = "none"; //le decimos que cuando de click en close(X) se desaparezca el display
        };

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none"; //le decimos que cuando de click en otro lugar fuera del modal, este desaparezca.
            }
        };
    }, 100); // Pequeño delay para asegurar que el DOM se haya actualizado
}



// =========== CRUD DE TIPO DE MASCOTA =========== //

function cargarCrudTipoMascota() {
    fetch("view/tipo_mascota/Tipo_mascota_view.php") // Nombre del archivo PHP a incluir
        .then(response => response.text()) // Convertir respuesta en texto
        .then(data => {
            document.getElementById("crud").innerHTML = data; // Incluir contenido

            inicializarModalTipoMascota();
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}

// =========== MODAL DE TIPO DE MASCOTA =========== //


function inicializarModalTipoMascota() {
    setTimeout(() => {
        const modal = document.getElementById("modal-tipoMascota");
        const btn = document.getElementById("openModal");
        const close = document.querySelector(".close");

        // Abrir modal
        btn.onclick = function () {
            modal.style.display = "block";
        };

        //Cerrar modal por (X)
        close.onclick = function () {
            modal.style.display = "none";
        };

        // Cerrar haciendo clic fuera del modal
        window.onclick = function (event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };

        // Enviar formulario vía fetch + FormData
        const form = document.querySelector(".form-modal");

        if (form) {
            form.addEventListener("submit", function (e) {
                e.preventDefault();
            
                const formData = new FormData(form);
            
                fetch("controller/tipo_mascota/add.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    form.reset();
                    modal.style.display = "none";
                    cargarCrudTipoMascota();
                })
                .catch(error => {
                    console.error("Error en la petición:", error);
                });
            });
        }

    }, 100); // Delay para que el DOM se cargue si viene por innerHTML
}


// =========== CRUD DE FUNDACION =========== //

function cargarCrudFundaciones() {
    fetch("Views/crud_fundaciones.php") // Nombre del archivo PHP a incluir
        .then(response => response.text()) // Convertir respuesta en texto
        .then(data => {
            document.getElementById("crud").innerHTML = data; // Incluir contenido

            inicializarModalFundaciones();
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}

// =========== MODAL DE FUNDACION =========== //

function inicializarModalFundaciones() { // funcion para inicializar el modal 
    setTimeout(() => {
        const modal = document.getElementById("modal-fundaciones");
        const btn = document.getElementById("openModal");
        const close = document.querySelector(".close");

        btn.onclick = function () {
            modal.style.display = "block"; //le decimos que bloquee el display cuando se haga click en el boton
        };

        close.onclick = function () {
            modal.style.display = "none"; //le decimos que cuando de click en close(X) se desaparezca el display
        };

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none"; //le decimos que cuando de click en otro lugar fuera del modal, este desaparezca.
            }
        };
    }, 100); // Pequeño delay para asegurar que el DOM se haya actualizado
}

// =========== CRUD DE PRODUCTOS =========== //

function cargarCrudProductos() {
    fetch("view/producto/ProductoView.php") // Nombre del archivo PHP a incluir
        .then(response => response.text()) // Convertir respuesta en texto
        .then(data => {
            document.getElementById("crud").innerHTML = data; // Incluir contenido

            inicializarModalProductos();
            inicializarModalProductosEdit();
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}

// =========== MODAL DE PRODUCTOS =========== //

function inicializarModalProductos() {
    setTimeout(() => {
        const modal = document.getElementById("modal-productos");
        const btn = document.getElementById("openModal");
        const close = document.querySelector(".close");

        // Abrir modal
        btn.onclick = function () {
            modal.style.display = "block";
        };

        //Cerrar modal por (X)
        close.onclick = function () {
            modal.style.display = "none";
        };

        // Cerrar haciendo clic fuera del modal
        window.onclick = function (event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };

        // Enviar formulario vía fetch + FormData
        const form = document.querySelector(".form-modal");

        if (form) {
            form.addEventListener("submit", function (e) {
                e.preventDefault();
            
                const formData = new FormData(form);
            
                fetch("controller/producto/add.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    form.reset();
                    modal.style.display = "none";
                    cargarCrudProductos();
                })
                .catch(error => {
                    console.error(" Error al enviar el formulario.", error);
                });
            });
        }

    }, 100); // Delay para que el DOM se cargue si viene por innerHTML
}

