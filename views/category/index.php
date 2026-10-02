<?php
/**
 * View: Categories list and management.
 */
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../models/Category.php';

require_login('../../views/auth/login.php');
$idUsuario = (int)($_SESSION['user_id'] ?? $_SESSION['id_usuario'] ?? 0);

$categoryModel = new Category();
$categorias = $categoryModel->getAll($idUsuario);
$flash = pull_flash();

$basePath = '../../';
$pageTitle = 'Categorías - SmartSpend';
include __DIR__ . '/../../includes/header.php';
?>

<section class="page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1>Administrar Categorías</h1>
        <p class="text-muted">Gestiona las categorías de tus ingresos y gastos.</p>
    </div>
    <a href="create.php" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
        <span class="iconify" data-icon="lucide:plus"></span> Nueva Categoría
    </a>
</section>

<?php include __DIR__ . '/../partials/flash.php'; ?>

<!-- Categories List -->
<section class="card-panel" aria-labelledby="historial-title">
    <div class="section-toolbar">
        <h3 id="historial-title">Mis Categorías</h3>
    </div>

    <?php if (empty($categorias)): ?>
        <p class="empty-state">No hay categorías configuradas.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table" aria-describedby="historial-title">
                <thead>
                    <tr>
                        <th scope="col" style="width: 50px; text-align: center;">Ícono</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Estado</th>
                        <th scope="col" style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $c): ?>
                        <?php
                        $isIncome = $c['tipo'] === 'ingreso';
                        $badgeClass = $isIncome ? 'success' : 'danger';
                        $typeLabel = $isIncome ? 'Ingreso' : 'Gasto';
                        $colorHex = htmlspecialchars((string) ($c['color'] ?? '#cccccc'));
                        $iconStr = htmlspecialchars((string) ($c['icono'] ?? 'mdi:folder'));
                        $isActive = (int)$c['estado'] === 1;
                        ?>
                        <tr>
                            <td data-label="Ícono" style="text-align: center; vertical-align: middle;">
                                <div style="
                                    display: inline-flex;
                                    align-items: center;
                                    justify-content: center;
                                    width: 36px;
                                    height: 36px;
                                    border-radius: 50%;
                                    background-color: <?php echo $colorHex; ?>33; /* 20% opacity */
                                    color: <?php echo $colorHex; ?>;
                                    font-size: 1.25rem;">
                                    <span class="iconify" data-icon="<?php echo $iconStr; ?>"></span>
                                </div>
                            </td>
                            <td data-label="Nombre">
                                <div>
                                    <strong><?php echo e($c['nombre_categoria']); ?></strong>
                                    <?php if (!empty($c['descripcion'])): ?>
                                        <div class="text-muted" style="font-size: 0.85em; margin-top: 2px; line-height: 1.2;"><?php echo e($c['descripcion']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td data-label="Tipo">
                                <span class="badge badge-<?php echo e($badgeClass); ?>">
                                    <?php echo e($typeLabel); ?>
                                </span>
                            </td>
                            <td data-label="Estado">
                                <?php if ($isActive): ?>
                                    <span class="badge" style="background-color: #e0f2f1; color: #00695c;">Activa</span>
                                <?php else: ?>
                                    <span class="badge" style="background-color: #eeeeee; color: #595959;">Inactiva</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Acciones" class="text-right">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                                    <a class="btn btn-sm btn-outline"
                                       href="edit.php?id=<?php echo (int) $c['id_categoria']; ?>">
                                        Editar
                                    </a>
                                    <?php if ($isActive): ?>
                                        <form method="POST"
                                              action="<?php echo e($basePath); ?>controllers/CategoryController.php?action=delete"
                                              class="inline-form js-confirm-delete"
                                              data-confirm="¿Estás seguro de desactivar esta categoría? No aparecerá al crear nuevas transacciones, pero se conservará en tu historial.">
                                            <?= csrf_field() ?>
                                            <input type="hidden"
                                                   name="id_categoria"
                                                   value="<?php echo (int) $c['id_categoria']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" style="margin: 0; width: 90px; text-align: center;">Desactivar</button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST"
                                              action="<?php echo e($basePath); ?>controllers/CategoryController.php?action=activate"
                                              class="inline-form js-confirm-delete"
                                              data-confirm="¿Estás seguro de reactivar esta categoría? Volverá a estar disponible para nuevas transacciones.">
                                            <?= csrf_field() ?>
                                            <input type="hidden"
                                                   name="id_categoria"
                                                   value="<?php echo (int) $c['id_categoria']; ?>">
                                            <button type="submit" class="btn btn-sm" style="margin: 0; width: 90px; text-align: center; background-color: #00695c; color: white; border-color: #00695c;">Activar</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php
$extraScripts = ['assets/js/categories.js'];
include __DIR__ . '/../../includes/footer.php';
?>
