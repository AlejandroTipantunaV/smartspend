/**
 * SmartSpend — Validación de formularios (cliente)
 * Formularios con data-validate="transaction"
 */
(function () {
    'use strict';

    function parseCategorias(select) {
        try {
            return JSON.parse(select.getAttribute('data-categorias') || '[]');
        } catch (e) {
            return [];
        }
    }

    function fillCategorias(tipoSelect, categoriaSelect) {
        var tipo = tipoSelect.value;
        var categorias = parseCategorias(categoriaSelect);
        var selected = categoriaSelect.getAttribute('data-selected');
        categoriaSelect.replaceChildren(new Option('Seleccione...', ''));
        categorias.filter(c => Number(c.estado) === 1 && (!tipo || c.tipo === tipo)).forEach(c => {
            const option = new Option(c.nombre_categoria, c.id_categoria);
            option.selected = String(c.id_categoria) === String(selected);
            categoriaSelect.add(option);
        });
    }

    function setError(fieldId, message) {
        var el = document.getElementById('error-' + fieldId);
        var input = document.getElementById(fieldId);
        if (el) {
            el.textContent = message || '';
        }
        if (input) {
            input.setAttribute('aria-describedby', 'error-' + fieldId);
            input.setAttribute('aria-invalid', message ? 'true' : 'false');
            if (message) {
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        }
    }

    function clearErrors(form) {
        form.querySelectorAll('.field-error').forEach(function (el) {
            el.textContent = '';
        });
        form.querySelectorAll('.is-invalid').forEach(function (el) {
            el.classList.remove('is-invalid');
            el.setAttribute('aria-invalid', 'false');
        });
    }

    function validateTransactionForm(form) {
        clearErrors(form);
        var ok = true;

        var monto = form.querySelector('#monto');
        var tipo = form.querySelector('#tipo');
        var categoria = form.querySelector('#id_categoria');
        var concepto = form.querySelector('#concepto');
        var fecha = form.querySelector('#fecha_transaccion');

        if (!monto || !monto.value || Number(monto.value) <= 0) {
            setError('monto', 'Ingrese un monto mayor a 0.');
            ok = false;
        }

        if (!tipo || !tipo.value) {
            setError('tipo', 'Seleccione el tipo.');
            ok = false;
        }

        if (!categoria || !categoria.value) {
            setError('id_categoria', 'Seleccione una categoría.');
            ok = false;
        }

        if (!concepto || concepto.value.trim().length < 3) {
            setError('concepto', 'El concepto debe tener al menos 3 caracteres.');
            ok = false;
        }

        if (!fecha || !fecha.value) {
            setError('fecha_transaccion', 'Seleccione una fecha.');
            ok = false;
        }

        // Actualiza estilo del botón según tipo
        var submitBtn = form.querySelector('[type="submit"]');
        if (submitBtn && tipo) {
            submitBtn.classList.remove('btn-success', 'btn-danger', 'btn-primary');
            if (tipo.value === 'ingreso') {
                submitBtn.classList.add('btn-success');
            } else if (tipo.value === 'gasto') {
                submitBtn.classList.add('btn-danger');
            } else {
                submitBtn.classList.add('btn-primary');
            }
        }

        return ok;
    }

    function initTransactionForm(form) {
        var tipoSelect = form.querySelector('#tipo');
        var categoriaSelect = form.querySelector('#id_categoria');

        if (tipoSelect && categoriaSelect) {
            fillCategorias(tipoSelect, categoriaSelect);
            tipoSelect.addEventListener('change', function () {
                categoriaSelect.removeAttribute('data-selected');
                fillCategorias(tipoSelect, categoriaSelect);
                const button = form.querySelector('[type="submit"]');
                button.classList.remove('btn-primary', 'btn-success', 'btn-danger');
                button.classList.add(tipoSelect.value === 'ingreso' ? 'btn-success' : tipoSelect.value === 'gasto' ? 'btn-danger' : 'btn-primary');

            });
        }

        form.addEventListener('submit', function (e) {
            if (!validateTransactionForm(form)) {
                e.preventDefault();
                var firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form[data-validate="transaction"]').forEach(initTransactionForm);
    });
})();
