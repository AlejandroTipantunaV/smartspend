<?php
require_once __DIR__ . '/../includes/session.php'; require_login('auth/login.php');
$pageTitle='Configuración - SmartSpend'; include __DIR__ . '/../includes/header.php';
?>
<section class="card-panel"><h1>Configuración</h1><p>Moneda: dólares estadounidenses (USD).</p><p>Las preferencias de movimiento reducido y contraste del dispositivo se respetan automáticamente.</p><a href="profile.php">Consultar mi perfil</a></section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
