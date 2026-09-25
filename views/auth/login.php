<?php
$pageTitle = "Iniciar Sesión - SmartSpend";
include __DIR__ . '/../../includes/header.php';
?>

<h1>Página de Iniciar Sesión</h1>

<form action="../../controllers/AuthController.php" method="POST" style="margin-top: 1.5rem;">
    <input type="hidden" name="action" value="login">
    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
</form>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
