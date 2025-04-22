/**
 * 📌 Función para cargar un CRUD en la vista principal de manera dinámica.
 * 
 * 🔹 ¿Qué hace esta función?
 *    - Esta función permite cargar distintos CRUDs (Crear, Leer, Actualizar, Eliminar) 
 *      dentro de un espacio específico en la página sin necesidad de recargarla.
 * 
 * 🔹 ¿Cómo funciona?
 * 1️⃣ La función se llama `cargarCrud(nombreCrud)`, donde `nombreCrud` es el nombre 
 *    del archivo PHP que contiene el CRUD que queremos mostrar.
 * 
 * 2️⃣ Se usa `fetch()` para hacer una petición al servidor y obtener el contenido del 
 *    archivo PHP correspondiente (por ejemplo, `"crud_vacunas.php"` si `nombreCrud` es `"crud_vacunas"`).
 * 
 * 3️⃣ Una vez que `fetch()` recibe la respuesta, convertimos el contenido en texto 
 *    con `.then(response => response.text())`. Esto permite insertar el contenido en el DOM.
 * 
 * 4️⃣ Luego, usamos `document.getElementById("crud").innerHTML = data;` para colocar 
 *    el contenido dentro de un `<div>` con `id="crud"`. 
 *    📌 **IMPORTANTE:** Este `div` debe existir en el HTML y es donde se mostrará el CRUD.
 * 
 * 5️⃣ Después de insertar el contenido, llamamos a la función `inicializarModal();`
 *    📌 **¿Por qué llamamos a `inicializarModal()`?**
 *       - Si el CRUD tiene un modal, este no funcionará correctamente si no se inicializa.
 *       - `inicializarModal()` se encarga de asignar los eventos y hacer que el modal funcione.
 *       - Sin esta función, el botón para abrir el modal no haría nada.
 * 
 * 🚀 **Esta función permite cambiar entre diferentes CRUDs sin recargar la página, 
 *     haciendo que la experiencia sea más rápida y fluida.**
 */


// =========== CRUD DE PROCESOS DE ADOPCION =========== //

function cargarCrudProcesos() {
    fetch("crud_procesoAdopcion.php") // Nombre del archivo PHP a incluir
        .then(response => response.text()) // Convertir respuesta en texto
        .then(data => {
            document.getElementById("crud").innerHTML = data; // Incluir contenido

            inicializarModalProcesos();
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}


/**
 * 📌 Función `inicializarModal()`: Permite que el modal funcione correctamente en la página.
 * 
 * 🔹 ¿Por qué necesitamos `inicializarModal()`?
 *    - Cuando un modal se carga dinámicamente en la página (por ejemplo, con `fetch()`),
 *      los eventos de clic en el botón y en el botón de cerrar (`X`) no funcionan automáticamente.
 *    - Esta función asigna los eventos para **abrir** y **cerrar** el modal correctamente.
 * 
 * 🔹 ¿Cómo funciona la función?
 * 1️⃣ **Esperamos 100 milisegundos (`setTimeout()`)** antes de ejecutar el código.
 *    📌 **¿Por qué?**  
 *       - Esto da tiempo para que el contenido del modal se inserte en el DOM si fue cargado dinámicamente.  
 *       - Si intentamos acceder a los elementos antes de que existan, el código fallará.
 * 
 * 2️⃣ **Obtenemos los elementos del modal** con `document.getElementById()`:
 *    - `modal`: La ventana emergente (`id="modal-procesos"`).
 *    - `btn`: El botón que abre el modal (`id="openModal"`).
 *    - `close`: El botón "X" para cerrar el modal (`class="close"`).
 * 
 * 3️⃣ **Evento para abrir el modal (`btn.onclick`)**:
 *    - Cuando el usuario hace clic en el botón, cambiamos `modal.style.display = "block";`
 *    - Esto hace que el modal se muestre en la pantalla.
 * 
 * 4️⃣ **Evento para cerrar el modal con "X" (`close.onclick`)**:
 *    - Cuando el usuario hace clic en el botón "X", cambiamos `modal.style.display = "none";`
 *    - Esto oculta el modal.
 * 
 * 5️⃣ **Cerrar el modal al hacer clic fuera de él (`window.onclick`)**:
 *    - Si el usuario hace clic fuera del modal (en el fondo oscuro), lo ocultamos.
 *    - Se verifica con `if (event.target == modal)`.
 * 
 */
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
    fetch("crud_vacunas.php")// Nombre del archivo PHP a incluir
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
    fetch("crud_tipo_mascota.php") // Nombre del archivo PHP a incluir
        .then(response => response.text()) // Convertir respuesta en texto
        .then(data => {
            document.getElementById("crud").innerHTML = data; // Incluir contenido

            inicializarModalTipoMascota();
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}

// =========== MODAL DE TIPO DE MASCOTA =========== //

function inicializarModalTipoMascota() { // funcion para inicializar el modal 
    setTimeout(() => {
        const modal = document.getElementById("modal-tipoMascota");
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


// =========== CRUD DE FUNDACION =========== //

function cargarCrudFundaciones() {
    fetch("crud_fundaciones.php") // Nombre del archivo PHP a incluir
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