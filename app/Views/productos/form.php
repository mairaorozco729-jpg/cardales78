<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h3 class="mb-0"><?= isset($producto) ? 'Editar Producto' : 'Nuevo Producto' ?></h3>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= isset($producto) ? base_url('/productos/update/'.$producto['id']) : base_url('/productos/save') ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Código</label>
                        <input type="text" name="codigo" class="form-control" value="<?= old('codigo', $producto['codigo'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="<?= old('nombre', $producto['nombre'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3"><?= old('descripcion', $producto['descripcion'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="id_categoria" class="form-select">
                            <option value="">-- Sin categoría --</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= (old('id_categoria', $producto['id_categoria'] ?? '') == $cat['id']) ? 'selected' : '' ?>><?= esc($cat['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Proveedor</label>
                        <select name="id_proveedor" class="form-select">
                            <option value="">-- Sin proveedor --</option>
                            <?php foreach ($proveedores as $prov): ?>
                                <option value="<?= $prov['id'] ?>" <?= (old('id_proveedor', $producto['id_proveedor'] ?? '') == $prov['id']) ? 'selected' : '' ?>><?= esc($prov['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Precio Compra</label>
                        <input type="number" step="0.01" name="precio_compra" class="form-control" value="<?= old('precio_compra', $producto['precio_compra'] ?? 0) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Precio Venta</label>
                        <input type="number" step="0.01" name="precio_venta" class="form-control" value="<?= old('precio_venta', $producto['precio_venta'] ?? 0) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock Actual</label>
                        <input type="number" name="stock_actual" class="form-control" value="<?= old('stock_actual', $producto['stock_actual'] ?? 0) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock Mínimo</label>
                        <input type="number" name="stock_minimo" class="form-control" value="<?= old('stock_minimo', $producto['stock_minimo'] ?? 0) ?>">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Guardar</button>
                <a href="<?= base_url('/productos') ?>" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>