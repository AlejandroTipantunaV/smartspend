<?php
$isLoggedIn = !empty($_SESSION['user_id']) || !empty($_SESSION['id_usuario']);

// Determine active menu ID
$currentId = '';
if ($basePath === '') {
    $currentId = 'home';
} elseif (strpos($_SERVER['PHP_SELF'], '/auth/') !== false) {
    if (strpos($_SERVER['PHP_SELF'], 'login.php') !== false) $currentId = 'login';
    if (strpos($_SERVER['PHP_SELF'], 'register.php') !== false) $currentId = 'register';
} elseif (strpos($_SERVER['PHP_SELF'], '/category/') !== false) {
    $currentId = 'categories';
} else {
    $page = basename($_SERVER['PHP_SELF']);
    if ($page === 'dashboard.php') $currentId = 'dashboard';
    if ($page === 'transactions.php') $currentId = 'transactions';
    if ($page === 'profile.php') $currentId = 'profile';
}
$isLoggedIn = !empty($_SESSION['user_id']) || !empty($_SESSION['id_usuario']);

$jsonPath = __DIR__ . '/../config/navigation.json';
$menuData = [];

if (file_exists($jsonPath)) {
    $jsonContent = file_get_contents($jsonPath);
    $menuData = json_decode($jsonContent, true) ?? [];
}

$allItems = $menuData['menu_items'] ?? [];

$navItems = array_filter($allItems, function($item) use ($isLoggedIn) {
    $auth = $item['auth'] ?? 'all';
    if ($auth === 'all') return true;
    if ($auth === 'user' && $isLoggedIn) return true;
    if ($auth === 'guest' && !$isLoggedIn) return true;
    return false;
});
?>

<nav class="main-navigation" aria-label="Navegación principal">
    <button class="mobile-nav-toggle" 
            type="button"
            aria-expanded="false" 
            aria-controls="primary-menu" 
            aria-label="Abrir menú de navegación">
        <span class="iconify" data-icon="lucide:menu" aria-hidden="true"></span>
    </button>

    <ul id="primary-menu" class="nav-menu">
        <?php foreach ($navItems as $item): ?>
            <?php
            $rawUrl = $item['url'];
            if ($basePath === '../' && strpos($rawUrl, 'views/') === 0) {
                $targetUrl = substr($rawUrl, 6);
            } else {
                $targetUrl = $basePath . $rawUrl;
            }
            $isActive = ($item['id'] === $currentId);
            ?>
            <li>
                <a href="<?php echo htmlspecialchars($targetUrl); ?>" 
                   class="nav-link <?php echo $isActive ? 'active' : ''; ?>"
                   <?php echo $isActive ? 'aria-current="page"' : ''; ?>>
                    <span class="iconify nav-icon" data-icon="<?php echo htmlspecialchars($item['icon']); ?>" aria-hidden="true"></span>
                    <span><?php echo htmlspecialchars($item['label']); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
