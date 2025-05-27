
// ================= REGEX PARA VALIDAR EMAIL =========================
let input = document.getElementById('email');
    let icon = document.getElementById('checked-icon');

    // Estado inicial
    icon.innerHTML = '✕';

    const validarEmail = (email) => {
        const regex = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
        return regex.test(email);
    }

    input.addEventListener('input', () => {

    if (validarEmail(input.value)) {
        
        icon.innerHTML = '✓';
        icon.classList.remove('NoEmail');
        icon.classList.add('correctoEmail');
    } else {
        
        icon.innerHTML = '✕';
        icon.classList.remove('correctoEmail');
        icon.classList.add('NoEmail');
    }
});