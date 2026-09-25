<?php
/**
 * Helpers de sesión (no es Auth).
 * AuthController (Jonathan) debe setear $_SESSION['id_usuario'] al hacer login.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('require_login')) {
    /** Exige sesión y devuelve el id del usuario. */
    function require_login(string $redirectTo = '../views/auth/login.php'): int
    {
        if (empty($_SESSION['id_usuario'])) {
            header('Location: ' . $redirectTo);
            exit;
        }

        return (int) $_SESSION['id_usuario'];
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
