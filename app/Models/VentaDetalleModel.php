<?php

namespace App\Models;

use CodeIgniter\Model;

class VentaDetalleModel extends Model
{
    protected $table = 'ventas_detalle';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_venta', 'id_producto', 'cantidad', 'precio_unitario', 'subtotal'];
}