<?php
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