<?php
require_once __DIR__ . '/includes/session.php';
if (!empty($_SESSION['id_usuario'])) { header('Location: views/dashboard.php'); exit; }
$pageTitle='SmartSpend - Finanzas personales';
include __DIR__ . '/includes/header.php';
?>
<section class="welcome-section">
<h1>Tu dinero, bajo control</h1>
<p>Registra tus ingresos y gastos, organiza tus movimientos y consulta tu balance en un solo lugar.</p>
<div class="form-actions"><a class="btn btn-primary" href="views/auth/register.php">Crear cuenta</a><a class="btn btn-outline" href="views/auth/login.php">Iniciar sesión</a></div>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
