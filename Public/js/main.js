document.addEventListener('DOMContentLoaded', function() {
    
    const hamburger = document.querySelector(".toggle-btn");
    const toggler = document.querySelector("#icon");
    const sidebar = document.querySelector("#sidebar");

    // Toggle para desktop
    if(hamburger && toggler && sidebar) {
        hamburger.addEventListener("click", function() {
            sidebar.classList.toggle("expand");
            toggler.classList.toggle("uil-angle-double-right");
            toggler.classList.toggle("uil-angle-double-left");
        });
    }

    // Variables para móvil
    const toggleBtnMobile = document.querySelector('.toggle-btn-mobile');
    const body = document.body;

    // Solo activar en móvil (pantallas menores a 992px)
    function isMobile() {
        return window.innerWidth <= 991;
    }

    // Función para cerrar el sidebar
    function closeSidebar() {
        if (sidebar && sidebar.classList.contains('expand')) {
            sidebar.classList.remove('expand');
            body.classList.remove('sidebar-open');
        }
    }

    // Función para abrir el sidebar
    function openSidebar() {
        if (sidebar) {
            sidebar.classList.add('expand');
            body.classList.add('sidebar-open');
        }
    }

    // Event listener ÚNICO para el botón móvil
    if (toggleBtnMobile) {
        toggleBtnMobile.addEventListener('click', function(e) {
            e.stopPropagation(); // Evitar que se propague el clic
            
            if (sidebar.classList.contains('expand')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }

    // Event listener para clics en el documento (cerrar sidebar)
    document.addEventListener('click', function(e) {
        // Solo funcionar en móvil
        if (!isMobile()) return;
        
        // Si el sidebar no está expandido, no hacer nada
        if (!sidebar || !sidebar.classList.contains('expand')) return;
        
        // Si se hace clic dentro del sidebar, no cerrar
        if (sidebar.contains(e.target)) return;
        
        // Si se hace clic en el botón toggle móvil, no cerrar (ya se maneja arriba)
        if (toggleBtnMobile && toggleBtnMobile.contains(e.target)) return;
        
        // Cerrar el sidebar
        closeSidebar();
    });

    // Event listener para prevenir que clics dentro del sidebar lo cierren
    if (sidebar) {
        sidebar.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Cerrar sidebar cuando cambie el tamaño de pantalla a desktop
    window.addEventListener('resize', function() {
        if (!isMobile() && sidebar && sidebar.classList.contains('expand')) {
            closeSidebar();
        }
    });
});

(function () {

  "use strict";

  // ======= Sticky
  window.onscroll = function () {
    const ud_header = document.querySelector(".ud-header");
    const sticky = ud_header.offsetTop;
    const logo = document.querySelector(".navbar-brand img");

    if (window.pageYOffset > sticky) {
      ud_header.classList.add("sticky");
    } else {
      ud_header.classList.remove("sticky");
    }

    // === logo change
    if (ud_header.classList.contains("sticky")) {
      logo.src = "Public/images/logo/logo.png";
    } else {
      logo.src = "Public/images/logo/logo-oscuro.png";
    }

    // show or hide the back-top-top button
    const backToTop = document.querySelector(".back-to-top");
    if (
      document.body.scrollTop > 50 ||
      document.documentElement.scrollTop > 50
    ) {
      backToTop.style.display = "flex";
    } else {
      backToTop.style.display = "none";
    }
  };

  //===== close navbar-collapse when a  clicked
  let navbarToggler = document.querySelector(".navbar-toggler");
  const navbarCollapse = document.querySelector(".navbar-collapse");

  document.querySelectorAll(".ud-menu-scroll").forEach((e) =>
    e.addEventListener("click", () => {
      navbarToggler.classList.remove("active");
      navbarCollapse.classList.remove("show");
    })
  );
  navbarToggler.addEventListener("click", function () {
    navbarToggler.classList.toggle("active");
    navbarCollapse.classList.toggle("show");
  });

  // ===== submenu
  const submenuButton = document.querySelectorAll(".nav-item-has-children");
  submenuButton.forEach((elem) => {
    elem.querySelector("a").addEventListener("click", () => {
      elem.querySelector(".ud-submenu").classList.toggle("show");
    });
  });

  // ===== wow js
  new WOW().init();

  // ====== scroll top js
  function scrollTo(element, to = 0, duration = 500) {
    const start = element.scrollTop;
    const change = to - start;
    const increment = 20;
    let currentTime = 0;

    const animateScroll = () => {
      currentTime += increment;

      const val = Math.easeInOutQuad(currentTime, start, change, duration);

      element.scrollTop = val;

      if (currentTime < duration) {
        setTimeout(animateScroll, increment);
      }
    };

    animateScroll();
  }

  Math.easeInOutQuad = function (t, b, c, d) {
    t /= d / 2;
    if (t < 1) return (c / 2) * t * t + b;
    t--;
    return (-c / 2) * (t * (t - 2) - 1) + b;
  };

  document.querySelector(".back-to-top").onclick = () => {
    scrollTo(document.documentElement);
  };

})();