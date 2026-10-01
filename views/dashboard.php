<?php
<<<<<<< HEAD
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

include_once __DIR__ . '/../includes/header.php'; 
?>

<main class="content container" id="main-content">
    <div class="welcome-section">
        <h2 style="color: var(--primary-color);">¡Bienvenido, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>
        
        <div class="status-card">
            <h3>Estado del Módulo</h3>
            <p>Esta vista de panel principal está en desarrollo </p>
        </div>

        
    </div>
</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>
=======
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
>>>>>>> c749ca896438d59128bb5756d8d02b60d84adb63
