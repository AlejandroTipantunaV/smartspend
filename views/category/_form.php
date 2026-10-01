<form method="POST" action="<?php echo htmlspecialchars($formAction); ?>" class="form-horizontal js-validate-category-form" id="category-form">
    <style>
        /* Specific styles for responsive icon grid */
        .icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }
        .icon-option {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            border: 2px solid transparent;
            border-radius: 8px;
            cursor: pointer;
            background-color: var(--surface-hover, #f8f9fa);
            transition: all 0.2s;
            color: var(--text-color, #333);
        }
        .icon-option:hover {
            background-color: var(--border-color, #e2e8f0);
        }
        .icon-option.selected {
            border-color: var(--primary-color, #4a90e2);
            background-color: var(--primary-color, #4a90e2);
            color: white;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
        }
        /* Mobile layout adjustment */
        .form-row-responsive {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        @media (min-width: 768px) {
            .form-row-responsive {
                grid-template-columns: 1fr 1fr;
            }
        }
        .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        .checkbox-container input[type="checkbox"] {
            margin-right: 10px;
            width: 18px;
            height: 18px;
        }
        .color-picker-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .color-picker-wrapper input[type="color"] {
            height: 45px;
            width: 60px;
            cursor: pointer;
            padding: 0;
            border: 1px solid var(--border-color, #e2e8f0);
            border-radius: 4px;
        }
    </style>

    <?php if ($categoryId !== null): ?>
        <input type="hidden" name="id_categoria" value="<?php echo (int) $categoryId; ?>">
    <?php endif; ?>

    <div class="form-group">
        <label for="tipo">Tipo de Categoría <span class="required">*</span></label>
        <select name="tipo" id="tipo" required>
            <option value="" disabled <?php echo empty($values['tipo']) ? 'selected' : ''; ?>>Seleccione un tipo</option>
            <option value="ingreso" <?php echo ($values['tipo'] ?? '') === 'ingreso' ? 'selected' : ''; ?>>Ingreso</option>
            <option value="gasto" <?php echo ($values['tipo'] ?? '') === 'gasto' ? 'selected' : ''; ?>>Gasto</option>
        </select>
        <div class="error-message" id="error-tipo"></div>
    </div>

    <div class="form-group">
        <label for="nombre_categoria">Nombre <span class="required">*</span></label>
        <input type="text" id="nombre_categoria" name="nombre_categoria" required
               minlength="3" maxlength="50"
               value="<?php echo htmlspecialchars((string) ($values['nombre_categoria'] ?? '')); ?>"
               placeholder="Ej: Comida, Sueldo...">
        <div class="error-message" id="error-nombre_categoria"></div>
    </div>

    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" maxlength="255" rows="3"
                  placeholder="Breve descripción..."><?php echo htmlspecialchars((string) ($values['descripcion'] ?? '')); ?></textarea>
    </div>

    <div class="form-row-responsive">
        <div class="form-group">
            <label>Icono</label>
            <input type="hidden" id="icono" name="icono" value="<?php echo htmlspecialchars((string) ($values['icono'] ?? 'mdi:folder')); ?>">
            
            <?php
            $hardcodedIcons = [
                'mdi:folder', 'mdi:food', 'mdi:car', 'mdi:bolt', 'mdi:movie', 
                'mdi:heart-pulse', 'mdi:school', 'mdi:cash', 'mdi:store', 'mdi:chart-line',
                'mdi:gift', 'mdi:airplane', 'mdi:cart', 'mdi:laptop', 'mdi:home', 
                'mdi:hanger', 'mdi:train', 'mdi:coffee', 'mdi:basketball', 'mdi:medical-bag'
            ];
            $selectedIcon = (string) ($values['icono'] ?? 'mdi:folder');
            ?>
            <div class="icon-grid" id="icon-picker-grid">
                <?php foreach ($hardcodedIcons as $ico): ?>
                    <div class="icon-option <?php echo $ico === $selectedIcon ? 'selected' : ''; ?>" data-icon-name="<?php echo $ico; ?>">
                        <span class="iconify" data-icon="<?php echo $ico; ?>"></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <small class="text-muted" style="display: block; margin-top: 5px;">Selecciona el ícono que mejor represente la categoría.</small>
        </div>

        <div class="form-group">
            <label for="color">Color Representativo</label>
            <div class="color-picker-wrapper">
                <input type="color" id="color" name="color"
                       value="<?php echo htmlspecialchars((string) ($values['color'] ?? '#4a90e2')); ?>">
                <span class="color-hex-display" id="color-hex" style="font-family: monospace; font-size: 1.1rem; font-weight: 500;">
                    <?php echo htmlspecialchars((string) ($values['color'] ?? '#4a90e2')); ?>
                </span>
            </div>
            <small class="text-muted" style="display: block; margin-top: 10px;">Haz clic en el cuadro para elegir un color.</small>
        </div>
    </div>

    <div class="form-group" style="margin-top: 1.5rem;">
        <div class="checkbox-container">
            <input type="hidden" name="estado" value="0">
            <input type="checkbox" id="estado" name="estado" value="1" <?php echo (!isset($values['estado']) || (int) $values['estado'] === 1) ? 'checked' : ''; ?>>
            <label for="estado" style="margin-bottom: 0; font-weight: normal; cursor: pointer;">Categoría Activa</label>
        </div>
        <small class="text-muted" style="display: block; margin-top: 4px; margin-left: 28px;">Desmarca esta opción si no quieres que esta categoría aparezca al crear transacciones.</small>
    </div>

    <div class="form-actions">
        <?php if ($showCancel): ?>
            <a href="index.php" class="btn btn-outline">Cancelar</a>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary"><?php echo e($submitLabel); ?></button>
    </div>
</form>
