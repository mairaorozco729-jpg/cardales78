<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Listado de Ventas</title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #666; padding: 6px 8px; text-align: left; }
        th { background: #f2f2f2; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Listado de Ventas</h2>
        <p>Generado: <?= date('d/m/Y H:i') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Método</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($ventas) && count($ventas) > 0): ?>
                <?php foreach ($ventas as $v): ?>
                    <tr>
                        <td><?= $v['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                        <td><?= esc($v['cliente']) ?: 'Consumidor final' ?></td>
                        <td class="text-right">$<?= number_format($v['total'], 2) ?></td>
                        <td><?= ucfirst($v['estado_pago'] ?? '—') ?></td>
                        <td><?= ucfirst($v['metodo_pago'] ?? '—') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No hay ventas registradas</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
