<?php

namespace App\Controllers;

use App\Models\CompraModel;
use App\Models\CompraDetalleModel;
use App\Models\ProductoModel;
use App\Models\ProveedorModel;

class Compras extends BaseController
{
    public function index()
    {
        $model = new CompraModel();
        // Ordenamiento
        $order_by = $this->request->getGet('order_by') ?? 'id';
        $sort = $this->request->getGet('sort') ?? 'desc';
        $allowed = ['id', 'fecha', 'proveedor', 'total'];
        if (!in_array($order_by, $allowed)) $order_by = 'id';
        if (!in_array($sort, ['asc', 'desc'])) $sort = 'desc';

        $compras = $model->select('compras.*, proveedores.nombre as proveedor')
                         ->join('proveedores', 'proveedores.id = compras.id_proveedor', 'left')
                         ->orderBy($order_by, $sort)
                         ->findAll();

        return view('compras/index', [
            'compras' => $compras,
            'order_by' => $order_by,
            'sort' => $sort
        ]);
    }

    public function create()
    {
        $proveedorModel = new ProveedorModel();
        $productoModel = new ProductoModel();
        return view('compras/form', [
            'proveedores' => $proveedorModel->findAll(),
            'productos'   => $productoModel->findAll(),
            'action' => 'save'
        ]);
    }

    public function save()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $compraModel = new CompraModel();
        $detalleModel = new CompraDetalleModel();
        $productoModel = new ProductoModel();

        $idCompra = $compraModel->insert([
            'fecha'        => date('Y-m-d H:i:s'),
            'id_proveedor' => $this->request->getPost('id_proveedor'),
            'total'        => $this->request->getPost('total'),
            'usuario_id'   => session()->get('id'),
        ]);

        $productos_id = $this->request->getPost('producto_id');
        $cantidades   = $this->request->getPost('cantidad');
        $precios      = $this->request->getPost('precio_unitario');
        $subtotales   = $this->request->getPost('subtotal');

        if ($productos_id && is_array($productos_id)) {
            foreach ($productos_id as $i => $id_producto) {
                $detalleModel->insert([
                    'id_compra'       => $idCompra,
                    'id_producto'     => $id_producto,
                    'cantidad'        => $cantidades[$i],
                    'precio_unitario' => $precios[$i],
                    'subtotal'        => $subtotales[$i],
                ]);
                // Actualizar stock (incrementar)
                $productoModel->set('stock_actual', "stock_actual + {$cantidades[$i]}", false)
                              ->where('id', $id_producto)
                              ->update();
            }
        }

        $db->transComplete();
        session()->setFlashdata('success', 'Compra registrada correctamente');
        return redirect()->to('/compras');
    }

    public function edit($id)
    {
        $compraModel = new CompraModel();
        $detalleModel = new CompraDetalleModel();
        $proveedorModel = new ProveedorModel();
        $productoModel = new ProductoModel();

        $compra = $compraModel->find($id);
        if (!$compra) {
            return redirect()->to('/compras');
        }

        $detalles = $detalleModel->where('id_compra', $id)->findAll();
        // Obtener ids de productos para precargar select
        $productosSeleccionados = array_column($detalles, 'id_producto');

        return view('compras/form', [
            'compra' => $compra,
            'detalles' => $detalles,
            'proveedores' => $proveedorModel->findAll(),
            'productos' => $productoModel->findAll(),
            'productosSeleccionados' => $productosSeleccionados,
            'action' => 'update/' . $id
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $compraModel = new CompraModel();
        $detalleModel = new CompraDetalleModel();
        $productoModel = new ProductoModel();

        // Primero, restaurar stock de la compra original (restando lo que se había sumado)
        $detallesOriginales = $detalleModel->where('id_compra', $id)->findAll();
        foreach ($detallesOriginales as $det) {
            $productoModel->set('stock_actual', "stock_actual - {$det['cantidad']}", false)
                          ->where('id', $det['id_producto'])
                          ->update();
        }
        // Eliminar detalles originales
        $detalleModel->where('id_compra', $id)->delete();

        // Actualizar cabecera
        $compraModel->update($id, [
            'id_proveedor' => $this->request->getPost('id_proveedor'),
            'total'        => $this->request->getPost('total'),
        ]);

        // Insertar nuevos detalles y actualizar stock
        $productos_id = $this->request->getPost('producto_id');
        $cantidades   = $this->request->getPost('cantidad');
        $precios      = $this->request->getPost('precio_unitario');
        $subtotales   = $this->request->getPost('subtotal');

        if ($productos_id && is_array($productos_id)) {
            foreach ($productos_id as $i => $id_producto) {
                $detalleModel->insert([
                    'id_compra'       => $id,
                    'id_producto'     => $id_producto,
                    'cantidad'        => $cantidades[$i],
                    'precio_unitario' => $precios[$i],
                    'subtotal'        => $subtotales[$i],
                ]);
                // Sumar nuevo stock
                $productoModel->set('stock_actual', "stock_actual + {$cantidades[$i]}", false)
                              ->where('id', $id_producto)
                              ->update();
            }
        }

        $db->transComplete();
        session()->setFlashdata('success', 'Compra actualizada correctamente');
        return redirect()->to('/compras');
    }

    public function show($id)
    {
        $compraModel = new CompraModel();
        $detalleModel = new CompraDetalleModel();
        $compra = $compraModel->select('compras.*, proveedores.nombre as proveedor')
                              ->join('proveedores', 'proveedores.id = compras.id_proveedor', 'left')
                              ->where('compras.id', $id)
                              ->first();
        if (!$compra) {
            return redirect()->to('/compras');
        }
        $detalles = $detalleModel->select('compras_detalle.*, productos.nombre as producto')
                                 ->join('productos', 'productos.id = compras_detalle.id_producto')
                                 ->where('id_compra', $id)
                                 ->findAll();
        return view('compras/detalle', [
            'compra' => $compra,
            'detalles' => $detalles
        ]);
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $detalleModel = new CompraDetalleModel();
        $productoModel = new ProductoModel();
        $compraModel = new CompraModel();

        // Restaurar stock antes de eliminar
        $detalles = $detalleModel->where('id_compra', $id)->findAll();
        foreach ($detalles as $det) {
            $productoModel->set('stock_actual', "stock_actual - {$det['cantidad']}", false)
                          ->where('id', $det['id_producto'])
                          ->update();
        }
        $detalleModel->where('id_compra', $id)->delete();
        $compraModel->delete($id);

        $db->transComplete();
        session()->setFlashdata('success', 'Compra eliminada correctamente');
        return redirect()->to('/compras');
    }
}