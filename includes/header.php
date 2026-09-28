<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'SmartSpend - Gestión de Gastos Personales'; ?></title>
    <?php $bp = isset($basePath) ? $basePath : ''; ?>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($bp); ?>assets/css/styles.css">
</head>
<body>
    <a class="skip-link" href="#contenido-principal">Saltar al contenido</a>
    <header class="main-header">
        <div class="container header-container">
            <a class="logo" href="<?php echo htmlspecialchars($bp); ?>index.php">SmartSpend</a>
            <nav class="main-nav" aria-label="Navegación principal">
                <ul>
                    <li><a href="<?php echo htmlspecialchars($bp); ?>index.php">Inicio</a></li>
                    <?php if (!empty($_SESSION['id_usuario'])): ?>
                        <li><a href="<?php echo htmlspecialchars($bp); ?>views/transactions.php">Transacciones</a></li>
                        <li>
                            <span class="nav-user" aria-label="Usuario autenticado">
                                <?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?>
                            </span>
                        </li>
                    <?php else: ?>
                        <li><a href="<?php echo htmlspecialchars($bp); ?>views/auth/login.php">Iniciar Sesión</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main id="contenido-principal" class="container content" tabindex="-1">
