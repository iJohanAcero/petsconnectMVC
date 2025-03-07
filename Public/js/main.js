
//================ SCROLL DE MASCOTAS RECOMENDADAS ===========================
const scroller = document.querySelectorAll(".scroll");

if (!window.matchMedia("(prefers-reduced-motion: reduce)").matches) { /* Le decimos que se use solo cuando el usuario no tenga desactivada las animaciones */
    addAnimation();
}

function addAnimation() {
    scroller.forEach((scroller) => {
        scroller.setAttribute("data-animated", true);
        
        const scrolleranimated = scroller.querySelector(".recomendados");
        const scrollercontent = Array.from(scrolleranimated.children);

        scrollercontent.forEach((item) => {
            const duplicateItem = item.cloneNode(true);

            // Asegurarse de copiar el fondo del CSS (background image)
            const backgroundStyle = window.getComputedStyle(item).getPropertyValue('background');
            if (backgroundStyle) {
                duplicateItem.style.background = backgroundStyle; // Copiar el background
            }

            duplicateItem.setAttribute("aria-hidden", true);
            scrolleranimated.appendChild(duplicateItem);
        });
    });
}


//================= SIDEBAR =================================

const MenuItem = document.querySelectorAll('.menu-item');


//---------------FUNCION PARA VERIFICAR EL ESTADO ACTIVO O NO DEL POPUP
const mascotasPopup = document.querySelector('.mascotas-popup');

//------------ FUNCION DE GUARDIANES
const guardianesBox = document.querySelector('#guardianes-box'); //FUNCION PARA EL COLOR DE LA CAJA
const guardianes = document.querySelector('.guardianes');  //PARA SELECCIONAR LA CLASE de todos los guardianes
const guardian = guardianes.querySelectorAll('.guardian-enlinea, .guardian-offline');
const guardianBuscador = document.querySelector('#guardian-buscador') //FUNCION DEL INPUT DEL BUSCADOR DE GUARDIANES

//--------- REMOVER LA CLASE ACTIVO DE TODOS LOS ITEMS DEL MENU
const cambiarActivo = () => {
    MenuItem.forEach(item => {
        item.classList.remove('activo');
    })
}

MenuItem.forEach(item =>{
    item.addEventListener('click', () => {
        cambiarActivo(); //Añadimos la funcion de camibar el activo para que no queden todos activos
        item.classList.add('activo');

        // AÑADIMOS LA FUNCIONALIDAD DE DESPLEGAR EL POPUP DE MASCOTAS CUANDO SE LE DE CLIK

        if(item.id === 'popup-mascotas'){ // FUNCION DEL DISPLAY BLOCK O NONE DEPENDIENDO EL ESTADO DEL POPUP
            if (mascotasPopup.style.display === 'block') {
                mascotasPopup.style.display = 'none';
            } else {
                mascotasPopup.style.display = 'block';
            } 
        } else {
            mascotasPopup.style.display = 'none';
        }
    })
})



// ======= CAJA DE GUARDIANES =================

guardianesBox.addEventListener('click', () => {
    guardianes.style.boxShadow = '0 0 1rem var(--color-primary)'; //PARA QUE CAMBIE DE COLOR CON EL CLICK AL CLOR PRIMARIO

    setTimeout(() => {
        guardianes.style.boxShadow = 'none';
    }, 1500);
})

// ================= FILTRADO DE PERFILES ====================
//BUSCADOR DE PERFILES
const buscarGuardian = () => {
    const val = guardianBuscador.value.toLowerCase();
    console.log(val);
    guardian.forEach(buscar => {
        let name = buscar.querySelector('h5').textContent.toLowerCase();
        if(name.indexOf(val) != -1) {
            buscar.style.display ='flex';
        } else {
            buscar.style.display = 'none';
        }
    })
}
//BUSCADOR PERFIL
guardianBuscador.addEventListener('keyup', buscarGuardian);

//CAMBIAR DE OFFLINE A ONLINE

// Seleccionar elementos del DOM
const enlineaBtn = document.querySelector('.enlinea');
const offlineBtn = document.querySelector('.offline');
const guardianesEnlinea = document.querySelectorAll('.guardian-enlinea');
const guardianesOffline = document.querySelectorAll('.guardian-offline');

// Función para mostrar solo guardianes en línea
enlineaBtn.addEventListener('click', () => {
    guardianesEnlinea.forEach(guardian => {
        guardian.style.display = 'flex'; // Mostrar guardianes en línea
    });
    guardianesOffline.forEach(guardian => {
        guardian.style.display = 'none'; // Ocultar guardianes offline
    });
});

// Función para mostrar solo guardianes offline
offlineBtn.addEventListener('click', () => {
    guardianesEnlinea.forEach(guardian => {
        guardian.style.display = 'none'; // Ocultar guardianes en línea
    });
    guardianesOffline.forEach(guardian => {
        guardian.style.display = 'flex'; // Mostrar guardianes offline
    });
});


// ============= MOSTRAR ENLINEA Y OFLLINE CUANDO SE USE LA BARRA BUSCADOR

guardianBuscador.addEventListener('click', () => {
    guardianesOffline.forEach(guardian => {
        guardian.style.display = 'flex'; // Ocultar guardianes offline
    });

    guardianesEnlinea.forEach(guardian => {
        guardian.style.display = 'flex '; // Ocultar guardianes offline
    });

    categoriaOn.forEach(guardian => {
        guardian.classList.remove('activoOn');
    })
})


// =============  CAMBIAR DE COLOR LA CATEGORIA ONLINE ===========
const categoriaOn = document.querySelectorAll('.enlinea, .offline')

const cambiarCategoria = () => {
    categoriaOn.forEach(item => {
        item.classList.remove('activoOn');
    })
}

categoriaOn.forEach(item =>{
    item.addEventListener('click', () => {
        cambiarCategoria(); //Añadimos la funcion de camibar el activo para que no queden todos activos
        item.classList.add('activoOn');
    })
})


// ============== CAMBIAR AL MODO OSCURO Y MODO CLARO ==============

let darkmode = localStorage.getItem('darkmode')
const cambioTema = document.getElementById('cambio-tema')

function cambiarLogo(){
    let imagen = document.getElementById("logo");

    if (imagen.src.includes("logo.png")) {
        imagen.src = "../Public/images/logo-oscuro.png";
    } else {
        imagen.src = "../Public/images/logo.png";
    }
}

const activarDarkmode = () => {
    document.body.classList.add('darkmode')
    localStorage.setItem('darkmode','activo')
}

const desactivarDarkmode = () => {
    document.body.classList.remove('darkmode')
    localStorage.setItem('darkmode', null)
}

if (darkmode === 'activo') {
    desactivarDarkmode()
    
}

cambioTema.addEventListener('click', () => {
    darkmode = localStorage.getItem('darkmode')
    if(darkmode !== 'activo') {
        activarDarkmode()  
    } else {
        desactivarDarkmode()
    } 
});


//========= CARGAR CRUDS DIFERENTES EN LA PAGINA DE CRUD =========//

// ======= ESTA FUNCION CARGA LAS CRUD DISPONIBLES ===============//
function cargarCruds() {
    fetch("cruds_disponibles.php") // Nombre del archivo PHP a incluir
        .then(response => response.text()) // Convertir respuesta en texto
        .then(data => {
            document.getElementById("cruds").innerHTML = data; // Incluir contenido
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}


// =========  CRUD VACUNAS CARGAR ================//
function cargarCrudVacunas() {
    fetch("crud_vacunas.php")// Nombre del archivo PHP a incluir
        .then(response => response.text()) // Convertir respuesta en texto
        .then(data => {
            document.getElementById("crud").innerHTML = data; // Incluir contenido

            inicializarModal();
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}

// =========  CRUD PRODUCTOS CARGAR ================//
function cargarCrudProductos() {
    fetch("crud_productos.php") // Nombre del archivo PHP a incluir
        .then(response => response.text()) // Convertir respuesta en texto
        .then(data => {
            document.getElementById("crud").innerHTML = data; // Incluir contenido
        })
        .catch(error => console.error("Error al cargar PHP:", error));
}

// ======= CARGAR MODAL DE AGERGAR Y ELIMINAR =========== //

function inicializarModal() { // funcion para inicializar el modal 
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