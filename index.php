<?php
<<<<<<< HEAD
// index.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';

$categorias = [];
$db_status = "Error al conectar.";

try {
    $db = Database::getInstance();
    $stmt = $db->query("SELECT * FROM categorias");
    $categorias = $stmt->fetchAll();
    $db_status = "Conexión a la Base de Datos exitosa.";
} catch (Exception $e) {
    $db_status = "Error: " . $e->getMessage();
}

include 'includes/header.php';
?>

<main class="content container" id="main-content">
    <section class="welcome-section text-center">
        <h1>Bienvenido a SmartSpend</h1>
        <p class="subtitle">Plataforma para el control y la gestión de gastos personales.</p>

        <!-- Botones de Acción -->
        <div class="actions-group">
            <?php if (isset($_SESSION['user_id'])): ?>
                <p class="user-greeting">Hola, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></strong></p>
                <a href="views/dashboard.php" class="btn-primary">Ir a mi Dashboard</a>
                <a href="controllers/AuthController.php?action=logout" class="btn-outline-danger">Cerrar Sesión</a>
            <?php endif; ?>
        </div>

        <!-- Estado del Sistema -->
        <div class="status-card centered-card text-left">
            <h3>Estado del Sistema</h3>
            <p><strong>Base de Datos:</strong> <?= htmlspecialchars($db_status) ?></p>
            <p><strong>Categorías Registradas:</strong> <?= count($categorias) ?></p>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
=======
/**
 * Landing — se mantiene liviano; Auth y Dashboard son de otros módulos.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/database.php';

try {
    $db = Database::getInstance();
    $categorias = $db->query('SELECT * FROM categorias')->fetchAll();
    $db_status = 'Conexión a la Base de Datos exitosa.';
} catch (Exception $e) {
    $categorias = [];
    $db_status = 'Error: ' . $e->getMessage();
}

$pageTitle = "SmartSpend - Inicio";
$basePath = '';
include __DIR__ . '/includes/header.php';
?>

<section class="welcome-section">
    <h2>Bienvenido a SmartSpend</h2>
    <p>Plataforma para el control y la gestión de gastos personales.</p>

    <div class="status-card">
        <h3>Estado del Sistema</h3>
        <p><strong>Base de Datos:</strong> <?php echo htmlspecialchars($db_status); ?></p>
        <p><strong>Categorías Registradas:</strong> <?php echo count($categorias); ?></p>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
>>>>>>> c749ca896438d59128bb5756d8d02b60d84adb63
