
// ================= REGEX PARA VALIDAR EMAIL =========================
    let input = document.getElementById('email');
    let icon = document.getElementById('checked-icon');

    // Estado inicial
    icon.innerHTML = '✕';
        icon.classList.add('Mal');
        icon.classList.remove('Correcto');

    const validarEmail = (email) => {
        const regex = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
        return regex.test(email);
    }

    input.addEventListener('input', () => {

    if (validarEmail(input.value)) {
        
        icon.innerHTML = '✓';
        icon.classList.remove('Mal');
        icon.classList.add('Correcto');
    } else {
        
        icon.innerHTML = '✕';
        icon.classList.remove('Correcto');
        icon.classList.add('Mal');
    }
});

let password = document.getElementById('contrasena');
    let icon2 = document.getElementById('checked-icon2');

    if (password && icon2) {
        // Estado inicial
        icon2.innerHTML = '✕';
        icon2.classList.add('Mal');
        icon2.classList.remove('Correcto');

        const validarPassword = (password) => {
            const regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/i;
            return regex.test(password);
        }

        password.addEventListener('input', () => {
            if (validarPassword(password.value)) {
                icon2.innerHTML = '✓';
                icon2.classList.remove('Mal');
                icon2.classList.add('Correcto');
            } else {
                icon2.innerHTML = '✕';
                icon2.classList.remove('Correcto');
                icon2.classList.add('Mal');
            }
        });
}