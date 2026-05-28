<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['codigo', 'nombre',
     'descripcion', 'id_categoria', 'id_proveedor', 
     'precio_compra', 'precio_venta', 'stock_actual',
      'stock_minimo'];
}