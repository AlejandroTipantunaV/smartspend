<?php
require_once __DIR__ . '/../../includes/session.php';
if (!empty($_SESSION['id_usuario'])) { header('Location: ../dashboard.php'); exit; }
$old = $_SESSION['auth_old'] ?? []; unset($_SESSION['auth_old']);
$pageTitle = 'Crear cuenta - SmartSpend';
include __DIR__ . '/../../includes/header.php';
?>
<section class="auth-card-wrapper card-panel">
<h1>Crear cuenta</h1><p>Todos los campos son obligatorios.</p>
<form action="../../controllers/AuthController.php" method="post">
<?= csrf_field() ?><input type="hidden" name="action" value="register">
<div class="form-group"><label for="nombre">Nombre completo</label><input id="nombre" name="nombre" autocomplete="name" required minlength="2" maxlength="100" value="<?= e($old['nombre'] ?? '') ?>"></div>
<div class="form-group"><label for="correo">Correo electrónico</label><input type="email" id="correo" name="correo" autocomplete="email" required maxlength="150" value="<?= e($old['correo'] ?? '') ?>"></div>
<div class="form-group"><label for="password">Contraseña</label><input type="password" id="password" name="password" autocomplete="new-password" required minlength="8" maxlength="72" aria-describedby="password-help"></div>
<p id="password-help">Mínimo 8 caracteres y máximo 72 bytes.</p><div class="form-group"><label for="password_confirmation">Confirmar contraseña</label><input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required></div>
<button class="btn btn-primary" type="submit">Crear cuenta</button>
</form><p><a href="login.php">Ya tengo cuenta</a></p>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
