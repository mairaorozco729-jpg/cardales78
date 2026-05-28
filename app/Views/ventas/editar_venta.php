<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>
        <i class="fas <?= $venta['id_mesa'] ? 'fa-utensils' : 'fa-receipt' ?>"></i>
        <?= $venta['id_mesa'] ? 'Mesa ' . esc($mesa['nombre'] ?? '') . ' (N° ' . esc($mesa['numero'] ?? '') . ')' : 'Editar Venta #' . $venta['id'] ?>
    </h1>
    <div>
        <?php if ($venta['id_mesa']): ?>
            <a href="<?= base_url('/ventas/mesas') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a mesas
            </a>
        <?php else: ?>
            <a href="<?= base_url('/ventas') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a ventas
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Advertencia si la venta está pagada -->
<?php if (isset($advertencia) && $advertencia): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> 
        <?= $advertencia ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('/ventas/save') ?>" id="ventaForm">
    <input type="hidden" name="id_venta" value="<?= $venta['id'] ?>">
    <?php if ($venta['id_mesa']): ?>
        <input type="hidden" name="id_mesa" value="<?= $venta['id_mesa'] ?>">
    <?php endif; ?>
    
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Fecha</label>
                    <input type="datetime-local" name="fecha" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($venta['fecha'])) ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cliente</label>
                    <input type="text" name="cliente" class="form-control" value="<?= esc($venta['cliente']) ?: ($venta['id_mesa'] ? 'Mesa ' . ($mesa['numero'] ?? '') : '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Mesa</label>
                    <input type="text" class="form-control" value="<?= $venta['id_mesa'] ? esc($mesa['nombre'] ?? '') . ' (N° ' . esc($mesa['numero'] ?? '') . ')' : 'Sin mesa' ?>" readonly>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <label class="form-label">Estado pago</label>
                    <select name="estado_pago" class="form-select">
                        <option value="pendiente" <?= $venta['estado_pago'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="parcial" <?= $venta['estado_pago'] == 'parcial' ? 'selected' : '' ?>>Parcial</option>
                        <option value="pagado" <?= $venta['estado_pago'] == 'pagado' ? 'selected' : '' ?>>Pagado</option>
                        <option value="mixto" <?= $venta['estado_pago'] == 'mixto' ? 'selected' : '' ?>>Mixto</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Método pago</label>
                    <select name="metodo_pago" class="form-select">
                        <option value="">Seleccionar</option>
                        <option value="efectivo" <?= $venta['metodo_pago'] == 'efectivo' ? 'selected' : '' ?>>Efectivo</option>
                        <option value="transferencia" <?= $venta['metodo_pago'] == 'transferencia' ? 'selected' : '' ?>>Transferencia</option>
                        <option value="mixto" <?= $venta['metodo_pago'] == 'mixto' ? 'selected' : '' ?>>Mixto</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Observación</label>
                    <input type="text" name="observacion" class="form-control" value="<?= esc($venta['observacion'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-white">
            <strong><i class="fas fa-box"></i> Productos</strong>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="detallesTabla">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Producto</th>
                            <th style="width: 15%;" class="text-center">Cantidad</th>
                            <th style="width: 15%;" class="text-end">Precio Unitario</th>
                            <th style="width: 15%;" class="text-end">Subtotal</th>
                            <th style="width: 15%;" class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($detalles) && count($detalles) > 0): ?>
                            <?php foreach ($detalles as $det): ?>
                            <tr class="fila-producto">
                                <td>
                                    <select name="producto_id[]" class="form-select producto-select" required>
                                        <option value="">-- Seleccione --</option>
                                        <?php foreach ($productos as $prod): ?>
                                            <option value="<?= $prod['id'] ?>" 
                                                    data-precio="<?= $prod['precio_venta'] ?>" 
                                                    <?= ($det['id_producto'] == $prod['id']) ? 'selected' : '' ?>>
                                                <?= esc($prod['nombre']) ?> - $<?= number_format($prod['precio_venta'], 2) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <input type="number" name="cantidad[]" class="form-control cantidad text-center" value="<?= $det['cantidad'] ?>" step="1" min="1" required>
                                 </td>
                                <td class="text-end">
                                    <input type="number" step="0.01" name="precio_unitario[]" class="form-control precio text-end" value="<?= $det['precio_unitario'] ?>" required>
                                 </td>
                                <td class="text-end">
                                    <input type="number" step="0.01" name="subtotal[]" class="form-control subtotal text-end" readonly value="<?= $det['subtotal'] ?>">
                                 </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm eliminar-fila">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                 </td>
                             </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr class="fila-producto">
                                <td>
                                    <select name="producto_id[]" class="form-select producto-select" required>
                                        <option value="">-- Seleccione --</option>
                                        <?php foreach ($productos as $prod): ?>
                                            <option value="<?= $prod['id'] ?>" data-precio="<?= $prod['precio_venta'] ?>">
                                                <?= esc($prod['nombre']) ?> - $<?= number_format($prod['precio_venta'], 2) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                 </td>
                                <td class="text-center">
                                    <input type="number" name="cantidad[]" class="form-control cantidad text-center" value="1" step="1" min="1" required>
                                 </td>
                                <td class="text-end">
                                    <input type="number" step="0.01" name="precio_unitario[]" class="form-control precio text-end" value="0" required>
                                 </td>
                                <td class="text-end">
                                    <input type="number" step="0.01" name="subtotal[]" class="form-control subtotal text-end" readonly value="0">
                                 </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm eliminar-fila">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                 </td>
                             </table>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="3" class="text-end"><strong>Total venta</strong></td>
                            <td colspan="2">
                                <input type="text" id="totalVenta" name="total" class="form-control text-end fw-bold" readonly value="<?= number_format($venta['total'], 2) ?>">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-center">
                                <button type="button" id="agregarFila" class="btn btn-success">
                                    <i class="fas fa-plus-circle"></i> Agregar producto
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="<?= $venta['id_mesa'] ? base_url('/ventas/mesas') : base_url('/ventas') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Cancelar
        </a>
        <div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar cambios
            </button>
            <?php if ($venta['id_mesa'] && $venta['estado_pago'] != 'pagado'): ?>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalCerrarMesa">
                    <i class="fas fa-lock"></i> Cerrar mesa y pagar
                </button>
            <?php endif; ?>
        </div>
    </div>
</form>

<!-- Modal para cerrar mesa -->
<?php if ($venta['id_mesa'] && $venta['estado_pago'] != 'pagado'): ?>
<div class="modal fade" id="modalCerrarMesa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-lock"></i> Cerrar mesa</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="<?= base_url('/ventas/cerrar_mesa/'.$venta['id']) ?>">
                <div class="modal-body">
                    <p>¿Cerrar la mesa <strong><?= esc($mesa['nombre'] ?? '') ?> (N° <?= esc($mesa['numero'] ?? '') ?>)</strong>?</p>
                    <div class="mb-3">
                        <label class="form-label">Método de pago</label>
                        <select name="metodo_pago" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="mixto">Mixto</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Cerrar mesa</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabla = document.querySelector('#detallesTabla tbody');
    const totalInput = document.getElementById('totalVenta');

    function calcularTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(function(input) {
            let val = parseFloat(input.value) || 0;
            total += val;
        });
        totalInput.value = total.toFixed(2);
    }

    function recalcularFila(fila) {
        const cantidad = parseFloat(fila.querySelector('.cantidad').value) || 0;
        const precio = parseFloat(fila.querySelector('.precio').value) || 0;
        const subtotal = cantidad * precio;
        fila.querySelector('.subtotal').value = subtotal.toFixed(2);
        calcularTotal();
    }

    // Eventos para cambios en cantidad y precio
    tabla.addEventListener('input', function(e) {
        if (e.target.classList.contains('cantidad') || e.target.classList.contains('precio')) {
            const fila = e.target.closest('.fila-producto');
            recalcularFila(fila);
        }
    });

    // Al cambiar producto, actualizar precio sugerido
    tabla.addEventListener('change', function(e) {
        if (e.target.classList.contains('producto-select')) {
            const fila = e.target.closest('.fila-producto');
            const selectedOption = e.target.options[e.target.selectedIndex];
            const precioSugerido = selectedOption.getAttribute('data-precio');
            if (precioSugerido) {
                fila.querySelector('.precio').value = precioSugerido;
                recalcularFila(fila);
            }
        }
    });

    // Eliminar fila
    tabla.addEventListener('click', function(e) {
        if (e.target.classList.contains('eliminar-fila')) {
            if (document.querySelectorAll('.fila-producto').length > 1) {
                e.target.closest('.fila-producto').remove();
                calcularTotal();
            } else {
                alert('Debe haber al menos un producto');
            }
        }
    });

    // Agregar nueva fila
    document.getElementById('agregarFila').addEventListener('click', function() {
        // Obtener las opciones del primer select
        const primerSelect = document.querySelector('.producto-select');
        let opciones = '<option value="">-- Seleccione --</option>';
        
        if (primerSelect) {
            for (let i = 0; i < primerSelect.options.length; i++) {
                const opt = primerSelect.options[i];
                if (opt.value !== '') {
                    opciones += `<option value="${opt.value}" data-precio="${opt.getAttribute('data-precio')}">${opt.text}</option>`;
                }
            }
        }
        
        const newRow = document.createElement('tr');
        newRow.className = 'fila-producto';
        newRow.innerHTML = `
            <td>
                <select name="producto_id[]" class="form-select producto-select" required>
                    ${opciones}
                </select>
            </td>
            <td class="text-center">
                <input type="number" name="cantidad[]" class="form-control cantidad text-center" value="1" step="1" min="1" required>
            </td>
            <td class="text-end">
                <input type="number" step="0.01" name="precio_unitario[]" class="form-control precio text-end" value="0" required>
            </td>
            <td class="text-end">
                <input type="number" step="0.01" name="subtotal[]" class="form-control subtotal text-end" readonly value="0">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm eliminar-fila">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;
        tabla.appendChild(newRow);
        recalcularFila(newRow);
    });
});
</script>

<?= $this->endSection() ?>