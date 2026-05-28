<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1><i class="fas fa-chart-line"></i> Gastos</h1>
    <?php if (session()->get('role') == 'admin'): ?>
        <a href="<?= base_url('/gastos/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Gasto
        </a>
    <?php endif; ?>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tablaGastos">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Monto</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($gastos as $g): ?>
                    <tr>
                        <td><?= $g['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($g['fecha'])) ?></td>
                        <td><?= esc($g['descripcion']) ?></td>
                        <td class="text-end text-danger">$<?= number_format($g['monto'], 2) ?></td>
                        <td><?= $g['usuario_id'] ?></td>
                        <td>
                            <a href="<?= base_url('/gastos/detalle/'.$g['id']) ?>" class="btn btn-sm btn-info" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if (session()->get('role') == 'admin'): ?>
                                <a href="<?= base_url('/gastos/edit/'.$g['id']) ?>" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('/gastos/delete/'.$g['id']) ?>" class="btn btn-sm btn-danger" 
                                   onclick="return confirm('¿Eliminar este gasto?')" title="Eliminar">
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
            $('#tablaGastos').DataTable({
                language: {
                    "decimal": "",
                    "emptyTable": "No hay gastos registrados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ gastos",
                    "infoEmpty": "Mostrando 0 a 0 de 0 gastos",
                    "infoFiltered": "(filtrado de _MAX_ gastos totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ gastos por página",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron gastos",
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