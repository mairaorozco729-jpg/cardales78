<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1><i class="bi bi-receipt"></i> Detalle de Gasto #<?= $gasto['id'] ?></h1>
    <a href="<?= base_url('/gastos') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <strong>Información del gasto</strong>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3"><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($gasto['fecha'])) ?></div>
            <div class="col-md-3"><strong>Descripción:</strong> <?= esc($gasto['descripcion']) ?></div>
            <div class="col-md-3"><strong>Monto:</strong> $<?= number_format($gasto['monto'], 2) ?></div>
            <div class="col-md-3"><strong>Categoría:</strong> <?= ucfirst($gasto['categoria'] ?? 'otros') ?></div>
        </div>
        <div class="row mt-2">
            <div class="col-md-3"><strong>Método pago:</strong> <?= ucfirst($gasto['metodo_pago'] ?? 'efectivo') ?></div>
            <div class="col-md-9"><strong>Referencia:</strong> <?= nl2br(esc($gasto['referencia'] ?? '')) ?></div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>