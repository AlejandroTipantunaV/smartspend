<?php
require_once __DIR__ . '/../includes/session.php';
$id=require_login('auth/login.php');
require_once __DIR__ . '/../models/User.php';
$user=(new User())->byId($id);
if (!$user) { $_SESSION=[]; header('Location: auth/login.php'); exit; }
$pageTitle='Mi perfil - SmartSpend'; include __DIR__ . '/../includes/header.php';
?>
<section class="card-panel"><h1>Mi perfil</h1><dl>
<dt>Nombre</dt><dd><?= e($user['nombre']) ?></dd>
<dt>Correo electrónico</dt><dd><?= e($user['correo']) ?></dd>
<dt>Miembro desde</dt><dd><?= e($user['fecha_registro']) ?></dd>
</dl><a href="transactions.php">Ver mis transacciones</a></section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
