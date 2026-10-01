<?php
require_once __DIR__ . '/../../includes/session.php';
if (!empty($_SESSION['id_usuario'])) { header('Location: ../dashboard.php'); exit; }
$old = $_SESSION['auth_old'] ?? []; unset($_SESSION['auth_old']);
$pageTitle = 'Iniciar sesión - SmartSpend';
include __DIR__ . '/../../includes/header.php';
?>
<section class="auth-card-wrapper card-panel">
<h1>Iniciar sesión</h1><p>Todos los campos son obligatorios.</p>
<form action="../../controllers/AuthController.php" method="post">
<?= csrf_field() ?><input type="hidden" name="action" value="login">

<div class="form-group"><label for="correo">Correo electrónico</label><input type="email" id="correo" name="correo" autocomplete="email" required maxlength="150" value="<?= e($old['correo'] ?? '') ?>"></div>
<div class="form-group"><label for="password">Contraseña</label><input type="password" id="password" name="password" autocomplete="current-password" required ></div>

<button class="btn btn-primary" type="submit">Iniciar sesión</button>
</form><p><a href="register.php">Crear una cuenta</a></p>
</section>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
