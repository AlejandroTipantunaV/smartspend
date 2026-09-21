<?php
require_once 'config/database.php';

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

<section class="welcome-section">
    <h2>Bienvenido a SmartSpend</h2>
    <p>Plataforma para el control y la gestión de gastos personales.</p>
    
    <div class="status-card">
        <h3>Estado del Sistema</h3>
        <p><strong>Base de Datos:</strong> <?php echo htmlspecialchars($db_status); ?></p>
        <p><strong>Categorías Registradas:</strong> <?php echo count($categorias); ?></p>
    </div>
</section>

<?php include 'includes/footer.php'; ?>