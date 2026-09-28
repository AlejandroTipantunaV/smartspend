
document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', (event) => {
            const password = form.querySelector('input[name="password"]');
            const confirmPassword = form.querySelector('input[name="confirm_password"]');
            
            // Eliminar alertas previas del cliente si existen
            const oldAlert = form.querySelector('.js-alert');
            if (oldAlert) oldAlert.remove();

            // Validar coincidencia de contraseñas (en registro y actualización)
            if (password && confirmPassword && confirmPassword.value.trim() !== '') {
                if (password.value !== confirmPassword.value) {
                    event.preventDefault(); // Detener el envío del formulario
                    showClientError(form, 'Las contraseñas no coinciden. Por favor, verifícalas.');
                    confirmPassword.focus();
                    return;
                }
            }

            // Validar longitud mínima de contraseña si fue ingresada
            if (password && password.value.length > 0 && password.value.length < 5) {
                event.preventDefault();
                showClientError(form, 'La contraseña debe tener al menos 5 caracteres.');
                password.focus();
                return;
            }
        });
    });

    // Función para manipular el DOM y mostrar errores sin recargar la página
    function showClientError(form, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger js-alert';
        alertDiv.setAttribute('role', 'alert');
        alertDiv.innerText = message;

        form.insertBefore(alertDiv, form.firstChild);
    }
});