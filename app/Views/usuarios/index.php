<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1><i class="fas fa-users"></i> Usuarios del Sistema</h1>
    <a href="<?= base_url('/usuarios/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nuevo Usuario
    </a>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Fecha de registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= esc($u['username']) ?></td>
                        <td>
                            <?php if ($u['role'] == 'admin'): ?>
                                <span class="badge bg-danger">Administrador</span>
                            <?php else: ?>
                                <span class="badge bg-info">Vendedor</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $u['created_at'] ?></td>
                        <td>
                            <?php if ($u['id'] != session()->get('id')): ?>
                                <a href="<?= base_url('/usuarios/delete/'.$u['id']) ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('¿Eliminar este usuario?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            <?php else: ?>
                                <span class="badge bg-secondary">Usuario actual</span>
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