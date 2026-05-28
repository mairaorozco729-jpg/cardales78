<?php

namespace App\Models;

use CodeIgniter\Model;

class VentaModel extends Model
{
    protected $table = 'ventas';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'fecha', 'cliente', 'total', 'usuario_id',
        'estado_pago', 'metodo_pago', 'observacion', 'id_mesa'
    ];
    protected $useTimestamps = false;
}