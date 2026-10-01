<?php
/**
 * View: Edit a category.
 */
require_once __DIR__ . '/../../includes/session.php';
require_once __DIR__ . '/../../models/Category.php';

require_login('../../views/auth/login.php');

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$categoryModel = new Category();
$category = $categoryModel->getById($id);

if (!$category) {
    set_flash('danger', 'Categoría no encontrada.');
    header('Location: index.php');
    exit;
}

$flash = pull_flash();
$basePath = '../../';
$pageTitle = 'Editar Categoría - SmartSpend';
include __DIR__ . '/../../includes/header.php';
?>

<section class="page-header">
    <h1>Editar Categoría</h1>
    <p class="text-muted">Modifica los detalles de la categoría.</p>
</section>

<?php include __DIR__ . '/../partials/flash.php'; ?>

<section class="card-panel" aria-labelledby="form-title" style="max-width: 600px; margin: 0 auto;">
    <h3 id="form-title">Detalles de la Categoría</h3>
    <?php
    $formAction = $basePath . 'controllers/CategoryController.php?action=update';
    $submitLabel = 'Actualizar categoría';
    $values = $category;
    $categoryId = $id;
    $showCancel = true;
    include __DIR__ . '/_form.php';
    ?>
</section>

<?php
$extraScripts = ['assets/js/categories.js'];
include __DIR__ . '/../../includes/footer.php';
?>
