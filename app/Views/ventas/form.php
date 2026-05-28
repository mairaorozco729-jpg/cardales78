<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<h1><?= isset($venta) ? 'Editar Venta' : 'Nueva Venta' ?></h1>
<form method="post" action="<?= base_url('/ventas/save') ?>">
    <?php if (isset($venta)): ?>
        <input type="hidden" name="id_venta" value="<?= $venta['id'] ?>">
    <?php endif; ?>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Fecha</label>
                    <input type="datetime-local" name="fecha" class="form-control"
                           value="<?= isset($venta) ? date('Y-m-d\TH:i', strtotime($venta['fecha'])) : date('Y-m-d\TH:i') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Cliente</label>
                    <input type="text" name="cliente" class="form-control" value="<?= old('cliente', $venta['cliente'] ?? '') ?>">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <label class="form-label">Estado del pago</label>
                    <select name="estado_pago" class="form-select">
                        <option value="pagado" <?= (isset($venta) && $venta['estado_pago'] == 'pagado') ? 'selected' : '' ?>>Pagado</option>
                        <option value="pendiente" <?= (isset($venta) && $venta['estado_pago'] == 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
                        <option value="parcial" <?= (isset($venta) && $venta['estado_pago'] == 'parcial') ? 'selected' : '' ?>>Parcial</option>
                        
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Método de pago</label>
                    <select name="metodo_pago" class="form-select">
                        <option value="efectivo" <?= (isset($venta) && $venta['metodo_pago'] == 'efectivo') ? 'selected' : '' ?>>Efectivo</option>
                        <option value="transferencia" <?= (isset($venta) && $venta['metodo_pago'] == 'transferencia') ? 'selected' : '' ?>>Transferencia</option>
                        <option value="mixto" <?= (isset($venta) && $venta['metodo_pago'] == 'mixto') ? 'selected' : '' ?>>Mixto</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Observación</label>
                    <input type="text" name="observacion" class="form-control" value="<?= old('observacion', $venta['observacion'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-white">
            <strong><i class="bi bi-box"></i> Productos</strong>
            <button type="button" class="btn btn-sm btn-success float-end" id="addRow"><i class="bi bi-plus-circle"></i> Agregar producto</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="productosTable">
                    <thead>
                        <tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th><th>Acciones</th></tr>
                    </thead>
                    <tbody id="detalleBody">
                        <?php if (isset($detalles) && count($detalles)): ?>
                            <?php foreach ($detalles as $idx => $det): ?>
                                <tr class="fila-producto">
                                    <td>
                                        <select name="producto_id[]" class="form-select producto-select" required>
                                            <option value="">-- Seleccionar --</option>
                                            <?php foreach ($productos as $p): ?>
                                                <option value="<?= $p['id'] ?>" data-precio="<?= $p['precio_venta'] ?>" <?= ($det['id_producto'] == $p['id']) ? 'selected' : '' ?>><?= esc($p['nombre']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td><input type="number" name="cantidad[]" class="form-control cantidad" value="<?= $det['cantidad'] ?>" min="1" required></td>
                                    <td><input type="number" step="0.01" name="precio_unitario[]" class="form-control precio" value="<?= $det['precio_unitario'] ?>" readonly></td>
                                    <td><input type="number" step="0.01" name="subtotal[]" class="form-control subtotal" value="<?= $det['subtotal'] ?>" readonly></td>
                                    <td><button type="button" class="btn btn-sm btn-danger eliminar-fila"><i class="bi bi-trash"></i></button></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="fila-producto">
                                <td>
                                    <select name="producto_id[]" class="form-select producto-select" required>
                                        <option value="">-- Seleccionar --</option>
                                        <?php foreach ($productos as $p): ?>
                                            <option value="<?= $p['id'] ?>" data-precio="<?= $p['precio_venta'] ?>"><?= esc($p['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><input type="number" name="cantidad[]" class="form-control cantidad" min="1" required></td>
                                <td><input type="number" step="0.01" name="precio_unitario[]" class="form-control precio" readonly></td>
                                <td><input type="number" step="0.01" name="subtotal[]" class="form-control subtotal" readonly></td>
                                <td><button type="button" class="btn btn-sm btn-danger eliminar-fila"><i class="bi bi-trash"></i></button></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr><th colspan="3" class="text-end">Total Venta:</th><th colspan="2"><input type="text" id="totalVenta" name="total" class="form-control" readonly style="background:#e9ecef"></th></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar venta</button>
    <a href="<?= base_url('/ventas') ?>" class="btn btn-secondary">Cancelar</a>
</form>

<script>
    function recalcTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(e => {
            let val = parseFloat(e.value);
            if (!isNaN(val)) total += val;
        });
        document.getElementById('totalVenta').value = total.toFixed(2);
    }

    function recalcFila(fila) {
        let cantidad = fila.querySelector('.cantidad').value;
        let precio = fila.querySelector('.precio').value;
        let subtotal = (parseFloat(cantidad) || 0) * (parseFloat(precio) || 0);
        fila.querySelector('.subtotal').value = subtotal.toFixed(2);
        recalcTotal();
    }

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('eliminar-fila')) {
            let fila = e.target.closest('.fila-producto');
            if (document.querySelectorAll('#detalleBody .fila-producto').length > 1) {
                fila.remove();
                recalcTotal();
            } else {
                alert('Debe haber al menos un producto');
            }
        }
    });

    document.getElementById('addRow').addEventListener('click', function() {
        let tbody = document.getElementById('detalleBody');
        let newRow = tbody.children[0].cloneNode(true);
        newRow.querySelectorAll('input, select').forEach(inp => inp.value = '');
        newRow.querySelector('.cantidad').value = '';
        newRow.querySelector('.precio').value = '';
        newRow.querySelector('.subtotal').value = '';
        tbody.appendChild(newRow);
        recalcTotal();
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('producto-select')) {
            let fila = e.target.closest('.fila-producto');
            let precio = e.target.selectedOptions[0]?.getAttribute('data-precio') || 0;
            fila.querySelector('.precio').value = precio;
            recalcFila(fila);
        }
        if (e.target.classList.contains('cantidad')) {
            let fila = e.target.closest('.fila-producto');
            recalcFila(fila);
        }
    });
</script>

<?= $this->endSection() ?>