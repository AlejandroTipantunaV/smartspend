<?php
/**
 * Vista: listado, filtro y creación de ingresos/gastos.
 */
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/Category.php';

$idUsuario = require_login('auth/login.php');

$filtroTipo = $_GET['tipo'] ?? 'todos';
$tipoFiltro = in_array($filtroTipo, ['ingreso', 'gasto'], true) ? $filtroTipo : null;

$transactionModel = new Transaction();
$categoryModel = new Category();

$transacciones = $transactionModel->getByUserId($idUsuario, $tipoFiltro);
$categorias = $categoryModel->getAll();
$flash = pull_flash();

$basePath = '../';
$pageTitle = 'Transacciones - SmartSpend';
include __DIR__ . '/../includes/header.php';
?>

<section class="page-header">
    <h2>Mis Transacciones</h2>
    <p class="text-muted">Registra, filtra y administra tus ingresos y gastos.</p>
</section>

<?php include __DIR__ . '/partials/flash.php'; ?>

<section class="card-panel" aria-labelledby="form-title">
    <h3 id="form-title">Agregar transacción</h3>
    <?php
    $formAction = $basePath . 'controllers/TransactionController.php?action=store';
    $submitLabel = 'Guardar transacción';
    $values = [];
    $idTransaccion = null;
    $showCancel = false;
    include __DIR__ . '/partials/transaction_form.php';
    ?>
</section>

<section class="card-panel" aria-labelledby="historial-title">
    <div class="section-toolbar">
        <h3 id="historial-title">Historial</h3>
        <form method="GET" class="filter-bar" aria-label="Filtrar transacciones" id="filtro-transacciones">
            <label for="filtro-tipo" class="visually-hidden">Filtrar por tipo</label>
            <select id="filtro-tipo" name="tipo">
                <option value="todos" <?php echo $filtroTipo === 'todos' ? 'selected' : ''; ?>>Todos</option>
                <option value="ingreso" <?php echo $filtroTipo === 'ingreso' ? 'selected' : ''; ?>>Ingresos</option>
                <option value="gasto" <?php echo $filtroTipo === 'gasto' ? 'selected' : ''; ?>>Gastos</option>
            </select>
        </form>
    </div>

    <?php if (empty($transacciones)): ?>
        <p class="empty-state">No hay transacciones para mostrar.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="data-table" aria-describedby="historial-title">
                <thead>
                    <tr>
                        <th scope="col">Fecha</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Concepto</th>
                        <th scope="col" class="text-right">Monto</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transacciones as $t): ?>
                        <?php
                        $esIngreso = $t['tipo'] === 'ingreso';
                        $badgeClass = $esIngreso ? 'success' : 'danger';
                        $tipoLabel = $esIngreso ? 'Ingreso' : 'Gasto';
                        ?>
                        <tr>
                            <td data-label="Fecha">
                                <?php echo e(date('d/m/Y', strtotime($t['fecha_transaccion']))); ?>
                            </td>
                            <td data-label="Tipo">
                                <span class="badge badge-<?php echo e($badgeClass); ?>">
                                    <?php echo e($tipoLabel); ?>
                                </span>
                            </td>
                            <td data-label="Categoría"><?php echo e($t['nombre_categoria']); ?></td>
                            <td data-label="Concepto"><?php echo e($t['concepto']); ?></td>
                            <td data-label="Monto" class="text-right amount-<?php echo e($t['tipo']); ?>">
                                <?php echo e(format_amount((float) $t['monto'], $t['tipo'])); ?>
                            </td>
                            <td data-label="Acciones" class="actions-cell">
                                <a class="btn btn-sm btn-outline"
                                   href="edit_transaction.php?id=<?php echo (int) $t['id_transaccion']; ?>">
                                    Editar
                                </a>
                                <form method="POST"
                                      action="<?php echo e($basePath); ?>controllers/TransactionController.php?action=delete"
                                      class="inline-form js-confirm-delete"
                                      data-confirm="¿Eliminar esta transacción? Esta acción no se puede deshacer.">
                                    <input type="hidden"
                                           name="id_transaccion"
                                           value="<?php echo (int) $t['id_transaccion']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php
$extraScripts = ['assets/js/validation.js', 'assets/js/main.js'];
include __DIR__ . '/../includes/footer.php';
?>
