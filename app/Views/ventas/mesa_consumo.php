<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 Bootstrap 5 theme -->
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
        border-radius: 0.5rem;
    }
    .producto-item:hover {
        background-color: #e9ecef;
        cursor: pointer;
    }
    .cantidad-control {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .cantidad-control button {
        width: 28px;
        height: 28px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1><i class="fas fa-utensils"></i> Mesa <?= esc($mesa['nombre']) ?> (N° <?= esc($mesa['numero']) ?>)</h1>
    <a href="<?= base_url('/ventas/mesas') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver a mesas
    </a>
</div>

<div class="row">
    <!-- Columna izquierda: Buscador autocompletado -->
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white py-2">
                <strong><i class="fas fa-search"></i> Agregar producto</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Buscar producto</label>
                    <select class="form-select" id="productoSelect" style="width: 100%;">
                        <option value=""></option>
                        <?php foreach ($productos as $p): ?>
                            <option value="<?= $p['id'] ?>" 
                                    data-precio="<?= $p['precio_venta'] ?>"
                                    data-nombre="<?= esc($p['nombre']) ?>"
                                    data-stock="<?= $p['stock_actual'] ?>">
                                <?= esc($p['nombre']) ?> - $<?= number_format($p['precio_venta'], 2) ?> (Stock: <?= $p['stock_actual'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cantidad</label>
                    <div class="input-group">
                        <button class="btn btn-outline-secondary" type="button" id="btnRestarCantidad">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" id="cantidadProducto" class="form-control text-center" value="1" min="1" style="max-width: 80px;">
                        <button class="btn btn-outline-secondary" type="button" id="btnSumarCantidad">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <button id="btnAgregarProducto" class="btn btn-success w-100">
                    <i class="fas fa-cart-plus"></i> Agregar a la mesa
                </button>
            </div>
        </div>
        
        <!-- Productos destacados / más vendidos -->
        <div class="card shadow-sm mt-3">
            <div class="card-header bg-secondary text-white py-2">
                <strong><i class="fas fa-fire"></i> Rápidos</strong>
            </div>
            <div class="card-body p-2">
                <div class="row g-1">
                    <?php 
                    $rapidos = array_slice($productos, 0, 8);
                    foreach ($rapidos as $p): 
                    ?>
                    <div class="col-6">
                        <button type="button" class="btn btn-sm btn-outline-primary w-100 mb-1 producto-rapido" 
                                data-id="<?= $p['id'] ?>"
                                data-nombre="<?= esc($p['nombre']) ?>"
                                data-precio="<?= $p['precio_venta'] ?>">
                            <?= esc($p['nombre']) ?>
                            <small class="d-block">$<?= number_format($p['precio_venta'], 0) ?></small>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Columna derecha: Consumo actual -->
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning py-2">
                <strong><i class="fas fa-receipt"></i> Consumo actual - Mesa <?= esc($mesa['numero']) ?></strong>
                <span class="float-end badge bg-dark">Total: $<span id="totalVentaDisplay"><?= number_format($venta['total'], 2) ?></span></span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th class="text-center" style="width: 130px;">Cantidad</th>
                                <th class="text-end">Precio</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-center" style="width: 50px;"></th>
                            </tr>
                        </thead>
                        <tbody id="detalleBody">
                            <?php if ($detalles && count($detalles) > 0): ?>
                                <?php foreach ($detalles as $d): ?>
                                    <tr id="fila-<?= $d['id'] ?>">
                                        <td><?= esc($d['producto']) ?></td>
                                        <td class="text-center">
                                            <div class="cantidad-control justify-content-center">
                                                <button type="button" class="btn btn-sm btn-outline-secondary btn-cantidad-restar" data-id="<?= $d['id'] ?>">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <span class="badge bg-secondary px-2 py-1" style="font-size: 0.9rem; min-width: 40px;" id="cantidad-<?= $d['id'] ?>"><?= $d['cantidad'] ?></span>
                                                <button type="button" class="btn btn-sm btn-outline-secondary btn-cantidad-sumar" data-id="<?= $d['id'] ?>">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-end">$<?= number_format($d['precio_unitario'], 2) ?></td>
                                        <td class="text-end fw-bold text-success" id="subtotal-<?= $d['id'] ?>">$<?= number_format($d['subtotal'], 2) ?></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-producto" data-id="<?= $d['id'] ?>" data-producto="<?= esc($d['producto']) ?>">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="sin-productos">
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle"></i> No hay productos consumidos aún.<br>
                                        Busca un producto arriba y haz clic en "Agregar a la mesa".
                                    </td>
                                </td>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <td colspan="3" class="text-end"><strong>TOTAL</strong></td>
                                <td class="text-end"><strong id="totalVenta">$<?= number_format($venta['total'], 2) ?></strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Botones de acción -->
<div class="row mt-3">
    <div class="col-12 d-flex justify-content-between">
        <a href="<?= base_url('/ventas/mesas') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Cancelar
        </a>
        <div>
            <a href="<?= base_url('/ventas/edit/'.$venta['id']) ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Editar venta completa
            </a>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalCerrarMesa">
                <i class="fas fa-lock"></i> Cerrar mesa y pagar
            </button>
        </div>
    </div>
</div>

<!-- Modal para cerrar mesa -->
<div class="modal fade" id="modalCerrarMesa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
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

<!-- jQuery, Select2, etc. -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    var ventaId = <?= $venta['id'] ?>;
    var productoSeleccionadoId = null;
    var productoSeleccionadoNombre = '';
    var productoSeleccionadoPrecio = 0;

    // Inicializar Select2
    $('#productoSelect').select2({
        theme: 'bootstrap-5',
        placeholder: '🔍 Escribe para buscar un producto...',
        allowClear: true,
        width: '100%'
    });

    // Guardar producto seleccionado
    $('#productoSelect').on('change', function() {
        var selected = $(this).find(':selected');
        if (selected.val()) {
            productoSeleccionadoId = selected.val();
            productoSeleccionadoNombre = selected.data('nombre');
            productoSeleccionadoPrecio = selected.data('precio');
        } else {
            productoSeleccionadoId = null;
        }
    });

    // Botones de cantidad
    $('#btnSumarCantidad').on('click', function() {
        var cantidad = parseInt($('#cantidadProducto').val());
        $('#cantidadProducto').val(cantidad + 1);
    });

    $('#btnRestarCantidad').on('click', function() {
        var cantidad = parseInt($('#cantidadProducto').val());
        if (cantidad > 1) {
            $('#cantidadProducto').val(cantidad - 1);
        }
    });

    // Agregar producto seleccionado
    $('#btnAgregarProducto').on('click', function() {
        if (!productoSeleccionadoId) {
            alert('Por favor, selecciona un producto de la lista');
            return;
        }
        
        var cantidad = parseInt($('#cantidadProducto').val());
        
        $.ajax({
            url: '<?= base_url('/ventas/agregar_producto_mesa') ?>',
            type: 'POST',
            data: {
                id_venta: ventaId,
                id_producto: productoSeleccionadoId,
                cantidad: cantidad
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.error || 'Error al agregar producto');
                }
            },
            error: function() {
                alert('Error de conexión');
            }
        });
    });

    // Productos rápidos
    $(document).on('click', '.producto-rapido', function() {
        var productoId = $(this).data('id');
        var cantidad = 1;
        
        $.ajax({
            url: '<?= base_url('/ventas/agregar_producto_mesa') ?>',
            type: 'POST',
            data: {
                id_venta: ventaId,
                id_producto: productoId,
                cantidad: cantidad
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.error || 'Error al agregar producto');
                }
            },
            error: function() {
                alert('Error de conexión');
            }
        });
    });

    // Ajustar cantidad en tabla
    function actualizarCantidad(idDetalle, nuevaCantidad) {
        if (nuevaCantidad < 1) {
            alert('La cantidad no puede ser menor a 1');
            return;
        }
        
        $.ajax({
            url: '<?= base_url('/ventas/editar_cantidad_mesa') ?>',
            type: 'POST',
            data: {
                id_detalle: idDetalle,
                id_venta: ventaId,
                nueva_cantidad: nuevaCantidad
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.error || 'Error al actualizar cantidad');
                }
            },
            error: function() {
                alert('Error de conexión');
            }
        });
    }

    $(document).on('click', '.btn-cantidad-sumar', function() {
        var idDetalle = $(this).data('id');
        var spanCantidad = $('#cantidad-' + idDetalle);
        var cantidadActual = parseInt(spanCantidad.text());
        actualizarCantidad(idDetalle, cantidadActual + 1);
    });

    $(document).on('click', '.btn-cantidad-restar', function() {
        var idDetalle = $(this).data('id');
        var spanCantidad = $('#cantidad-' + idDetalle);
        var cantidadActual = parseInt(spanCantidad.text());
        if (cantidadActual > 1) {
            actualizarCantidad(idDetalle, cantidadActual - 1);
        } else {
            alert('Usa el botón rojo para eliminar el producto');
        }
    });

    // Eliminar producto
    $(document).on('click', '.btn-eliminar-producto', function() {
        var idDetalle = $(this).data('id');
        var productoNombre = $(this).data('producto');
        if (confirm('¿Eliminar ' + productoNombre + ' de la cuenta?')) {
            window.location.href = '<?= base_url('/ventas/eliminar_producto_mesa') ?>/' + idDetalle;
        }
    });
</script>

<?= $this->endSection() ?>