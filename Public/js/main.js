document.addEventListener('DOMContentLoaded', function () {

  const hamburger = document.querySelector(".toggle-btn");

  const toggler = document.querySelector("#icon");

  const sidebar = document.querySelector("#sidebar");

  if (hamburger && toggler && sidebar) {
    hamburger.addEventListener("click", function () {
      sidebar.classList.toggle("expand");
      toggler.classList.toggle("uil-angle-double-right");
      toggler.classList.toggle("uil-angle-double-left");
    });
  }

  const toggleBtnMobile = document.querySelector('.toggle-btn-mobile');
  if (toggleBtnMobile) {
    toggleBtnMobile.addEventListener('click', function () {
      sidebar.classList.toggle('expand');
      document.body.classList.toggle('sidebar-open');
    });
  }


  // ✅ Carga dinámica del portafolio de mascotas al cargar la página
  
  const btnTodos = document.getElementById('btn-ver-todos');
  if (btnTodos) {
    btnTodos.addEventListener('click', function (e) {
      e.preventDefault();

      // ✅ Carga dinámica del portafolio de mascotas
      fetch('view/portafolio/PortafoleoView.php')
        .then(res => res.text())
        .then(html => {
          // 🔄 Reemplaza el contenido del área principal
          const mainContent = document.getElementById('main-content');
          if (mainContent) {
            mainContent.innerHTML = html;

            // 📦 Vuelve a cargar el JS del modal de mascotas
            const script = document.createElement('script');
            script.src = 'Public/js/PortafoleoMascota.js';
            document.body.appendChild(script);
          }
        });
    });
  }
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

  function changeLogo() {
    if (ud_header.classList.contains("sticky")) {
      logo.src = "../../Public/images/logo/logo.png";
    } else {
      logo.src = "../../Public/images/logo/logo-oscuro.png";
    }
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
