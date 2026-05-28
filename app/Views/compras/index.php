<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1><i class="fas fa-shopping-cart"></i> Compras</h1>
    <?php if (session()->get('role') == 'admin'): ?>
        <a href="<?= base_url('/compras/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Compra
        </a>
    <?php endif; ?>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tablaCompras">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Total</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($compras as $c): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($c['fecha'])) ?></td>
                        <td><?= esc($c['proveedor'] ?? '—') ?></td>
                        <td>$<?= number_format($c['total'], 2) ?></td>
                        <td><?= $c['usuario_id'] ?></td>
                        <td>
                            <a href="<?= base_url('/compras/detalle/'.$c['id']) ?>" class="btn btn-sm btn-info" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if (session()->get('role') == 'admin'): ?>
                                <a href="<?= base_url('/compras/edit/'.$c['id']) ?>" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('/compras/delete/'.$c['id']) ?>" class="btn btn-sm btn-danger" 
                                   onclick="return confirm('¿Eliminar esta compra? Se revertirá el stock.')" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        if ($.fn.DataTable) {
            $('#tablaCompras').DataTable({
                language: {
                    "decimal": "",
                    "emptyTable": "No hay compras registradas",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ compras",
                    "infoEmpty": "Mostrando 0 a 0 de 0 compras",
                    "infoFiltered": "(filtrado de _MAX_ compras totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ compras por página",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron compras",
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
                order: [[0, 'desc']],
                columnDefs: [
                    { orderable: false, targets: [5] }
                ]
            });
        } else {
            console.log('DataTables no está cargado');
        }
    });
</script>
<?= $this->endSection() ?>