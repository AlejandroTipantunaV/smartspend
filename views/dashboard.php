<?php
require_once __DIR__ . '/../includes/session.php';
$idUsuario = require_login('../views/auth/login.php');

require_once __DIR__ . '/../models/Transaction.php';
$transactionModel = new Transaction();

$balanceStats = $transactionModel->getBalanceStats($idUsuario);
$catCount = $transactionModel->getUsedCategoriesCount($idUsuario);
$expenseDistribution = $transactionModel->getCategoryDistribution($idUsuario, 'gasto');
$incomeDistribution = $transactionModel->getCategoryDistribution($idUsuario, 'ingreso');

$topExpenseCat = !empty($incomeDistribution) ? $incomeDistribution[0] : null;

// Convertir a JSON para JavaScript
$expenseDistJson = json_encode($expenseDistribution);
$incomeDistJson = json_encode($incomeDistribution);
$totalExpense = $balanceStats['total_expenses'];
$totalIncome = $balanceStats['total_income'];

$basePath  = '../';
$pageTitle = 'Dashboard Financiero - SmartSpend';
include __DIR__ . '/../includes/header.php';
?>

<div class="dashboard-container">
    
    <!-- Top Header -->
    <div class="dashboard-header">
        <div class="balance-section">
            <div class="balance-header-row">
                <h3>Balance Total</h3>
            </div>
            <div class="balance-amount <?php echo $balanceStats['total_balance'] < 0 ? 'negative' : ''; ?>">
                $<?php echo number_format($balanceStats['total_balance'], 2); ?>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h4>Categorías</h4>
                    <p><?php echo $catCount; ?></p>
                </div>
                <div class="stat-icon blue">
                    <iconify-icon icon="lucide:folder-open"></iconify-icon>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h4 id="top-cat-title">Top Ingreso</h4>
                    <p id="top-cat-name"><?php echo htmlspecialchars($topExpenseCat ? $topExpenseCat['category_name'] : '-'); ?></p>
                </div>
                <div class="stat-icon orange">
                    <iconify-icon icon="lucide:trending-up"></iconify-icon>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h4>Top Monto</h4>
                    <p id="top-cat-amount">$<?php echo number_format($topExpenseCat ? $topExpenseCat['amount'] : 0, 2); ?></p>
                </div>
                <div class="stat-icon green">
                    <iconify-icon icon="lucide:dollar-sign"></iconify-icon>
                </div>
            </div>
        </div>
    </div>

    <!-- Movement View Toggle -->
    <div class="type-toggle-section">
        <h3>Vista de Movimientos</h3>
        <div class="type-toggles">
            <button class="type-btn active" id="btn-income" onclick="toggleType('ingreso')">
                <div class="type-indicator income"></div>
                <div class="type-label">
                    <strong>Ingresos</strong>
                    <span>Dinero entrante</span>
                </div>
            </button>
            <button class="type-btn" id="btn-expense" onclick="toggleType('gasto')">
                <div class="type-indicator expense"></div>
                <div class="type-label">
                    <strong>Gastos</strong>
                    <span>Dinero saliente</span>
                </div>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="dashboard-grid">
        <!-- Chart -->
        <div class="chart-card">
            <div class="card-header">
                <h3>Distribución</h3>
                <p>Porcentaje dividido por categoría para el tipo de movimiento seleccionado.</p>
            </div>
            <div id="distribution-chart"></div>
        </div>

        <!-- Category List -->
        <div class="list-card">
            <div class="card-header">
                <h3>Categorías</h3>
                <p>Ordenadas por monto para el tipo de movimiento seleccionado.</p>
            </div>
            <div class="category-list" id="category-list-container">
                <!-- Se llenará con JS -->
            </div>
        </div>
    </div>

    <!-- Trend Line Chart -->
    <div class="trend-card" style="margin-top: 2rem;">
        <div class="card-header">
            <h3>Tendencia Diaria</h3>
            <p>Transacciones diarias para el tipo de movimiento seleccionado.</p>
        </div>
        <div id="trend-chart-container" style="min-height: 400px;"></div>
    </div>

</div>

<!-- Highcharts CDN -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<!-- Módulos adicionales de Highcharts para opciones de exportación (hamburguesa) -->
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
const expenseData = <?php echo $expenseDistJson; ?>;
const incomeData = <?php echo $incomeDistJson; ?>;
const totalExpense = <?php echo $totalExpense; ?>;
const totalIncome = <?php echo $totalIncome; ?>;

const expenseTrend = <?php echo json_encode($transactionModel->getDailyTrend($idUsuario, 'gasto')); ?>;
const incomeTrend = <?php echo json_encode($transactionModel->getDailyTrend($idUsuario, 'ingreso')); ?>;

let currentType = 'ingreso';
let chartInstance = null;
let trendChartInstance = null;

// Paleta de colores para gráficos
const colors = ['#0d6efd', '#198754', '#dc3545', '#ffc107', '#0dcaf0', '#6f42c1', '#fd7e14', '#20c997'];

function toggleType(type) {
    currentType = type;
    
    // Update buttons
    document.getElementById('btn-income').classList.toggle('active', type === 'ingreso');
    document.getElementById('btn-expense').classList.toggle('active', type === 'gasto');
    
    renderDashboard();
}

function renderDashboard() {
    const data = currentType === 'gasto' ? expenseData : incomeData;
    const total = currentType === 'gasto' ? totalExpense : totalIncome;
    const trendData = currentType === 'gasto' ? expenseTrend : incomeTrend;
    const titleType = currentType === 'gasto' ? 'Gastos' : 'Ingresos';
    
    // Actualizar las tarjetas superiores (Top Categoría y Monto)
    const topCatNameEl = document.getElementById('top-cat-name');
    const topCatAmountEl = document.getElementById('top-cat-amount');
    const topCatTitleEl = document.getElementById('top-cat-title');
    
    topCatTitleEl.innerText = currentType === 'gasto' ? 'Top Gasto' : 'Top Ingreso';
    if (data && data.length > 0) {
        topCatNameEl.innerText = data[0].category_name;
        topCatAmountEl.innerText = '$' + data[0].amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    } else {
        topCatNameEl.innerText = '-';
        topCatAmountEl.innerText = '$0.00';
    }
    
    renderChart(data, total);
    renderList(data);
    renderTrendChart(trendData, titleType);
}

function renderTrendChart(trendData, titleType) {
    if (trendChartInstance) {
        trendChartInstance.destroy();
        trendChartInstance = null;
    }

    if (!trendData || !trendData.series || trendData.series.length === 0) {
        document.getElementById('trend-chart-container').innerHTML = '<div class="empty-state">No hay tendencias para mostrar.</div>';
        return;
    }

    trendChartInstance = Highcharts.chart('trend-chart-container', {
        chart: {
            type: 'line'
        },
        title: {
            text: titleType + ' por día'
        },
        xAxis: {
            categories: trendData.categories,
            labels: {
                rotation: -45,
                style: { fontSize: '10px' }
            }
        },
        yAxis: {
            title: {
                text: 'Monto ($)'
            }
        },
        tooltip: {
            shared: true,
            valuePrefix: '$'
        },
        plotOptions: {
            line: {
                marker: {
                    radius: 3
                },
                lineWidth: 2
            }
        },
        exporting: {
            enabled: true,
            buttons: {
                contextButton: {
                    menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG', 'separator', 'downloadCSV', 'downloadXLS']
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        series: trendData.series.map((s, index) => {
            s.color = s.color || colors[index % colors.length];
            return s;
        }),
        credits: { enabled: false }
    });
}

function renderChart(data, total) {
    const chartData = data.map((item, index) => ({
        name: item.category_name,
        y: item.amount,
        color: item.color || colors[index % colors.length]
    }));

    if (chartInstance) {
        chartInstance.destroy();
        chartInstance = null;
    }

    if (!data || data.length === 0) {
        document.getElementById('distribution-chart').innerHTML = '<div class="empty-state">No hay datos para mostrar.</div>';
        return;
    }

    chartInstance = Highcharts.chart('distribution-chart', {
        chart: { type: 'pie' },
        title: {
            text: 'Total<br><b>$' + Highcharts.numberFormat(total, 2) + '</b>',
            align: 'center',
            verticalAlign: 'middle',
            y: 0
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                innerSize: '70%',
                dataLabels: { 
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                },
                showInLegend: true
            }
        },
        exporting: {
            enabled: true,
            buttons: {
                contextButton: {
                    menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG', 'separator', 'downloadCSV', 'downloadXLS']
                }
            }
        },
        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'bottom'
        },
        series: [{
            name: 'Proporción',
            colorByPoint: true,
            data: chartData
        }],
        credits: { enabled: false }
    });
}

function renderList(data) {
    const container = document.getElementById('category-list-container');
    container.innerHTML = '';

    if (data.length === 0) {
        container.innerHTML = '<div class="empty-state">No hay categorías registradas.</div>';
        return;
    }

    data.forEach((item, index) => {
        const color = item.color || colors[index % colors.length];
        
        const itemHtml = `
            <div class="category-item">
                <div class="cat-item-header">
                    <div class="cat-item-info">
                        <div class="cat-icon" style="background-color: ${color}">
                            <iconify-icon icon="${item.icon}"></iconify-icon>
                        </div>
                        <div class="cat-details">
                            <strong>${item.category_name}</strong>
                            <span>${item.category_name}</span>
                        </div>
                    </div>
                    <div class="cat-amount-info">
                        <strong>$${item.amount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</strong>
                        <span>${item.percentage}%</span>
                    </div>
                </div>
                <div class="cat-progress-bar">
                    <div class="cat-progress-fill" style="width: ${item.percentage}%; background-color: ${color}"></div>
                </div>
            </div>
        `;
        container.innerHTML += itemHtml;
    });
}

// Initial render
document.addEventListener('DOMContentLoaded', () => {
    renderDashboard();
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
