/**
 * SmartSpend — Interacciones DOM generales
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var filtro = document.getElementById('filtro-tipo');
        if (filtro && filtro.form) {
            filtro.addEventListener('change', function () {
                filtro.form.submit();
            });
        }

        document.querySelectorAll('form.js-confirm-delete').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                var message = form.getAttribute('data-confirm') || '¿Confirmar eliminación?';
                if (!window.confirm(message)) {
                    event.preventDefault();
                }
            });
        });
    });
})();
