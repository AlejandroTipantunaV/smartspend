<?php
if (!function_exists('renderAlert')) {
    function renderAlert($message, $type = 'info', $title = null, $dismissible = true) {
        $validTypes = ['success', 'danger', 'warning', 'info'];
        $type = in_array($type, $validTypes) ? $type : 'info';
        
        $defaultTitles = [
            'success' => '¡Éxito!',
            'danger'  => '¡Error!',
            'warning' => '¡Advertencia!',
            'info'    => '¡Información!'
        ];
        
        $icons = [
            'success' => 'lucide:check-circle-2',
            'danger'  => 'lucide:x-circle',
            'warning' => 'lucide:alert-triangle',
            'info'    => 'lucide:info'
        ];

        $toastTitle = $title ?? $defaultTitles[$type];
        $ariaLive = ($type === 'danger') ? 'assertive' : 'polite';
        $iconName = $icons[$type];
        
        $output = '<article class="alert-box alert-' . htmlspecialchars($type) . '" role="alert" aria-live="' . $ariaLive . '">';
        $output .= '<div class="alert-icon-container">';
        $output .= '<span class="iconify alert-icon" data-icon="' . $iconName . '" aria-hidden="true"></span>';
        $output .= '</div>';
        $output .= '<div class="alert-body">';
        $output .= '<strong class="alert-title">' . htmlspecialchars($toastTitle) . '</strong>';
        $output .= '<p class="alert-message">' . htmlspecialchars($message) . '</p>';
        $output .= '</div>';
        
        if ($dismissible) {
            $output .= '<button type="button" class="alert-close-btn" aria-label="Cerrar notificación">&times;</button>';
        }
        
        $output .= '</article>';
        return $output;
    }
}

if (!empty($_SESSION['flash_message'])) {
    $flash = $_SESSION['flash_message'];
    $message = is_array($flash) ? ($flash['message'] ?? '') : $flash;
    $type = is_array($flash) ? ($flash['type'] ?? 'info') : 'info';
    $title = is_array($flash) ? ($flash['title'] ?? null) : null;
    $position = is_array($flash) ? ($flash['position'] ?? 'top-right') : 'top-right';

    $validPositions = ['top-right', 'top-left', 'top-center', 'bottom-right', 'bottom-left', 'bottom-center'];
    $positionClass = in_array($position, $validPositions) ? 'toast-' . $position : 'toast-top-right';
    
    if (!empty($message)) {
        echo '<aside class="toast-container ' . htmlspecialchars($positionClass) . '" id="toast-container" aria-label="Notificaciones del sistema">';
        echo renderAlert($message, $type, $title);
        echo '</aside>';
    }
    
    unset($_SESSION['flash_message']);
}
?>
