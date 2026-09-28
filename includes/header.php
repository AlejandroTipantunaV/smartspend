<?php
// Garantizar que la sesión esté iniciada para leer $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detectar automáticamente la profundidad de la carpeta actual
$current_path = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$base_path = './';

if (strpos($current_path, '/views/auth/') !== false) {
    $base_path = '../../';
} elseif (strpos($current_path, '/views/') !== false) {
    $base_path = '../';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartSpend</title>
    <!-- Ruta dinámica para vincular los estilos sin importar desde dónde se llame -->
    <link rel="stylesheet" href="<?= $base_path ?>assets/css/styles.css">
</head>
<body>
    <a href="#main-content" class="skip-link">Saltar al contenido principal</a>
    <header class="main-header">
        <div class="container header-container">
            <a href="<?= $base_path ?>index.php" class="logo">SmartSpend</a>
            <nav class="main-nav" aria-label="Navegación principal">
                <ul>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?= $base_path ?>views/dashboard.php">Panel</a></li>
                        <li><a href="<?= $base_path ?>views/auth/profile.php">Mi Perfil</a></li>
                        <li><a href="<?= $base_path ?>controllers/AuthController.php?action=logout">Cerrar Sesión</a></li>
                    <?php else: ?>
                        <li><a href="<?= $base_path ?>views/auth/login.php">Iniciar Sesión</a></li>
                        <li><a href="<?= $base_path ?>views/auth/register.php">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>