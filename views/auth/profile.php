<!-- views/auth/profile.php -->
<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../../models/User.php';
$user = User::getById($_SESSION['user_id']);

require_once __DIR__ . '/../../includes/header.php'; 
?>

<main class="content container" id="main-content">
    <div class="welcome-section auth-card">
        <h2 class="text-center">Mi Perfil</h2>

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

        <!-- Formulario de Actualización -->
        <form action="../../controllers/AuthController.php?action=update_profile" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($user['nombre'] ?? '') ?>" required autocomplete="name">
            </div>

            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" value="<?= htmlspecialchars($user['correo'] ?? '') ?>" required autocomplete="email">
            </div>

            <hr class="form-divider">
            <p class="form-info-text">
                Si deseas cambiar la contraseña, completa los siguientes campos. De lo contrario, déjalos vacíos.
            </p>

            <div class="form-group">
                <label for="password">Nueva Contraseña (Opcional)</label>
                <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmar Nueva Contraseña</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" autocomplete="new-password">
            </div>

            <button type="submit" class="btn-primary">
                Guardar Cambios
            </button>
        </form>

        <hr class="form-divider">

        <!-- Formulario de Eliminación de Cuenta -->
        <form action="../../controllers/AuthController.php?action=delete_profile" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.');">
            <button type="submit" class="btn-outline-danger btn-full">
                Eliminar Cuenta
            </button>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>