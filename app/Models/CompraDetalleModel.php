<?php

namespace App\Models;

use CodeIgniter\Model;

class CompraDetalleModel extends Model
{
    protected $table = 'compras_detalle';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_compra', 'id_producto', 'cantidad', 'precio_unitario', 'subtotal'];
}