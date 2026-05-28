<?php

namespace App\Controllers;

use App\Models\GastoModel;

class Gastos extends BaseController
{
    public function index()
    {
        $model = new GastoModel();

        $order_by = $this->request->getGet('order_by') ?? 'fecha';
        $sort = $this->request->getGet('sort') ?? 'desc';
        $allowed = ['id', 'descripcion', 'monto', 'fecha', 'categoria', 'metodo_pago'];
        if (!in_array($order_by, $allowed)) $order_by = 'fecha';
        if (!in_array($sort, ['asc', 'desc'])) $sort = 'desc';

        $gastos = $model->orderBy($order_by, $sort)->findAll();

        return view('gastos/index', [
            'gastos'   => $gastos,
            'order_by' => $order_by,
            'sort'     => $sort,
        ]);
    }

    public function show($id)
    {
        $model = new GastoModel();
        $gasto = $model->find($id);
        if (!$gasto) return redirect()->to('/gastos');
        return view('gastos/detalle', ['gasto' => $gasto]);
    }

    public function create()
    {
        return view('gastos/form', ['gasto' => null]);
    }

    public function edit($id)
    {
        $model = new GastoModel();
        $gasto = $model->find($id);
        if (!$gasto) return redirect()->to('/gastos');
        return view('gastos/form', ['gasto' => $gasto]);
    }

    public function save()
    {
        $model = new GastoModel();
        $id = $this->request->getPost('id');

        $data = [
            'descripcion' => $this->request->getPost('descripcion'),
            'monto'       => $this->request->getPost('monto'),
            'fecha'       => $this->request->getPost('fecha') ?: date('Y-m-d H:i:s'),
            'categoria'   => $this->request->getPost('categoria'),
            'metodo_pago' => $this->request->getPost('metodo_pago'),
            'referencia'  => $this->request->getPost('referencia'),
            'usuario_id'  => session()->get('id'),
        ];

        if ($id) {
            $model->update($id, $data);
        } else {
            $model->save($data);
        }

        return redirect()->to('/gastos')->with('success', 'Gasto guardado correctamente');
    }

    public function delete($id)
    {
        $model = new GastoModel();
        $model->delete($id);
        return redirect()->to('/gastos')->with('success', 'Gasto eliminado');
    }
}