// Espera a que todo el contenido de la página se cargue antes de ejecutar el script
document.addEventListener('DOMContentLoaded', function() {

    // --- FUNCIÓN REUTILIZABLE PARA VALIDAR DATOS DE USUARIO ---
    // Esta función contiene la lógica para validar los campos de registro y modificación de usuario.
    function validateUserForm(form) {
        form.addEventListener('submit', function(event) {
            // Prevenir el envío del formulario para validarlo primero
            event.preventDefault();
            
            let errors = [];
            
            // Recoger valores de los campos
            const nombre = document.getElementById('nombre').value;
            const apellidos = document.getElementById('apellidos').value;
            const dni = document.getElementById('dni').value;
            const telefono = document.getElementById('telefono').value;
            const email = document.getElementById('email').value;

            // 1. Validar Nombre y Apellidos (solo texto y espacios)
            if (!/^[a-zA-Z\s]+$/.test(nombre)) errors.push("El nombre solo puede contener letras y espacios.");
            if (!/^[a-zA-Z\s]+$/.test(apellidos)) errors.push("Los apellidos solo pueden contener letras y espacios.");

            // 2. Validar Teléfono (exactamente 9 dígitos)
            if (!/^\d{9}$/.test(telefono)) errors.push("El teléfono debe tener exactamente 9 dígitos.");
            
            // 3. Validar Email
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errors.push("El formato del email no es válido.");

            // 4. Validar DNI (formato y letra)
            const dniRegex = /^\d{8}[A-Z]$/;
            if (!dniRegex.test(dni.toUpperCase())) {
                errors.push("El formato del DNI no es válido (debe ser 8 números y 1 letra).");
            } else {
                const numeroDni = dni.substring(0, 8);
                const letraDni = dni.substring(8).toUpperCase();
                const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
                const letraCalculada = letras[numeroDni % 23];
                if (letraDni !== letraCalculada) {
                    errors.push("La letra del DNI no es correcta.");
                }
            }

            // Si hay errores, mostrarlos. Si no, enviar el formulario.
            if (errors.length > 0) {
                alert("Por favor, corrige los siguientes errores:\n\n- " + errors.join("\n- "));
            } else {
                form.submit(); // Todo correcto, se envía el formulario
            }
        });
    }

    // --- BUSCAR Y ASIGNAR LA VALIDACIÓN A LOS FORMULARIOS DE USUARIO ---
    const registerForm = document.getElementById('register_form');
    if (registerForm) {
        validateUserForm(registerForm);
    }

    const modifyUserForm = document.getElementById('user_modify_form');
    if (modifyUserForm) {
        validateUserForm(modifyUserForm);
    }

    // --- VALIDACIÓN PARA FORMULARIOS DE REVIEW (AÑADIR Y MODIFICAR) ---
    const itemForm = document.getElementById('item_add_form') || document.getElementById('item_modify_form');
    if (itemForm) {
        itemForm.addEventListener('submit', function(event) {
            event.preventDefault();
            let errors = [];

            const titulo = document.getElementById('titulo_pelicula').value;
            const ano = document.getElementById('ano_estreno').value;
            const puntuacion = document.getElementById('puntuacion').value;

            if (titulo.trim() === "") errors.push("El título no puede estar vacío.");
            if (isNaN(ano) || ano.length !== 4) errors.push("El año debe ser un número de 4 dígitos.");
            if (isNaN(puntuacion) || puntuacion < 1 || puntuacion > 10) errors.push("La puntuación debe ser un número entre 1 y 10.");

            if (errors.length > 0) {
                alert("Por favor, corrige los siguientes errores:\n\n- " + errors.join("\n- "));
            } else {
                itemForm.submit();
            }
        });
    }
});