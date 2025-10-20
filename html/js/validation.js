document.addEventListener('DOMContentLoaded', function() {
    // Validación del formulario de registro
    const registerForm = document.getElementById('register_form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            if (!validarRegistro()) {
                e.preventDefault();
            }
        });
    }

    // Validación del formulario de login
    const loginForm = document.getElementById('login_form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            if (!validarLogin()) {
                e.preventDefault();
            }
        });
    }
});

function validarRegistro() {
    let isValid = true;

    // Validar DNI
    const dni = document.getElementById('dni');
    if (dni && !validarDNI(dni.value)) {
        alert('DNI no válido. Formato: 12345678-Z');
        dni.focus();
        isValid = false;
    }

    // Validar teléfono
    const telefono = document.getElementById('telefono');
    if (telefono && !/^\d{9}$/.test(telefono.value)) {
        alert('Teléfono debe tener exactamente 9 dígitos');
        telefono.focus();
        isValid = false;
    }

    // Validar email
    const email = document.getElementById('email');
    if (email && !validarEmail(email.value)) {
        alert('Email no válido');
        email.focus();
        isValid = false;
    }

    // Validar nombre y apellidos (solo letras)
    const nombre = document.getElementById('nombre');
    const apellidos = document.getElementById('apellidos');
    
    if (nombre && !/^[A-Za-zÁÉÍÓÚáéíóúñÑ ]+$/.test(nombre.value)) {
        alert('El nombre solo puede contener letras y espacios');
        nombre.focus();
        isValid = false;
    }
    
    if (apellidos && !/^[A-Za-zÁÉÍÓÚáéíóúñÑ ]+$/.test(apellidos.value)) {
        alert('Los apellidos solo pueden contener letras y espacios');
        apellidos.focus();
        isValid = false;
    }

    return isValid;
}

function validarLogin() {
    // Validación básica para login
    const username = document.getElementById('username');
    const password = document.getElementById('password');
    
    if (username && username.value.trim() === '') {
        alert('Por favor, introduce tu nombre de usuario');
        username.focus();
        return false;
    }
    
    if (password && password.value.trim() === '') {
        alert('Por favor, introduce tu contraseña');
        password.focus();
        return false;
    }
    
    return true;
}

function validarDNI(dni) {
    // Validar formato DNI español
    if (dni.length !== 10) return false;
    
    const numero = dni.substring(0, 8);
    const letra = dni.substring(9, 10).toUpperCase();
    const letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
    
    if (!/^\d+$/.test(numero)) return false;
    
    const letraCorrecta = letras[parseInt(numero) % 23];
    return letra === letraCorrecta;
}

function validarEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Validación en tiempo real para DNI
const dniInput = document.getElementById('dni');
if (dniInput) {
    dniInput.addEventListener('blur', function() {
        if (this.value && !validarDNI(this.value)) {
            this.style.borderColor = 'red';
        } else {
            this.style.borderColor = '';
        }
    });
}
