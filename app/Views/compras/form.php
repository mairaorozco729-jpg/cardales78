<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h3><?= isset($compra) ? 'Editar Compra' : 'Nueva Compra' ?></h3>
    </div>
    <div class="card-body">
        <form method="post" action="<?= base_url('/compras/'.$action) ?>" id="compraForm">
            <div class="mb-3">
                <label>Proveedor</label>
                <select name="id_proveedor" class="form-control" required>
                    <option value="">-- Seleccione --</option>
                    <?php foreach ($proveedores as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= (isset($compra) && $compra['id_proveedor'] == $p['id']) ? 'selected' : '' ?>>
                            <?= esc($p['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Detalle de productos</label>
                <table class="table table-bordered" id="detalleTable">
                    <thead>
                        <tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th><th></th></tr>
                    </thead>
                    <tbody id="detalleBody">
                        <?php if (isset($detalles) && $detalles): ?>
                            <?php foreach ($detalles as $i => $det): ?>
                            <tr>
                                <td>
                                    <select name="producto_id[]" class="form-control producto-select" required>
                                        <option value="">-- Producto --</option>
                                        <?php foreach ($productos as $prod): ?>
                                        <option value="<?= $prod['id'] ?>" data-precio="<?= $prod['precio_compra'] ?>" <?= ($det['id_producto'] == $prod['id']) ? 'selected' : '' ?>>
                                            <?= esc($prod['nombre']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><input type="number" name="cantidad[]" class="form-control cantidad" value="<?= $det['cantidad'] ?>" required></td>
                                <td><input type="number" step="0.01" name="precio_unitario[]" class="form-control precio" value="<?= $det['precio_unitario'] ?>" required></td>
                                <td><input type="number" step="0.01" name="subtotal[]" class="form-control subtotal" readonly value="<?= $det['subtotal'] ?>"></td>
                                <td><button type="button" class="btn btn-danger btn-sm eliminar-fila">Eliminar</button></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td>
                                    <select name="producto_id[]" class="form-control producto-select" required>
                                        <option value="">-- Producto --</option>
                                        <?php foreach ($productos as $prod): ?>
                                        <option value="<?= $prod['id'] ?>" data-precio="<?= $prod['precio_compra'] ?>"><?= esc($prod['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><input type="number" name="cantidad[]" class="form-control cantidad" required></td>
                                <td><input type="number" step="0.01" name="precio_unitario[]" class="form-control precio" required></td>
                                <td><input type="number" step="0.01" name="subtotal[]" class="form-control subtotal" readonly></td>
                                <td><button type="button" class="btn btn-danger btn-sm eliminar-fila">Eliminar</button></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total Compra</strong></td>
                            <td><input type="number" step="0.01" name="total" id="total" class="form-control" readonly required></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                <button type="button" id="agregarFila" class="btn btn-secondary mt-2">Agregar otro producto</button>
            </div>

            <button type="submit" class="btn btn-success">Guardar Compra</button>
            <a href="<?= base_url('/compras') ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<script>
    function calcularFila(fila) {
        let cantidad = parseFloat(fila.querySelector('.cantidad').value) || 0;
        let precio = parseFloat(fila.querySelector('.precio').value) || 0;
        let subtotal = cantidad * precio;
        fila.querySelector('.subtotal').value = subtotal.toFixed(2);
        calcularTotal();
    }

    function calcularTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(function(input) {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('total').value = total.toFixed(2);
    }

    // Evento al cambiar cantidad o precio
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('cantidad') || e.target.classList.contains('precio')) {
            let fila = e.target.closest('tr');
            calcularFila(fila);
        }
    });

    // Cargar precio de compra al seleccionar producto
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('producto-select')) {
            let selectedOption = e.target.options[e.target.selectedIndex];
            let precio = selectedOption.getAttribute('data-precio');
            let fila = e.target.closest('tr');
            let precioInput = fila.querySelector('.precio');
            if (precio) {
                precioInput.value = parseFloat(precio).toFixed(2);
                calcularFila(fila);
            }
        }
    });

    // Agregar nueva fila
    document.getElementById('agregarFila').addEventListener('click', function() {
        let tbody = document.getElementById('detalleBody');
        let newRow = tbody.rows[0].cloneNode(true);
        newRow.querySelectorAll('input, select').forEach(input => {
            if (input.type === 'text' || input.type === 'number') input.value = '';
            if (input.tagName === 'SELECT') input.selectedIndex = 0;
        });
        tbody.appendChild(newRow);
    });

    // Eliminar fila
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('eliminar-fila')) {
            let fila = e.target.closest('tr');
            if (document.querySelectorAll('#detalleBody tr').length > 1) {
                fila.remove();
                calcularTotal();
            } else {
                alert('Debe haber al menos un producto');
            }
        }
    });
</script>
<?= $this->endSection() ?>