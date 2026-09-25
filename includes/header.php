<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$basePath = '';
if (file_exists('assets/css/styles.css')) {
    $basePath = '';
} elseif (file_exists('../assets/css/styles.css')) {
    $basePath = '../';
} elseif (file_exists('../../assets/css/styles.css')) {
    $basePath = '../../';
}

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
    
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/styles.css?v=<?php echo time(); ?>">
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