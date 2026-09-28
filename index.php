<?php
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
