<?php
require_once __DIR__ . '/../includes/session.php';
$idUsuario = require_login('../views/auth/login.php');

$basePath  = '../';
$pageTitle = 'Dashboard Financiero - SmartSpend';
include __DIR__ . '/../includes/header.php';
?>

<section class="page-header">
    <h2>Dashboard</h2>
    <p class="text-muted">Resumen de tus finanzas personales.</p>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
