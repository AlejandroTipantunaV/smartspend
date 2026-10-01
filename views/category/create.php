<?php
/**
 * View: Create a new category.
 */
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../models/Category.php';

require_login('../../views/auth/login.php');

$flash = pull_flash();
$basePath = '../../';
$pageTitle = 'Nueva Categoría - SmartSpend';
include __DIR__ . '/../../includes/header.php';
?>

<section class="page-header">
    <h1>Nueva Categoría</h1>
    <p class="text-muted">Crea una nueva categoría para organizar tus finanzas.</p>
</section>

<?php include __DIR__ . '/../partials/flash.php'; ?>

<section class="card-panel" aria-labelledby="form-title" style="max-width: 600px; margin: 0 auto;">
    <h3 id="form-title">Detalles de la Categoría</h3>
    <?php
    $formAction = $basePath . 'controllers/CategoryController.php?action=store';
    $submitLabel = 'Guardar categoría';
    $values = [];
    $categoryId = null;
    $showCancel = true;
    include __DIR__ . '/_form.php';
    ?>
</section>

<?php
$extraScripts = ['assets/js/categories.js'];
include __DIR__ . '/../../includes/footer.php';
?>
