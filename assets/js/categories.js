document.addEventListener('DOMContentLoaded', () => {
    const iconInput = document.getElementById('icono');
    const iconOptions = document.querySelectorAll('.icon-option');
    
    if (iconOptions.length > 0 && iconInput) {
        iconOptions.forEach(option => {
            option.addEventListener('click', () => {
                // Remove selected class from all
                iconOptions.forEach(opt => opt.classList.remove('selected'));
                // Add to clicked
                option.classList.add('selected');
                // Set hidden input value
                iconInput.value = option.getAttribute('data-icon-name');
            });
        });
    }

    const colorInput = document.getElementById('color');
    const colorHex = document.getElementById('color-hex');
    
    if (colorInput && colorHex) {
        colorInput.addEventListener('input', (e) => {
            colorHex.textContent = e.target.value;
        });
    }

    // Form validation
    const categoryForm = document.querySelector('.js-validate-category-form');
    if (categoryForm) {
        categoryForm.addEventListener('submit', (e) => {
            let hasError = false;

            const typeSelect = document.getElementById('tipo');
            const errorType = document.getElementById('error-tipo');
            if (!typeSelect.value) {
                errorType.textContent = 'Por favor selecciona un tipo.';
                hasError = true;
            } else {
                errorType.textContent = '';
            }

            const nameInput = document.getElementById('nombre_categoria');
            const errorName = document.getElementById('error-nombre_categoria');
            if (!nameInput.value.trim() || nameInput.value.length < 3) {
                errorName.textContent = 'El nombre debe tener al menos 3 caracteres.';
                hasError = true;
            } else {
                errorName.textContent = '';
            }

            if (hasError) {
                e.preventDefault();
            }
        });
    }
});
