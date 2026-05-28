<?php

namespace App\Models;

use CodeIgniter\Model;

class MesaModel extends Model
{
    protected $table = 'mesas';
    protected $primaryKey = 'id';
    protected $allowedFields = ['numero', 'nombre', 'estado'];
    protected $useTimestamps = false;
}