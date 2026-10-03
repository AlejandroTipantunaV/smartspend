<?php
/**
 * Helpers de sesión (no es Auth).
 * AuthController (Jonathan) debe setear $_SESSION['id_usuario'] al hacer login.
 */

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params(['httponly'=>true, 'samesite'=>'Lax', 'secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
    session_start();
}

date_default_timezone_set('America/Guayaquil');
set_exception_handler(static function (Throwable $error): void {
    error_log('SmartSpend: ' . $error->getMessage());
    http_response_code(503);
    $pageTitle = 'Servicio temporalmente no disponible - SmartSpend';
    require __DIR__ . '/header.php';
    echo '<section class="card-panel"><h1>Servicio temporalmente no disponible</h1><p>No se pudieron cargar los datos. Inténtalo nuevamente cuando la conexión esté disponible.</p></section>';
    require __DIR__ . '/footer.php';
});

if (!function_exists('require_login')) {
    /** Exige sesión y devuelve el id del usuario. */
    function require_login(string $redirectTo = '../views/auth/login.php'): int
    {
        $userId = $_SESSION['id_usuario'] ?? $_SESSION['user_id'] ?? null;
        if (empty($userId)) {
            header('Location: ' . $redirectTo);
            exit;
        }

        return (int) $userId;
    }
}

if (!function_exists('pull_flash')) {
    /** @return array{type:string,message:string}|null */
    function pull_flash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        return is_array($flash) ? $flash : null;
    }
}

if (!function_exists('set_flash')) {
    function set_flash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message,
        ];
    }
}

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('format_amount')) {
    function format_amount(float $monto, string $tipo): string
    {
        $signo = $tipo === 'ingreso' ? '+' : '-';

        return $signo . '$' . number_format($monto, 2);
    }
}

function csrf_field(): string {
    $_SESSION['csrf_token'] = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
    return '<input type="hidden" name="csrf_token" value="'.e($_SESSION['csrf_token']).'">';
}
function verify_csrf(): void {
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { http_response_code(405); header('Allow: POST'); exit('Método no permitido.'); }
    if (!is_string($_POST['csrf_token'] ?? null) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'],$_POST['csrf_token'])) {
        http_response_code(403); exit('Solicitud no válida. Regresa al formulario y vuelve a intentarlo.');
    }
}
