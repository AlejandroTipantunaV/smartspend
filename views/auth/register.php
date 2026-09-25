<?php
$pageTitle = "Crear Cuenta - SmartSpend";
include __DIR__ . '/../../includes/header.php';
?>

<h1>Página de Registro</h1>

<form action="../../controllers/AuthController.php" method="POST" style="margin-top: 1.5rem;">
    <input type="hidden" name="action" value="register">
    <button type="submit" class="btn btn-secondary">Registrarse</button>
</form>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
