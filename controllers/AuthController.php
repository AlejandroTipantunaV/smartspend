<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$action = $_REQUEST['action'] ?? '';

if ($action === 'login') {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = 'Usuario SmartSpend';
    $_SESSION['flash_message'] = [
        'message' => '¡Bienvenido de nuevo a SmartSpend!',
        'type' => 'success'
    ];
    header("Location: ../views/dashboard.php");
    exit;
}

if ($action === 'register') {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = 'Nuevo Usuario';
    $_SESSION['flash_message'] = [
        'message' => '¡Cuenta creada exitosamente! Bienvenido a SmartSpend.',
        'type' => 'success'
    ];
    header("Location: ../views/dashboard.php");
    exit;
}

if ($action === 'logout') {
    session_destroy();
    session_start();
    $_SESSION['flash_message'] = [
        'message' => 'Has cerrado sesión exitosamente.',
        'type' => 'info'
    ];
    header("Location: ../index.php");
    exit;
}

header("Location: ../index.php");
exit;
