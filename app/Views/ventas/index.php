<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1><i class="fas fa-cash-register"></i> Ventas</h1>
    <div>
        <a href="<?= base_url('/ventas/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nueva Venta
        </a>
        <a href="<?= base_url('/ventas/export_pdf') ?>" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tablaVentas">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Estado Pago</th>
                        <th>Método Pago</th>
                        <th>Mesa</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $v): ?>
                    <tr>
                        <td><?= $v['id'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                        <td><?= esc($v['cliente']) ?: 'Consumidor final' ?></td>
                        <td>$<?= number_format($v['total'], 2) ?></td>
                        <td>
                            <?php
                            $badge = '';
                            switch ($v['estado_pago']) {
                                case 'pagado': $badge = 'success'; break;
                                case 'pendiente': $badge = 'danger'; break;
                                case 'parcial': $badge = 'warning'; break;
                                case 'mixto': $badge = 'info'; break;
                                default: $badge = 'secondary';
                            }
                            ?>
                            <span class="badge bg-<?= $badge ?>"><?= ucfirst($v['estado_pago'] ?? 'N/A') ?></span>
                        </td>
                        <td><?= ucfirst($v['metodo_pago'] ?? '—') ?></td>
                        <td><?= $v['id_mesa'] ? 'Mesa ' . $v['id_mesa'] : '—' ?></td>
                        <td>
                            <a href="<?= base_url('/ventas/detalle/'.$v['id']) ?>" class="btn btn-sm btn-info" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?= base_url('/ventas/export_pdf_one/'.$v['id']) ?>" class="btn btn-sm btn-danger" title="PDF">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            <?php if (session()->get('role') == 'admin'): ?>
                                <a href="<?= base_url('/ventas/edit/'.$v['id']) ?>" class="btn btn-sm btn-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('/ventas/delete/'.$v['id']) ?>" class="btn btn-sm btn-danger" 
                                   onclick="return confirm('¿Eliminar esta venta? Se revertirá el stock.')" title="Eliminar">
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
            $('#tablaVentas').DataTable({
                language: {
                    "decimal": "",
                    "emptyTable": "No hay ventas registradas",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ ventas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 ventas",
                    "infoFiltered": "(filtrado de _MAX_ ventas totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ ventas por página",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron ventas",
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
                    { orderable: false, targets: [7] }
                ]
            });
        } else {
            console.log('DataTables no está cargado');
        }
    });
</script>
<?= $this->endSection() ?>