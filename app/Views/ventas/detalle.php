<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1><i class="fas fa-receipt"></i> Detalle de Venta #<?= $venta['id'] ?></h1>
    <div class="d-flex gap-2">
        <a href="<?= base_url('/ventas') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <a href="<?= base_url('/ventas/export_pdf_one/'.$venta['id']) ?>" target="_blank" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
    </div>
</div>

<!-- Información de la venta -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-info text-white">
        <strong><i class="fas fa-info-circle"></i> Información de la venta</strong>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?>
            </div>
            <div class="col-md-3">
                <strong>Cliente:</strong> <?= esc($venta['cliente']) ?: 'Consumidor final' ?>
            </div>
            <div class="col-md-3">
                <strong>Total:</strong> $<?= number_format($venta['total'], 2) ?>
            </div>
            <div class="col-md-3">
                <strong>Estado pago:</strong>
                <?php
                $badge = '';
                switch ($venta['estado_pago']) {
                    case 'pagado': $badge = 'success'; break;
                    case 'pendiente': $badge = 'danger'; break;
                    case 'parcial': $badge = 'warning'; break;
                    case 'mixto': $badge = 'info'; break;
                    default: $badge = 'secondary';
                }
                ?>
                <span class="badge bg-<?= $badge ?>"><?= ucfirst($venta['estado_pago'] ?? 'N/A') ?></span>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-3">
                <strong>Método pago:</strong> <?= ucfirst($venta['metodo_pago'] ?? '—') ?>
            </div>
            <div class="col-md-3">
                <strong>Mesa:</strong> 
                <?php if ($mesa): ?>
                    <?= esc($mesa['nombre']) ?> (N° <?= esc($mesa['numero']) ?>)
                <?php else: ?>
                    No aplica (venta sin mesa)
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <strong>Observación:</strong> <?= nl2br(esc($venta['observacion'] ?? '')) ?>
            </div>
        </div>
    </div>
</div>

<!-- Productos vendidos -->
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <strong><i class="fas fa-box"></i> Productos vendidos</strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-end">Precio unitario</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles as $d): ?>
                        <tr>
                            <td><?= esc($d['producto']) ?></td>
                            <td class="text-center"><?= $d['cantidad'] ?></td>
                            <td class="text-end">$<?= number_format($d['precio_unitario'], 2) ?></td>
                            <td class="text-end text-success fw-bold">$<?= number_format($d['subtotal'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total venta</strong></td>
                        <td class="text-end"><strong>$<?= number_format($venta['total'], 2) ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>