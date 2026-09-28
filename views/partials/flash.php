<?php
/**
 * Partial: mensaje flash de sesión.
 * Requiere: $flash (array|null) desde pull_flash().
 */
if (empty($flash) || !is_array($flash)) {
    return;
}

$type = $flash['type'] ?? 'success';
$message = $flash['message'] ?? '';
$allowed = ['success', 'danger'];

if (!in_array($type, $allowed, true) || $message === '') {
    return;
}
?>
<div class="alert alert-<?php echo e($type); ?>" role="alert">
    <?php echo e($message); ?>
</div>
