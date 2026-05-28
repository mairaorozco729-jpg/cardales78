<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1><i class="bi bi-receipt"></i> Detalle de Compra #<?= $compra['id'] ?></h1>
    <a href="<?= base_url('/compras') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<div class="card mb-3">
    <div class="card-header bg-info text-white">Información general</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4"><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($compra['fecha'])) ?></div>
            <div class="col-md-4"><strong>Proveedor:</strong> <?= esc($compra['proveedor']) ?></div>
            <div class="col-md-4"><strong>Total:</strong> $<?= number_format($compra['total'], 2) ?></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">Productos comprados</div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $d): ?>
                <tr>
                    <td><?= esc($d['producto']) ?></td>
                    <td><?= $d['cantidad'] ?></td>
                    <td>$<?= number_format($d['precio_unitario'], 2) ?></td>
                    <td>$<?= number_format($d['subtotal'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-dark">
                    <td colspan="3" class="text-end"><strong>Total compra</strong></td>
                    <td><strong>$<?= number_format($compra['total'], 2) ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?= $this->endSection() ?>