<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<h1><?= isset($gasto) ? 'Editar Gasto' : 'Nuevo Gasto' ?></h1>
<form method="post" action="<?= base_url('/gastos/save') ?>">
    <?php if (isset($gasto)): ?>
        <input type="hidden" name="id" value="<?= $gasto['id'] ?>">
    <?php endif; ?>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="descripcion" class="form-control" value="<?= old('descripcion', $gasto['descripcion'] ?? '') ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Monto</label>
                    <input type="number" step="0.01" name="monto" class="form-control" value="<?= old('monto', $gasto['monto'] ?? 0) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha</label>
                    <input type="datetime-local" name="fecha" class="form-control" value="<?= isset($gasto) ? date('Y-m-d\TH:i', strtotime($gasto['fecha'])) : date('Y-m-d\TH:i') ?>" required>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <label class="form-label">Categoría</label>
                    <select name="categoria" class="form-select">
                        <option value="alquiler" <?= (isset($gasto) && $gasto['categoria'] == 'alquiler') ? 'selected' : '' ?>>Alquiler</option>
                        <option value="servicios" <?= (isset($gasto) && $gasto['categoria'] == 'servicios') ? 'selected' : '' ?>>Servicios (luz, agua, internet)</option>
                        <option value="insumos" <?= (isset($gasto) && $gasto['categoria'] == 'insumos') ? 'selected' : '' ?>>Insumos</option>
                        <option value="otros" <?= (isset($gasto) && $gasto['categoria'] == 'otros') ? 'selected' : '' ?>>Otros</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Método de pago</label>
                    <select name="metodo_pago" class="form-select">
                        <option value="efectivo" <?= (isset($gasto) && $gasto['metodo_pago'] == 'efectivo') ? 'selected' : '' ?>>Efectivo</option>
                        <option value="transferencia" <?= (isset($gasto) && $gasto['metodo_pago'] == 'transferencia') ? 'selected' : '' ?>>Transferencia</option>
                        <option value="mixto" <?= (isset($gasto) && $gasto['metodo_pago'] == 'mixto') ? 'selected' : '' ?>>Mixto</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Referencia / Observación</label>
                    <input type="text" name="referencia" class="form-control" value="<?= old('referencia', $gasto['referencia'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar gasto</button>
    <a href="<?= base_url('/gastos') ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection() ?>