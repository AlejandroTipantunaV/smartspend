<?php
/** Global layout header. Detects $basePath, loads CSS modules with cache-busting, renders nav and opens <main>. */
require_once __DIR__ . '/session.php';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'] ?? __DIR__ . '/../index.php'));
$rootDir = str_replace('\\', '/', dirname(__DIR__));
$relative = trim(substr($scriptDir, strlen($rootDir)), '/');
$basePath = $relative === '' ? '' : str_repeat('../', count(explode('/', $relative)));
$pageTitle = $pageTitle ?? 'SmartSpend - Control y Gestión de Gastos Personales';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/dashboard.css?v=<?php echo $v; ?>">
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
