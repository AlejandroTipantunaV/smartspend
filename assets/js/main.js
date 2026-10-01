/**
 * SmartSpend - Main JavaScript & Accessibility Interactivity
 */

document.addEventListener('DOMContentLoaded', () => {
    const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
    const primaryNavMenu = document.querySelector('.nav-menu');

    if (mobileNavToggle && primaryNavMenu) {
        mobileNavToggle.addEventListener('click', () => {
            const isExpanded = mobileNavToggle.getAttribute('aria-expanded') === 'true';
            
            mobileNavToggle.setAttribute('aria-expanded', !isExpanded);
            mobileNavToggle.setAttribute('aria-label', isExpanded ? 'Abrir menú de navegación' : 'Cerrar menú de navegación');
            primaryNavMenu.classList.toggle('is-active');

            const toggleIcon = mobileNavToggle.querySelector('.iconify');
            if (toggleIcon) {
                toggleIcon.setAttribute('data-icon', !isExpanded ? 'lucide:x' : 'lucide:menu');
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && primaryNavMenu.classList.contains('is-active')) {
                primaryNavMenu.classList.remove('is-active');
                mobileNavToggle.setAttribute('aria-expanded', 'false');
                mobileNavToggle.setAttribute('aria-label', 'Abrir menú de navegación');
                mobileNavToggle.focus();
                
                const toggleIcon = mobileNavToggle.querySelector('.iconify');
                if (toggleIcon) {
                    toggleIcon.setAttribute('data-icon', 'lucide:menu');
                }
            }
        });
    }

    // Confirmación al eliminar transacción
    document.querySelectorAll('form.js-confirm-delete').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const message = form.getAttribute('data-confirm') || '¿Confirmar eliminación?';
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });

    initToastDismissal();
});

function initToastDismissal() {
    const toastBoxes = document.querySelectorAll('.toast-container .alert-box');
    toastBoxes.forEach(toast => {
        setupSingleToast(toast);
    });
}

function setupSingleToast(toast) {
    if (toast.dataset.initialized) return;
    toast.dataset.initialized = 'true';

    const closeBtn = toast.querySelector('.alert-close-btn');

    const dismissToast = () => {
        if (toast.classList.contains('toast-fadeOut')) return;
        toast.classList.add('toast-fadeOut');
        setTimeout(() => {
            const container = toast.parentElement;
            if (toast.contains(document.activeElement)) document.getElementById('main-content')?.focus();
            toast.remove();
            if (container && container.children.length === 0) {
                container.remove();
            }
        }, 300);
    };

    if (closeBtn) {
        closeBtn.addEventListener('click', dismissToast);
    }


}

/**
 * Global Helper to trigger Toast Notifications
 */
window.showToast = function(message, type = 'info', title = null, position = 'top-right') {
    type = ['success', 'danger', 'warning', 'info'].includes(type) ? type : 'info';
    const validPositions = ['top-right', 'top-left', 'top-center', 'bottom-right', 'bottom-left', 'bottom-center'];
    const posClass = validPositions.includes(position) ? 'toast-' + position : 'toast-top-right';

    let container = document.querySelector(`.toast-container.${posClass}`);
    if (!container) {
        container = document.createElement('aside');
        container.className = `toast-container ${posClass}`;
        container.setAttribute('aria-label', 'System notifications');
        document.body.appendChild(container);
    }

    const titles = {
        success: '¡Éxito!',
        danger: '¡Error!',
        warning: '¡Advertencia!',
        info: '¡Información!'
    };

    const icons = {
        success: 'lucide:check-circle-2',
        danger: 'lucide:x-circle',
        warning: 'lucide:alert-triangle',
        info: 'lucide:info'
    };

    const toastTitle = title || titles[type] || 'Notificación';
    const iconName = icons[type] || 'lucide:info';

    const alertArticle = document.createElement('article');
    alertArticle.className = `alert-box alert-${type}`;
    alertArticle.setAttribute('role', 'alert');
    alertArticle.setAttribute('aria-live', type === 'danger' ? 'assertive' : 'polite');

    alertArticle.innerHTML = `
        <div class="alert-icon-container">
            <span class="iconify alert-icon" data-icon="${iconName}" aria-hidden="true"></span>
        </div>
        <div class="alert-body">
            <strong class="alert-title"></strong>
            <p class="alert-message"></p>
        </div>
        <button type="button" class="alert-close-btn" aria-label="Cerrar notificación">&times;</button>
    `;

    alertArticle.querySelector('.alert-title').textContent = toastTitle;
    alertArticle.querySelector('.alert-message').textContent = message;
    container.appendChild(alertArticle);
    setupSingleToast(alertArticle);

    if (window.Iconify && window.Iconify.scan) {
        window.Iconify.scan(alertArticle);
    }
};
