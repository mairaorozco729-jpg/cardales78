<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\CategoriaModel;
use App\Models\ProveedorModel;

class Productos extends BaseController
{
    public function index()
    {
        $model = new ProductoModel();
        
        // Obtener parámetros de ordenamiento
        $order_by = $this->request->getGet('order_by') ?? 'id';
        $sort = $this->request->getGet('sort') ?? 'asc';
        
        // Columnas permitidas para ordenar
        $allowed = ['id', 'codigo', 'nombre', 'precio_venta', 'stock_actual'];
        if (!in_array($order_by, $allowed)) {
            $order_by = 'id';
        }
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'asc';
        }
        
        $productos = $model->select('productos.*, categorias.nombre as categoria, proveedores.nombre as proveedor')
                           ->join('categorias', 'categorias.id = productos.id_categoria', 'left')
                           ->join('proveedores', 'proveedores.id = productos.id_proveedor', 'left')
                           ->orderBy($order_by, $sort)
                           ->findAll();
        
        return view('productos/index', [
            'productos' => $productos,
            'order_by'  => $order_by,
            'sort'      => $sort,
        ]);
    }
    
    public function create()
    {
        $categoriaModel = new CategoriaModel();
        $proveedorModel = new ProveedorModel();
        return view('productos/form', [
            'categorias' => $categoriaModel->findAll(),
            'proveedores'=> $proveedorModel->findAll(),
        ]);
    }
    
    public function save()
    {
        $model = new ProductoModel();
        $data = [
            'codigo'        => $this->request->getPost('codigo'),
            'nombre'        => $this->request->getPost('nombre'),
            'descripcion'   => $this->request->getPost('descripcion'),
            'id_categoria'  => $this->request->getPost('id_categoria'),
            'id_proveedor'  => $this->request->getPost('id_proveedor'),
            'precio_compra' => $this->request->getPost('precio_compra'),
            'precio_venta'  => $this->request->getPost('precio_venta'),
            'stock_actual'  => $this->request->getPost('stock_actual'),
            'stock_minimo'  => $this->request->getPost('stock_minimo'),
        ];
        $model->save($data);
        return redirect()->to('/productos');
    }
    
    public function edit($id)
    {
        $model = new ProductoModel();
        $producto = $model->find($id);
        if (!$producto) {
            return redirect()->to('/productos');
        }
        $categoriaModel = new CategoriaModel();
        $proveedorModel = new ProveedorModel();
        return view('productos/form', [
            'producto'    => $producto,
            'categorias'  => $categoriaModel->findAll(),
            'proveedores' => $proveedorModel->findAll(),
        ]);
    }
    
    public function update($id)
    {
        $model = new ProductoModel();
        $data = [
            'codigo'        => $this->request->getPost('codigo'),
            'nombre'        => $this->request->getPost('nombre'),
            'descripcion'   => $this->request->getPost('descripcion'),
            'id_categoria'  => $this->request->getPost('id_categoria'),
            'id_proveedor'  => $this->request->getPost('id_proveedor'),
            'precio_compra' => $this->request->getPost('precio_compra'),
            'precio_venta'  => $this->request->getPost('precio_venta'),
            'stock_actual'  => $this->request->getPost('stock_actual'),
            'stock_minimo'  => $this->request->getPost('stock_minimo'),
        ];
        $model->update($id, $data);
        return redirect()->to('/productos');
    }
    
    public function delete($id)
    {
        $model = new ProductoModel();
        $model->delete($id);
        return redirect()->to('/productos');
    }
}