<?php
<<<<<<< HEAD
// Garantizar que la sesión esté iniciada para leer $_SESSION
=======
/** Global layout header. Detects $basePath, loads CSS modules with cache-busting, renders nav and opens <main>. */
>>>>>>> c749ca896438d59128bb5756d8d02b60d84adb63
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

<<<<<<< HEAD
// Detectar automáticamente la profundidad de la carpeta actual
$current_path = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$base_path = './';

if (strpos($current_path, '/views/auth/') !== false) {
    $base_path = '../../';
} elseif (strpos($current_path, '/views/') !== false) {
    $base_path = '../';
}
=======
// Detect the correct relative base path to the project root based on
// the physical location of the calling view file (root / views/ / views/auth/).
$basePath = '';
if (file_exists('assets/css/styles.css')) {
    $basePath = '';          // Called from project root (index.php)
} elseif (file_exists('../assets/css/styles.css')) {
    $basePath = '../';       // Called from views/
} elseif (file_exists('../../assets/css/styles.css')) {
    $basePath = '../../';    // Called from views/auth/
}

$pageTitle = $pageTitle ?? 'SmartSpend - Control y Gestión de Gastos Personales';
>>>>>>> c749ca896438d59128bb5756d8d02b60d84adb63
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
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
=======
    <meta name="description" content="SmartSpend - Aplicación web accesible para el control, registro y gestión de finanzas personales, ingresos y gastos.">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    
    <?php
    // Timestamp-based cache buster — forces the browser to fetch the
    // latest version of each CSS module on every page load.
    $v = time();
    ?>
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/variables.css?v=<?php echo $v; ?>">
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/accessibility.css?v=<?php echo $v; ?>">
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/layout.css?v=<?php echo $v; ?>">
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/alerts.css?v=<?php echo $v; ?>">
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/cards.css?v=<?php echo $v; ?>">
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/forms.css?v=<?php echo $v; ?>">
</head>
<body>

    <a href="#main-content" class="skip-link">Saltar al contenido principal</a>

    <header class="site-header">
        <div class="container header-wrapper">
            <a href="<?php echo $basePath; ?>index.php" class="brand-logo" aria-label="Página de inicio de SmartSpend">
                <span class="iconify brand-icon" data-icon="lucide:wallet" aria-hidden="true"></span>
                <span>SmartSpend</span>
            </a>

            <?php include __DIR__ . '/nav.php'; ?>
        </div>
    </header>

    <main id="main-content" class="site-main container" tabindex="-1">
        
        <?php include __DIR__ . '/alerts.php'; ?>
>>>>>>> c749ca896438d59128bb5756d8d02b60d84adb63
