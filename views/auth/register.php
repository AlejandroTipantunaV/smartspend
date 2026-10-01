<!-- views/auth/register.php -->
<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
    exit;
}
include_once __DIR__ . '/../../includes/header.php'; 
?>

<main class="content container" id="main-content">
    <div class="welcome-section auth-card">
        <h2 class="text-center" style="color: var(--primary-color); margin-bottom: 1.5rem;">Regístrate</h2>

        <!-- Mensajes de Alerta -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <form action="../../controllers/AuthController.php?action=register" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required autocomplete="name">
            </div>

            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" required autocomplete="email">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmar Contraseña</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn-primary">
                Crear Cuenta
            </button>
</form>

        <p class="text-center" style="margin-top: 1.5rem; color: var(--text-muted);">
            ¿Ya tienes cuenta? <a href="login.php" class="auth-link">Inicia Sesión</a>
        </p>
    </div>
</main>

<?php include_once __DIR__ . '/../../includes/footer.php'; ?>