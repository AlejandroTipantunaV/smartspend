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
        // Conserva selección actual o la de data-selected (edición)
        var selected =
            categoriaSelect.value ||
            categoriaSelect.getAttribute('data-selected') ||
            '';

        categoriaSelect.replaceChildren(new Option('Seleccione...', ''));

        categorias
            .filter(function (c) {
                return Number(c.estado) === 1 && (!tipo || c.tipo === tipo);
            })
            .forEach(function (c) {
                var option = new Option(
                    c.nombre_categoria,
                    String(c.id_categoria)
                );
                if (String(c.id_categoria) === String(selected)) {
                    option.selected = true;
                }
                categoriaSelect.add(option);
            });
    }

    function setError(form, fieldId, message) {
        var el = form.querySelector('#error-' + fieldId);
        var input = form.querySelector('#' + fieldId);
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

    function updateSubmitStyle(form, tipo) {
        var submitBtn = form.querySelector('[type="submit"]');
        if (!submitBtn) {
            return;
        }
        submitBtn.classList.remove('btn-success', 'btn-danger', 'btn-primary');
        if (tipo === 'ingreso') {
            submitBtn.classList.add('btn-success');
        } else if (tipo === 'gasto') {
            submitBtn.classList.add('btn-danger');
        } else {
            submitBtn.classList.add('btn-primary');
        }
    }

    function validateTransactionForm(form) {
        clearErrors(form);
        var ok = true;

        var monto = form.querySelector('#monto');
        var tipo = form.querySelector('#tipo');
        var categoria = form.querySelector('#id_categoria');
        var concepto = form.querySelector('#concepto');
        var fecha = form.querySelector('#fecha_transaccion');

        var montoVal = monto ? String(monto.value).trim().replace(',', '.') : '';
        var montoNum = parseFloat(montoVal);

        if (!monto || montoVal === '' || !isFinite(montoNum) || montoNum <= 0) {
            setError(form, 'monto', 'Ingrese un monto mayor a 0.');
            ok = false;
        }

        if (!tipo || !tipo.value) {
            setError(form, 'tipo', 'Seleccione el tipo.');
            ok = false;
        }

        if (!categoria || !String(categoria.value).trim()) {
            setError(form, 'id_categoria', 'Seleccione una categoría.');
            ok = false;
        }

        var conceptoVal = concepto ? concepto.value.trim() : '';
        if (!concepto || conceptoVal.length < 3) {
            setError(form, 'concepto', 'El concepto debe tener al menos 3 caracteres.');
            ok = false;
        }

        if (!fecha || !fecha.value) {
            setError(form, 'fecha_transaccion', 'Seleccione una fecha.');
            ok = false;
        }

        updateSubmitStyle(form, tipo ? tipo.value : '');
        return ok;
    }

    function initTransactionForm(form) {
        // Evita tooltips nativos duplicados; usamos nuestros mensajes
        form.setAttribute('novalidate', 'novalidate');

        var tipoSelect = form.querySelector('#tipo');
        var categoriaSelect = form.querySelector('#id_categoria');

        if (tipoSelect && categoriaSelect) {
            fillCategorias(tipoSelect, categoriaSelect);
            updateSubmitStyle(form, tipoSelect.value);

            tipoSelect.addEventListener('change', function () {
                // Al cambiar tipo, la categoría previa puede no aplicar
                var prev = categoriaSelect.value;
                categoriaSelect.removeAttribute('data-selected');
                fillCategorias(tipoSelect, categoriaSelect);
                if (prev && categoriaSelect.querySelector('option[value="' + prev + '"]')) {
                    categoriaSelect.value = prev;
                } else {
                    categoriaSelect.value = '';
                }
                setError(form, 'id_categoria', '');
                updateSubmitStyle(form, tipoSelect.value);
            });
        }

        // Limpia el error del campo al corregirlo (evita mensajes "fantasma")
        form.querySelectorAll('input, select, textarea').forEach(function (field) {
            var eventName = field.tagName === 'SELECT' ? 'change' : 'input';
            field.addEventListener(eventName, function () {
                if (!field.id) {
                    return;
                }
                setError(form, field.id, '');
            });
        });

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
        document
            .querySelectorAll('form[data-validate="transaction"]')
            .forEach(initTransactionForm);
    });
})();
