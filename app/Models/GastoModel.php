<?php

namespace App\Models;

use CodeIgniter\Model;

class GastoModel extends Model
{
    protected $table = 'gastos';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'descripcion', 'monto', 'fecha', 'usuario_id',
        'categoria', 'metodo_pago', 'referencia'
    ];
    protected $useTimestamps = false;
}