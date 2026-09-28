<?php
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