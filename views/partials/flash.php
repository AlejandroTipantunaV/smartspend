<?php
/** Renders a session flash message as a floating toast using the generic renderAlert() system. */
require_once __DIR__ . '/../../includes/alerts.php';

if (empty($flash) || !is_array($flash)) {
    return;
}

$type    = $flash['type']    ?? 'info';
$message = $flash['message'] ?? '';
$title   = $flash['title']   ?? null;

if ($message === '') {
    return;
}
?>
<aside class="toast-container toast-top-right" aria-label="Notificaciones del sistema" aria-live="polite">
    <?php echo renderAlert($message, $type, $title); ?>
</aside>
