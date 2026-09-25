<?php
/**
 * Partial: formulario de transacción (crear / editar).
 *
 * Variables esperadas:
 * - string $formAction   URL del controlador
 * - string $submitLabel  Texto del botón
 * - array  $categorias   Lista de categorías
 * - array  $values       Valores iniciales (tipo, id_categoria, monto, concepto, fecha_transaccion)
 * - int|null $idTransaccion  Si es edición
 * - bool $showCancel
 */
$values = array_merge([
    'tipo' => '',
    'id_categoria' => '',
    'monto' => '',
    'concepto' => '',
    'fecha_transaccion' => date('Y-m-d'),
], $values ?? []);

$showCancel = $showCancel ?? false;
$idTransaccion = $idTransaccion ?? null;
$categoriasJson = e(json_encode($categorias, JSON_UNESCAPED_UNICODE));
?>
<form id="form-transaccion"
      class="form-grid"
      method="POST"
      action="<?php echo e($formAction); ?>"
      novalidate
      data-validate="transaction">

    <?php if ($idTransaccion !== null): ?>
        <input type="hidden" name="id_transaccion" value="<?php echo (int) $idTransaccion; ?>">
    <?php endif; ?>

    <div class="form-group">
        <label for="monto">Monto <span class="required" aria-hidden="true">*</span></label>
        <input type="number"
               id="monto"
               name="monto"
               step="0.01"
               min="0.01"
               required
               aria-required="true"
               placeholder="0.00"
               inputmode="decimal"
               value="<?php echo e((string) $values['monto']); ?>">
        <span class="field-error" id="error-monto" role="alert"></span>
    </div>

    <div class="form-group">
        <label for="tipo">Tipo <span class="required" aria-hidden="true">*</span></label>
        <select id="tipo" name="tipo" required aria-required="true">
            <option value="">Seleccione...</option>
            <option value="ingreso" <?php echo $values['tipo'] === 'ingreso' ? 'selected' : ''; ?>>Ingreso</option>
            <option value="gasto" <?php echo $values['tipo'] === 'gasto' ? 'selected' : ''; ?>>Gasto</option>
        </select>
        <span class="field-error" id="error-tipo" role="alert"></span>
    </div>

    <div class="form-group">
        <label for="id_categoria">Categoría <span class="required" aria-hidden="true">*</span></label>
        <select id="id_categoria"
                name="id_categoria"
                required
                aria-required="true"
                data-selected="<?php echo e((string) $values['id_categoria']); ?>"
                data-categorias='<?php echo $categoriasJson; ?>'>
            <option value="">Seleccione un tipo primero...</option>
        </select>
        <span class="field-error" id="error-id_categoria" role="alert"></span>
    </div>

    <div class="form-group">
        <label for="fecha_transaccion">Fecha <span class="required" aria-hidden="true">*</span></label>
        <input type="date"
               id="fecha_transaccion"
               name="fecha_transaccion"
               required
               aria-required="true"
               value="<?php echo e((string) $values['fecha_transaccion']); ?>">
        <span class="field-error" id="error-fecha_transaccion" role="alert"></span>
    </div>

    <div class="form-group form-group--full">
        <label for="concepto">Concepto <span class="required" aria-hidden="true">*</span></label>
        <input type="text"
               id="concepto"
               name="concepto"
               maxlength="255"
               required
               aria-required="true"
               placeholder="Ej: Compra de víveres"
               value="<?php echo e((string) $values['concepto']); ?>">
        <span class="field-error" id="error-concepto" role="alert"></span>
    </div>

    <div class="form-actions form-group--full">
        <?php if ($showCancel): ?>
            <a href="transactions.php" class="btn btn-outline">Cancelar</a>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary"><?php echo e($submitLabel); ?></button>
    </div>
</form>
