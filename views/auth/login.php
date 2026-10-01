<<<<<<< HEAD
<!-- views/auth/login.php -->
<?php 
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
    exit;
}
require_once __DIR__ . '/../../includes/header.php'; 
?>

<main class="content container" id="main-content">
    <div class="welcome-section auth-card">
        <h2 class="text-center">Iniciar Sesión</h2>

        <!-- Mensajes de Alerta -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($_GET['success']) ?>
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <form action="../../controllers/AuthController.php?action=login" method="POST">
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" required autocomplete="email">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn-primary">
                Entrar
            </button>
        </form>

        <p class="text-center auth-footer-text">
            ¿No tienes cuenta? <a href="register.php" class="auth-link">Regístrate aquí</a>
        </p>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
=======
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
>>>>>>> c749ca896438d59128bb5756d8d02b60d84adb63
