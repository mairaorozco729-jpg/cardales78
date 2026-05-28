<?php

namespace App\Models;

use CodeIgniter\Model;

class CompraModel extends Model
{
    protected $table = 'compras';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fecha', 'id_proveedor', 'total', 'usuario_id'];
}