<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Venta #<?= $venta['id'] ?></title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 12px; }
        .meta { margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #666; padding: 6px 8px; text-align: left; }
        th { background: #f2f2f2; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin:0; font-size:20px;">Fonda Sírvalo Pues!!</h1>
        <h3 style="margin:0; font-size:14px; font-weight:400; color:#666;">Documento equivalente</h3>
        <h2 style="margin-top:8px; font-size:16px;">Venta #<?= $venta['id'] ?></h2>
        <p>Fecha: <?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?> · Generado: <?= date('d/m/Y H:i') ?></p>
    </div>

    <div class="meta">
        <strong>Cliente:</strong> <?= esc($venta['cliente']) ?: 'Consumidor final' ?>
        &nbsp;|&nbsp;
        <strong>Total:</strong> $<?= number_format($venta['total'], 2) ?>
        &nbsp;|&nbsp;
        <strong>Estado:</strong> <?= ucfirst($venta['estado_pago'] ?? '—') ?>
        &nbsp;|&nbsp;
        <strong>Método:</strong> <?= ucfirst($venta['metodo_pago'] ?? '—') ?>
    </div>

    <?php if ($mesa): ?>
        <div><strong>Mesa:</strong> <?= esc($mesa['nombre']) ?> (N° <?= esc($mesa['numero']) ?>)</div>
    <?php endif; ?>

    <hr>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio unitario</th>
                <th>Subtotal</th>
            </tr>
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
            <tr>
                <td colspan="3" class="text-right"><strong>Total</strong></td>
                <td><strong>$<?= number_format($venta['total'], 2) ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <?php if (!empty($venta['observacion'])): ?>
        <hr>
        <div><strong>Observación:</strong><br><?= nl2br(esc($venta['observacion'])) ?></div>
    <?php endif; ?>
</body>
</html>
