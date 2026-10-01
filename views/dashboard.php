<?php
declare(strict_types=1);

require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../includes/session.php';
$idUsuario = require_login('auth/login.php');
$dashboard = (new DashboardController())->data($idUsuario);
$escape = static function ($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};
$amount = static function ($value): string {
    return $value === null ? 'No disponible' : number_format((float) $value, 2, ',', '.');
};
$ready = $dashboard['status'] === 'ready';
$maximum = max(abs((float) $dashboard['income']), abs((float) $dashboard['expenses']), 1);

$pageTitle = 'Dashboard - SmartSpend';
require __DIR__ . '/../includes/header.php';
?>
<section class="dashboard" id="dashboard-content" tabindex="-1" aria-labelledby="dashboard-title">
    <div class="dashboard-heading">
        <div><p class="dashboard-eyebrow">TU RESUMEN FINANCIERO</p>
        <h1 id="dashboard-title">Tu dinero, en perspectiva</h1>
        <p>Ingresos, gastos y movimientos de todo tu historial.</p></div>
        <span class="dashboard-period">Todos los períodos</span>
    </div>

    <?php if (!$ready): ?>
        <div class="dashboard-notice" role="status">
            <h3><?= $dashboard['status'] === 'error' ? 'Datos no disponibles' : 'Conecta tu cuenta' ?></h3>
            <p><?= $escape($dashboard['message']) ?></p>
        </div>
    <?php endif; ?>

    <div class="dashboard-metrics">
        <?php foreach ([['income', 'Ingresos totales', 'Entradas registradas'], ['expenses', 'Gastos totales', 'Salidas registradas'], ['balance', 'Balance disponible', 'Ingresos menos gastos']] as [$key, $label, $description]): ?>
            <article class="dashboard-card dashboard-metric dashboard-metric--<?= $key ?>">
                <h3><?= $label ?></h3>
                <p class="dashboard-value<?= !$ready ? ' dashboard-value--missing' : '' ?><?= $key === 'balance' && $ready ? ((float)$dashboard['balance'] < 0 ? ' balance-negative' : ' balance-positive') : '' ?>"><?= $escape($amount($dashboard[$key])) ?></p>
                <p class="dashboard-muted"><?= $description ?></p>
            </article>
        <?php endforeach; ?>
    </div>
    <p class="dashboard-unit">Importes en dólares estadounidenses (USD).</p>

    <div class="dashboard-panels">
        <section class="dashboard-card" aria-labelledby="recent-title">
            <div class="dashboard-panel-heading"><h3 id="recent-title">Movimientos recientes</h3>
                <?php if ($ready): ?><span><?= $escape($dashboard['count']) ?> en total</span><?php endif; ?>
            </div>
            <?php if (!$ready || !$dashboard['movements']): ?>
                <div class="dashboard-empty"><p class="dashboard-empty-title"><?= $ready ? 'Todavía no hay movimientos' : 'Tus movimientos aparecerán aquí' ?></p>
                <p><?= $ready ? 'Cuando registres tu primer ingreso o gasto, verás aquí tu actividad y el resumen se actualizará.' : 'El historial estará disponible cuando se puedan cargar los datos de tu cuenta.' ?></p></div>
            <?php else: ?>
                <ul class="dashboard-movements">
                <?php foreach ($dashboard['movements'] as $movement): ?>
                    <li class="dashboard-movement">
                        <div><h4><?= $escape(trim($movement['concepto']) !== '' ? $movement['concepto'] : 'Sin concepto') ?></h4>
                            <p class="dashboard-muted"><?= $escape($movement['nombre_categoria'] ?? 'Sin categoría') ?> · <time datetime="<?= $escape($movement['fecha_transaccion']) ?>"><?= $escape($movement['fecha_transaccion']) ?></time></p></div>
                        <div class="dashboard-movement-amount"><span class="dashboard-badge"><?= $movement['tipo'] === 'ingreso' ? 'Ingreso' : 'Gasto' ?></span>
                            <strong><?= $escape($amount($movement['monto'])) ?></strong></div>
                    </li>
                <?php endforeach; ?>
                </ul>
                <p class="dashboard-muted dashboard-list-note">Los últimos cinco movimientos, ordenados por fecha.</p>
            <?php endif; ?>
        </section>
        <section class="dashboard-card" aria-labelledby="comparison-title">
            <h3 id="comparison-title">Ingresos frente a gastos</h3>
            <?php if ($ready && $dashboard['count'] > 0): ?>
                <figure class="dashboard-chart" aria-labelledby="comparison-title comparison-caption">
                    <?php foreach (['income' => 'Ingresos', 'expenses' => 'Gastos'] as $key => $label): ?>
                        <div class="dashboard-chart-label"><span><?= $label ?></span><strong><?= $escape($amount($dashboard[$key])) ?></strong></div>
                        <div class="dashboard-track" aria-hidden="true"><div class="dashboard-bar dashboard-bar--<?= $key ?>" style="width: <?= number_format(abs((float) $dashboard[$key]) / $maximum * 100, 2, '.', '') ?>%"></div></div>
                    <?php endforeach; ?>
                    <figcaption id="comparison-caption">Totales de todo el historial. Las barras comparan la magnitud de los importes; los valores conservan su signo.</figcaption>
                </figure>
            <?php else: ?>
                <div class="dashboard-empty"><p class="dashboard-empty-title">Un vistazo a tu equilibrio</p><p><?= $ready ? 'La comparación aparecerá cuando tengas movimientos registrados.' : 'Necesitamos tus datos para mostrar la comparación.' ?></p></div>
            <?php endif; ?>
        </section>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
