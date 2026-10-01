<?php
/**
 * Vista: edición de una transacción existente.
 */
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/Category.php';

$idUsuario = require_login('auth/login.php');
$id = (int) ($_GET['id'] ?? 0);

$transactionModel = new Transaction();
$categoryModel = new Category();

$transaccion = $id > 0 ? $transactionModel->getById($id, $idUsuario) : null;
$categorias = $categoryModel->getAll();
$flash = pull_flash();

$basePath = '../';
$pageTitle = 'Editar transacción - SmartSpend';
include __DIR__ . '/../includes/header.php';
?>

<section class="page-header">
    <h2>Editar transacción</h2>
    <p class="text-muted"><a href="transactions.php">← Volver al historial</a></p>
</section>

<?php include __DIR__ . '/partials/flash.php'; ?>

<?php if (!$transaccion): ?>
    <div class="alert alert-danger" role="alert">
        No se encontró la transacción o no tiene permiso para editarla.
    </div>
<?php else: ?>
    <section class="card-panel" aria-labelledby="edit-title">
        <h3 id="edit-title">Datos de la transacción</h3>
        <?php
        $formAction = $basePath . 'controllers/TransactionController.php?action=update';
        $submitLabel = 'Actualizar transacción';
        $idTransaccion = (int) $transaccion['id_transaccion'];
        $showCancel = true;
        $values = [
            'tipo' => $transaccion['tipo'],
            'id_categoria' => $transaccion['id_categoria'],
            'monto' => $transaccion['monto'],
            'concepto' => $transaccion['concepto'],
            'fecha_transaccion' => $transaccion['fecha_transaccion'],
        ];
        include __DIR__ . '/partials/transaction_form.php';
        ?>
    </section>
<?php endif; ?>

<?php
$extraScripts = ['assets/js/validation.js'];
include __DIR__ . '/../includes/footer.php';
?>
