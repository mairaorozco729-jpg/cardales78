<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3"><i class="fas fa-chair"></i> Gestión de Mesas</h1>
    <div>
        <button type="button" class="btn btn-sm btn-primary me-2" data-toggle="modal" data-target="#modalNuevaMesa">
            <i class="fas fa-plus-circle"></i> Agregar Mesa
        </button>
        <a href="<?= base_url('/ventas') ?>" class="btn btn-sm btn-secondary">
            <i class="fas fa-list"></i> Ver ventas
        </a>
    </div>
</div>

<!-- Grid compacto de mesas -->
<div class="row">
    <?php foreach ($mesas as $mesa): ?>
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6 mb-3">
            <div class="card shadow-sm h-100 <?= $mesa['estado'] == 'ocupada' ? 'border-warning border-2' : ($mesa['estado'] == 'libre' ? 'border-success border-2' : 'border-secondary') ?>" 
                 style="border-top-width: 3px;">
                
                <div class="card-header bg-white py-2 px-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="truncate">
                            <span class="fw-bold"><?= esc($mesa['nombre']) ?></span>
                            <small class="text-muted d-block">N° <?= esc($mesa['numero']) ?></small>
                        </div>
                        <div class="btn-group-vertical btn-group-sm">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-editar-mesa p-0 px-1" 
                                    data-id="<?= $mesa['id'] ?>" 
                                    data-numero="<?= esc($mesa['numero']) ?>" 
                                    data-nombre="<?= esc($mesa['nombre']) ?>"
                                    title="Editar mesa">
                                <i class="fas fa-edit fa-xs"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-mesa mt-1 p-0 px-1" 
                                    data-id="<?= $mesa['id'] ?>" 
                                    data-nombre="<?= esc($mesa['nombre']) ?>"
                                    title="Eliminar mesa">
                                <i class="fas fa-trash-alt fa-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body text-center py-2">
                    <?php if ($mesa['estado'] == 'libre'): ?>
                        <i class="fas fa-check-circle text-success" style="font-size: 1.8rem;"></i>
                        <span class="badge bg-success mt-1 d-block">Libre</span>
                    <?php elseif ($mesa['estado'] == 'ocupada'): ?>
                        <i class="fas fa-utensils text-warning" style="font-size: 1.8rem;"></i>
                        <span class="badge bg-warning mt-1 d-block">Ocupada</span>
                    <?php else: ?>
                        <i class="fas fa-lock text-secondary" style="font-size: 1.8rem;"></i>
                        <span class="badge bg-secondary mt-1 d-block">Cerrada</span>
                    <?php endif; ?>
                </div>
                
                <div class="card-footer bg-white text-center p-2">
                    <?php if ($mesa['estado'] == 'libre'): ?>
                        <a href="<?= base_url('/ventas/mesa_consumo/'.$mesa['id']) ?>" class="btn btn-sm btn-success w-100 py-1">
                            <i class="fas fa-plus"></i> Abrir
                        </a>
                    <?php elseif ($mesa['estado'] == 'ocupada'): ?>
                        <a href="<?= base_url('/ventas/mesa_consumo/'.$mesa['id']) ?>" class="btn btn-sm btn-warning w-100 py-1">
                            <i class="fas fa-cart-shopping"></i> Consumo
                        </a>
                    <?php else: ?>
                        <button class="btn btn-sm btn-secondary w-100 py-1" disabled>
                            <i class="fas fa-clock"></i> Cerrada
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal para agregar nueva mesa -->
<div class="modal fade" id="modalNuevaMesa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="post" action="<?= base_url('/ventas/agregar_mesa') ?>">
                <div class="modal-header bg-primary text-white py-2">
                    <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Nueva Mesa</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-2">
                    <div class="mb-2">
                        <label class="form-label small">Número</label>
                        <input type="text" name="numero" class="form-control form-control-sm" placeholder="Ej: 9" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Nombre / Ubicación</label>
                        <input type="text" name="nombre" class="form-control form-control-sm" placeholder="Ej: Mesa 9 - Terraza" required>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar mesa -->
<div class="modal fade" id="modalEditarMesa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="post" action="<?= base_url('/ventas/editar_mesa') ?>">
                <div class="modal-header bg-warning py-2">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Mesa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-2">
                    <input type="hidden" name="id" id="edit_mesa_id">
                    <div class="mb-2">
                        <label class="form-label small">Número</label>
                        <input type="text" name="numero" id="edit_mesa_numero" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Nombre / Ubicación</label>
                        <input type="text" name="nombre" id="edit_mesa_nombre" class="form-control form-control-sm" required>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-warning">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para eliminar mesa -->
<div class="modal fade" id="modalEliminarMesa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="post" action="<?= base_url('/ventas/eliminar_mesa') ?>">
                <div class="modal-header bg-danger text-white py-2">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Eliminar Mesa</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-2">
                    <input type="hidden" name="id" id="delete_mesa_id">
                    <p class="small mb-1">¿Eliminar la mesa <strong id="delete_mesa_nombre"></strong>?</p>
                    <p class="text-danger small mb-0">Solo mesas LIBRES pueden eliminarse</p>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Editar mesa
    $('.btn-editar-mesa').on('click', function() {
        var id = $(this).data('id');
        var numero = $(this).data('numero');
        var nombre = $(this).data('nombre');
        $('#edit_mesa_id').val(id);
        $('#edit_mesa_numero').val(numero);
        $('#edit_mesa_nombre').val(nombre);
        $('#modalEditarMesa').modal('show');
    });

    // Eliminar mesa
    $('.btn-eliminar-mesa').on('click', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        $('#delete_mesa_id').val(id);
        $('#delete_mesa_nombre').text(nombre);
        $('#modalEliminarMesa').modal('show');
    });
</script>

<?= $this->endSection() ?>