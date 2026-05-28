<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<style>
    /* =========================================
        NUEVO DISEÑO DE TARJETAS MÉTRICAS
    ========================================= */
    .metric-card {
        position: relative;
        overflow: hidden;
        border: none;
        border-radius: 1.5rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1.5rem 2.5rem rgba(0,0,0,0.12);
    }
    .metric-card .card-body {
        padding: 1.5rem;
        position: relative;
        z-index: 2;
    }
    .metric-card .metric-icon {
        position: absolute;
        right: 1rem;
        top: 1rem;
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.9;
        transition: all 0.3s ease;
    }
    .metric-card:hover .metric-icon {
        transform: scale(1.05) rotate(3deg);
    }
    .metric-card .metric-icon i {
        font-size: 1.8rem;
        color: white;
    }
    .metric-card .metric-title {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        margin-bottom: 0.5rem;
        opacity: 0.8;
    }
    .metric-card .metric-value {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 0;
    }
    .metric-card .metric-footer {
        margin-top: 0.75rem;
        font-size: 0.7rem;
        opacity: 0.7;
    }
    
    /* Colores específicos para cada tarjeta */
    .card-ventas {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
    }
    .card-compras {
        background: linear-gradient(135deg, #134e5e 0%, #71b280 100%);
        color: white;
    }
    .card-gastos {
        background: linear-gradient(135deg, #8e0e00 0%, #d34300 100%);
        color: white;
    }
    .card-ganancia {
        background: linear-gradient(135deg, #0f2027 0%, #203a43 0%, #2c5364 100%);
        color: white;
    }
    
    /* Efecto de brillo en las tarjetas */
    .card-ventas::before, .card-compras::before, .card-gastos::before, .card-ganancia::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.5s ease;
        pointer-events: none;
    }
    .card-ventas:hover::before, .card-compras:hover::before, 
    .card-gastos:hover::before, .card-ganancia:hover::before {
        opacity: 1;
    }

    /* Resto de estilos */
    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        border: none;
        border-radius: 1rem;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0,0,0,0.15);
    }
    .stock-table tbody tr:hover {
        background-color: rgba(255, 193, 7, 0.1);
        cursor: pointer;
    }
    .chart-container {
        position: relative;
        padding: 0.5rem;
    }
    .badge-stock {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
        border-radius: 0.5rem;
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .gradient-primary  { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .gradient-success  { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .gradient-danger   { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); }
    .gradient-warning  { background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); }
    .gradient-info     { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
</style>

<!-- Encabezado con bienvenida -->
<div class="d-sm-flex align-items-center justify-content-between mb-4 animate-fade-in">
    <div>
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line text-primary"></i> Dashboard
        </h1>
        <p class="text-muted mt-2">
            <i class="fas fa-calendar-alt"></i> <?= date('l, d F Y') ?> | 
            <i class="fas fa-user"></i> Bienvenido, <?= session()->get('username') ?>
        </p>
    </div>
    <div class="d-flex gap-2">
        <form method="get" action="<?= current_url() ?>" class="d-flex align-items-center gap-2 bg-white rounded shadow-sm p-1">
            <div class="bg-white border-0 rounded d-flex align-items-center">
                <i class="fas fa-calendar-alt text-primary ms-2 me-1 small"></i>
                <input type="date" name="fecha_inicio" value="<?= esc($fecha_inicio ?? '') ?>" class="border-0 form-control-sm" style="width: 130px; outline: none;">
                <span class="text-muted mx-1">-</span>
                <input type="date" name="fecha_fin" value="<?= esc($fecha_fin ?? '') ?>" class="border-0 form-control-sm" style="width: 130px; outline: none;">
            </div>
            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">
                <i class="fas fa-search"></i> Filtrar
            </button>
            <?php if ($mostrar_filtro): ?>
                <a href="<?= base_url('/dashboard') ?>" class="btn btn-sm btn-secondary rounded-pill px-3">
                    <i class="fas fa-times"></i> Limpiar
                </a>
            <?php endif; ?>
        </form>
        <span class="badge bg-gradient-primary px-3 py-2 rounded-pill shadow-sm">
            <i class="fas fa-chart-simple"></i> Hoy es <?= date('d/m/Y') ?>
        </span>
    </div>
</div>

<!-- =========================================
    NUEVO DISEÑO DE TARJETAS MÉTRICAS (SIN PORCENTAJES)
========================================= -->
<div class="row g-4 mb-4 animate-fade-in">
    
    <!-- Ventas -->
    <div class="col-xl-3 col-md-6">
        <div class="card metric-card card-ventas">
            <div class="card-body">
                <div class="metric-title">
                    <i class="fas fa-chart-line me-1"></i> <?= $mostrar_filtro ? 'Ventas en período' : 'Ventas Hoy' ?>
                </div>
                <div class="metric-value">$<?= number_format($total_ventas, 2) ?></div>
                <div class="metric-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Compras -->
    <div class="col-xl-3 col-md-6">
        <div class="card metric-card card-compras">
            <div class="card-body">
                <div class="metric-title">
                    <i class="fas fa-shopping-cart me-1"></i> <?= $mostrar_filtro ? 'Compras en período' : 'Compras Hoy' ?>
                </div>
                <div class="metric-value">$<?= number_format($total_compras, 2) ?></div>
                <div class="metric-icon">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gastos -->
    <div class="col-xl-3 col-md-6">
        <div class="card metric-card card-gastos">
            <div class="card-body">
                <div class="metric-title">
                    <i class="fas fa-coins me-1"></i> <?= $mostrar_filtro ? 'Gastos en período' : 'Gastos Hoy' ?>
                </div>
                <div class="metric-value">$<?= number_format($total_gastos, 2) ?></div>
                <div class="metric-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Ganancia Neta -->
    <div class="col-xl-3 col-md-6">
        <div class="card metric-card card-ganancia">
            <div class="card-body">
                <div class="metric-title">
                    <i class="fas fa-chart-pie me-1"></i> Ganancia Neta
                </div>
                <div class="metric-value">$<?= number_format($ganancia_neta, 2) ?></div>
                <div class="metric-footer">
                    <i class="fas fa-info-circle me-1"></i> Ventas - (Compras + Gastos)
                </div>
                <div class="metric-icon">
                    <i class="fas fa-chart-simple"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fila de accesos rápidos -->
<div class="row g-4 mb-4 animate-fade-in">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1"><i class="fas fa-chart-line"></i> Ventas acumuladas</p>
                    <h3 class="fw-bold text-success mb-0">$<?= number_format($ventas_mes ?? 0, 2) ?></h3>
                    <small>en el mes actual</small>
                </div>
                <a href="<?= base_url('/ventas') ?>" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-arrow-right"></i> Ver ventas
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted small mb-1"><i class="fas fa-boxes"></i> Inventario</p>
                    <h3 class="fw-bold text-primary mb-0"><?= $total_productos ?></h3>
                    <small>productos registrados</small>
                </div>
                <a href="<?= base_url('/productos') ?>" class="btn btn-dark rounded-pill px-4">
                    <i class="fas fa-arrow-right"></i> Ver productos
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Productos con stock bajo -->
<?php if ($productos_stock_bajo && count($productos_stock_bajo) > 0): ?>
<div class="row mb-4 animate-fade-in">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4 border-start border-warning border-4">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-warning">
                    <i class="fas fa-exclamation-triangle"></i> Alerta de inventario
                </h6>
                <span class="badge bg-warning rounded-pill px-3 py-2">
                    <i class="fas fa-box"></i> <?= $total_stock_bajo ?> productos con stock bajo
                </span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle stock-table">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Código</th>
                                <th class="text-center">Stock Actual</th>
                                <th class="text-center">Stock Mínimo</th>
                                <th class="text-center">Urgencia</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($productos_stock_bajo, 0, 5) as $p): ?>
                            <?php
                                $diferencia    = $p['stock_minimo'] - $p['stock_actual'];
                                $urgencia      = $diferencia >= 10 ? 'Alta' : ($diferencia >= 5 ? 'Media' : 'Baja');
                                $urgenciaColor = $urgencia == 'Alta' ? 'danger' : ($urgencia == 'Media' ? 'warning' : 'info');
                            ?>
                            <tr>
                                <td><strong><?= esc($p['nombre']) ?></strong><br><small class="text-muted"><?= esc($p['codigo']) ?></small></td>
                                <td><?= esc($p['codigo']) ?></td>
                                <td class="text-center"><span class="badge bg-danger rounded-pill px-3 py-2"><?= $p['stock_actual'] ?> unidades</span></td>
                                <td class="text-center"><?= $p['stock_minimo'] ?> units</span></td>
                                <td class="text-center"><span class="badge bg-<?= $urgenciaColor ?> rounded-pill"><?= $urgencia ?></span></td>
                                <td class="text-center">
                                    <a href="<?= base_url('/productos/edit/'.$p['id']) ?>" class="btn btn-sm btn-primary rounded-pill">
                                        <i class="fas fa-edit"></i> Reabastecer
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if ($total_stock_bajo > 5): ?>
                    <div class="text-center mt-3">
                        <a href="<?= base_url('/productos') ?>" class="btn btn-link">
                            Ver los <?= $total_stock_bajo - 5 ?> productos restantes <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row mb-4 animate-fade-in">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4 border-start border-success border-4 bg-success bg-opacity-10">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                <h5 class="text-success">¡Inventario saludable!</h5>
                <p class="mb-0">Todos los productos tienen stock suficiente. No hay alertas de inventario.</p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Gráfico pequeño + tabla top productos + últimas ventas -->
<div class="row g-4 mb-4 animate-fade-in">

    <!-- Gráfico de barras -->
    <div class="col-lg-5 col-md-12">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-transparent border-0">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-chart-bar"></i> Más vendidos (30 días)
                </h6>
            </div>
            <div class="card-body chart-container" style="max-height: 250px; padding: 0.75rem;">
                <?php if ($productos_vendidos && count($productos_vendidos) > 0): ?>
                    <canvas id="topProductosChart" style="max-height: 180px; width: 100%;"></canvas>
                <?php else: ?>
                    <div class="alert alert-info text-center mb-0">
                        <i class="fas fa-info-circle"></i> No hay datos suficientes.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Top productos en detalle -->
    <div class="col-lg-7 col-md-12">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-transparent border-0">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-trophy"></i> Top productos en detalle
                </h6>
            </div>
            <div class="card-body">
                <?php if ($productos_vendidos): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $rank = 1; foreach ($productos_vendidos as $p): ?>
                                <tr>
                                    <td class="text-center">
                                        <?php if ($rank == 1): ?>
                                            <span class="badge bg-warning rounded-pill">🥇</span>
                                        <?php elseif ($rank == 2): ?>
                                            <span class="badge bg-secondary rounded-pill">🥈</span>
                                        <?php elseif ($rank == 3): ?>
                                            <span class="badge bg-info rounded-pill">🥉</span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-dark">#<?= $rank ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-semibold"><?= esc($p['nombre']) ?></td>
                                    <td class="text-center"><?= $p['total_vendido'] ?> und</span></td>
                                    <td class="text-end text-success fw-bold">$<?= number_format($p['total_venta'], 2) ?></td>
                                </tr>
                                <?php $rank++; endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">No hay datos disponibles.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Últimas ventas -->
<div class="row g-4 animate-fade-in">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-clock"></i> Últimas ventas
                </h6>
                <a href="<?= base_url('/ventas') ?>" class="btn btn-sm btn-link">Ver todas <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th class="text-end">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ventas as $v): ?>
                            <tr>
                                <td><small><?= date('d/m H:i', strtotime($v['fecha'])) ?></small></td>
                                <td><?= esc($v['cliente']) ?: 'Consumidor final' ?></td>
                                <td class="text-end text-success fw-bold">$<?= number_format($v['total'], 2) ?></td>
                                <td><a href="<?= base_url('/ventas/detalle/'.$v['id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill">Ver</a></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($ventas)): ?>
                            <tr><td colspan="4" class="text-center py-4">No hay ventas recientes</span></td>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script gráfico -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    <?php if ($productos_vendidos && count($productos_vendidos) > 0): ?>
    const ctx        = document.getElementById('topProductosChart').getContext('2d');
    const nombres    = <?= $top_nombres    ?: '[]' ?>;
    const cantidades = <?= $top_cantidades ?: '[]' ?>;

    const colores = [
        'rgba(102,126,234,0.85)', 'rgba(17,153,142,0.85)', 'rgba(235,51,73,0.85)',
        'rgba(242,153,74,0.85)',  'rgba(79,172,254,0.85)', 'rgba(133,135,150,0.85)',
        'rgba(48,54,65,0.85)',    'rgba(105,52,143,0.85)', 'rgba(255,159,64,0.85)',
        'rgba(75,192,192,0.85)'
    ];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: nombres,
            datasets: [{
                label: 'Unidades vendidas',
                data: cantidades,
                backgroundColor: colores.slice(0, nombres.length),
                borderRadius: 6,
                barPercentage: 0.6,
                categoryPercentage: 0.7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.8,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { usePointStyle: true, boxWidth: 7, font: { size: 9 } }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 8,
                    bodyFont: { size: 10 },
                    callbacks: { label: (c) => `📦 ${c.raw} unidades` }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Cantidad', font: { size: 9, weight: 'bold' } },
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { font: { size: 9 } }
                },
                x: {
                    ticks: { autoSkip: false, maxRotation: 30, font: { size: 8 } },
                    grid: { display: false }
                }
            }
        }
    });
    <?php endif; ?>
});
</script>

<?= $this->endSection() ?>