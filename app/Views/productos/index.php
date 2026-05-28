<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1><i class="fas fa-box"></i> Productos</h1>
    <?php if (session()->get('role') == 'admin'): ?>
        <a href="<?= base_url('/productos/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Producto
        </a>
    <?php endif; ?>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tablaProductos">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Proveedor</th>
                        <th>Precio Compra</th>
                        <th>Precio Venta</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= esc($p['codigo']) ?></td>
                        <td><?= esc($p['nombre']) ?></td>
                        <td><?= esc($p['categoria']) ?></td>
                        <td><?= esc($p['proveedor']) ?></td>
                        <td class="text-end">$<?= number_format($p['precio_compra'], 2) ?></td>
                        <td class="text-end">$<?= number_format($p['precio_venta'], 2) ?></td>
                        <td class="<?= ($p['stock_actual'] <= $p['stock_minimo']) ? 'text-danger fw-bold' : '' ?>">
                            <?= $p['stock_actual'] ?>
                        </td>
                        <td><?= $p['stock_minimo'] ?></td>
                        <td class="text-center">
                            <?php if (session()->get('role') == 'admin'): ?>
                                <a href="<?= base_url('/productos/edit/'.$p['id']) ?>" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('/productos/delete/'.$p['id']) ?>" class="btn btn-sm btn-danger" 
                                   onclick="return confirm('¿Eliminar este producto?')" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            <?php else: ?>
                                <span class="badge bg-secondary">Solo lectura</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- jQuery (obligatorio para DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#tablaProductos').DataTable({
            language: {
                "decimal": "",
                "emptyTable": "No hay datos disponibles en la tabla",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ productos",
                "infoEmpty": "Mostrando 0 a 0 de 0 productos",
                "infoFiltered": "(filtrado de _MAX_ productos totales)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ productos por página",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "No se encontraron productos",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "aria": {
                    "sortAscending": ": activar para ordenar columna ascendente",
                    "sortDescending": ": activar para ordenar columna descendente"
                }
            },
            responsive: true,
            pageLength: 10,
            order: [[0, 'desc']], // Ordenar por ID descendente
            columnDefs: [
                { orderable: false, targets: [9] } // Deshabilitar orden en columna de acciones
            ]
        });
    });
</script>

<?= $this->endSection() ?>