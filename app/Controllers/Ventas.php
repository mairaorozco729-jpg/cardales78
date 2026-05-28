<?php

namespace App\Controllers;

use App\Models\VentaModel;
use App\Models\VentaDetalleModel;
use App\Models\ProductoModel;
use App\Models\MesaModel;
use Dompdf\Dompdf;

class Ventas extends BaseController
{
    // ==================== MÉTODOS PARA GESTIÓN DE MESAS ====================
    
    public function mesas()
    {
        $mesaModel = new MesaModel();
        $ventaModel = new VentaModel();
        
        $mesas = $mesaModel->findAll();
        
        // Para cada mesa ocupada, obtener la venta activa
        foreach ($mesas as &$mesa) {
            if ($mesa['estado'] == 'ocupada') {
                $venta = $ventaModel->where('id_mesa', $mesa['id'])
                                    ->where('estado_pago !=', 'pagado')
                                    ->orderBy('id', 'DESC')
                                    ->first();
                $mesa['venta_id'] = $venta['id'] ?? null;
            }
        }
        
        return view('ventas/mesas', ['mesas' => $mesas]);
    }
    
    public function mesa_consumo($id_mesa)
    {
        $mesaModel = new MesaModel();
        $ventaModel = new VentaModel();
        
        $mesa = $mesaModel->find($id_mesa);
        if (!$mesa) {
            return redirect()->to('/ventas/mesas')->with('error', 'Mesa no encontrada');
        }
        
        // Buscar venta activa para esta mesa
        $venta = $ventaModel->where('id_mesa', $id_mesa)
                            ->where('estado_pago !=', 'pagado')
                            ->orderBy('id', 'DESC')
                            ->first();
        
        // Si no existe venta activa, crear una nueva
        if (!$venta) {
            $ventaId = $ventaModel->insert([
                'fecha'        => date('Y-m-d H:i:s'),
                'cliente'      => 'Mesa ' . $mesa['numero'],
                'total'        => 0,
                'usuario_id'   => session()->get('id'),
                'estado_pago'  => 'pendiente',
                'metodo_pago'  => null,
                'observacion'  => '',
                'id_mesa'      => $id_mesa
            ]);
            $venta = $ventaModel->find($ventaId);
            // Cambiar estado de la mesa a ocupada
            $mesaModel->update($id_mesa, ['estado' => 'ocupada']);
        }
        
        // Redirigir a la vista unificada de edición de venta
        return redirect()->to('/ventas/edit/' . $venta['id']);
    }
    
    public function agregar_producto_venta()
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        $id_venta = $this->request->getPost('id_venta');
        $id_producto = $this->request->getPost('id_producto');
        $cantidad = $this->request->getPost('cantidad');
        
        $ventaModel = new VentaModel();
        $productoModel = new ProductoModel();
        $detalleModel = new VentaDetalleModel();
        
        $venta = $ventaModel->find($id_venta);
        $producto = $productoModel->find($id_producto);
        
        if (!$venta || !$producto) {
            return redirect()->back()->with('error', 'Datos inválidos');
        }
        
        if ($cantidad <= 0) {
            return redirect()->back()->with('error', 'La cantidad debe ser mayor a cero');
        }
        
        // Verificar stock suficiente
        if ($producto['stock_actual'] < $cantidad) {
            return redirect()->back()->with('error', 'Stock insuficiente para ' . $producto['nombre']);
        }
        
        $subtotal = $cantidad * $producto['precio_venta'];
        
        // Insertar detalle
        $detalleModel->insert([
            'id_venta'        => $id_venta,
            'id_producto'     => $id_producto,
            'cantidad'        => $cantidad,
            'precio_unitario' => $producto['precio_venta'],
            'subtotal'        => $subtotal
        ]);
        
        // Actualizar total de la venta
        $nuevo_total = $venta['total'] + $subtotal;
        $ventaModel->update($id_venta, ['total' => $nuevo_total]);
        
        // Descontar stock
        $productoModel->set('stock_actual', "stock_actual - {$cantidad}", false)
                      ->where('id', $id_producto)
                      ->update();
        
        $db->transComplete();
        
        return redirect()->to('/ventas/edit/' . $id_venta)
                         ->with('success', 'Producto agregado correctamente');
    }
    
    public function editar_cantidad_venta()
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        $id_detalle = $this->request->getPost('id_detalle');
        $id_venta = $this->request->getPost('id_venta');
        $nueva_cantidad = $this->request->getPost('nueva_cantidad');
        
        $detalleModel = new VentaDetalleModel();
        $ventaModel = new VentaModel();
        $productoModel = new ProductoModel();
        
        $detalle = $detalleModel->find($id_detalle);
        if (!$detalle) {
            return redirect()->back()->with('error', 'Producto no encontrado');
        }
        
        $venta = $ventaModel->find($id_venta);
        if (!$venta) {
            return redirect()->back()->with('error', 'Venta no encontrada');
        }
        
        $producto = $productoModel->find($detalle['id_producto']);
        $cantidad_actual = $detalle['cantidad'];
        $precio_unitario = $detalle['precio_unitario'];
        
        if ($nueva_cantidad < 1) {
            return redirect()->back()->with('error', 'La cantidad debe ser al menos 1');
        }
        
        // Verificar stock suficiente si la cantidad aumenta
        if ($nueva_cantidad > $cantidad_actual) {
            $diferencia = $nueva_cantidad - $cantidad_actual;
            if ($producto['stock_actual'] < $diferencia) {
                return redirect()->back()->with('error', 'Stock insuficiente para ' . $producto['nombre']);
            }
        }
        
        $diferencia_cantidad = $nueva_cantidad - $cantidad_actual;
        $diferencia_subtotal = $diferencia_cantidad * $precio_unitario;
        
        // Actualizar stock del producto
        $productoModel->set('stock_actual', "stock_actual - {$diferencia_cantidad}", false)
                      ->where('id', $detalle['id_producto'])
                      ->update();
        
        // Actualizar detalle
        $nuevo_subtotal = $nueva_cantidad * $precio_unitario;
        $detalleModel->update($id_detalle, [
            'cantidad' => $nueva_cantidad,
            'subtotal' => $nuevo_subtotal
        ]);
        
        // Actualizar total de la venta
        $nuevo_total_venta = $venta['total'] + $diferencia_subtotal;
        $ventaModel->update($id_venta, ['total' => $nuevo_total_venta]);
        
        $db->transComplete();
        
        return redirect()->to('/ventas/edit/' . $id_venta)
                         ->with('success', 'Cantidad actualizada correctamente');
    }
    
    public function eliminar_producto_venta($id_detalle)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        $detalleModel = new VentaDetalleModel();
        $ventaModel = new VentaModel();
        $productoModel = new ProductoModel();
        
        // Obtener el detalle
        $detalle = $detalleModel->find($id_detalle);
        if (!$detalle) {
            return redirect()->back()->with('error', 'Producto no encontrado');
        }
        
        // Obtener la venta asociada
        $venta = $ventaModel->find($detalle['id_venta']);
        if (!$venta) {
            return redirect()->back()->with('error', 'Venta no encontrada');
        }
        
        // Revertir stock del producto
        $productoModel->set('stock_actual', "stock_actual + {$detalle['cantidad']}", false)
                      ->where('id', $detalle['id_producto'])
                      ->update();
        
        // Restar el subtotal del total de la venta
        $nuevo_total = $venta['total'] - $detalle['subtotal'];
        $ventaModel->update($detalle['id_venta'], ['total' => $nuevo_total]);
        
        // Eliminar el detalle
        $detalleModel->delete($id_detalle);
        
        $db->transComplete();
        
        return redirect()->to('/ventas/edit/' . $detalle['id_venta'])
                         ->with('success', 'Producto eliminado correctamente');
    }
    
    public function cerrar_mesa($id_venta)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        $ventaModel = new VentaModel();
        $mesaModel = new MesaModel();
        
        $venta = $ventaModel->find($id_venta);
        if (!$venta) {
            return redirect()->to('/ventas/mesas')->with('error', 'Venta no encontrada');
        }
        
        $metodo_pago = $this->request->getPost('metodo_pago');
        if (!$metodo_pago) {
            return redirect()->back()->with('error', 'Debe seleccionar un método de pago');
        }
        
        // Marcar venta como pagada
        $ventaModel->update($id_venta, [
            'estado_pago' => 'pagado',
            'metodo_pago' => $metodo_pago
        ]);
        
        // Liberar la mesa
        if ($venta['id_mesa']) {
            $mesaModel->update($venta['id_mesa'], ['estado' => 'libre']);
        }
        
        $db->transComplete();
        
        return redirect()->to('/ventas/mesas')->with('success', 'Mesa cerrada y venta finalizada');
    }
    
    public function actualizar_venta()
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        $ventaModel = new VentaModel();
        $idVenta = $this->request->getPost('id_venta');
        
        $dataVenta = [
            'fecha'        => $this->request->getPost('fecha') ?: date('Y-m-d H:i:s'),
            'cliente'      => $this->request->getPost('cliente'),
            'estado_pago'  => $this->request->getPost('estado_pago'),
            'metodo_pago'  => $this->request->getPost('metodo_pago'),
            'observacion'  => $this->request->getPost('observacion'),
        ];
        
        $ventaModel->update($idVenta, $dataVenta);
        
        $db->transComplete();
        
        $venta = $ventaModel->find($idVenta);
        if ($venta['id_mesa']) {
            return redirect()->to('/ventas/edit/' . $idVenta)->with('success', 'Venta actualizada correctamente');
        } else {
            return redirect()->to('/ventas')->with('success', 'Venta actualizada correctamente');
        }
    }
    
    // ==================== MÉTODOS EXISTENTES ====================
    
    public function index()
    {
        $model = new VentaModel();
        $order_by = $this->request->getGet('order_by') ?? 'id';
        $sort = $this->request->getGet('sort') ?? 'desc';
        $allowed = ['id', 'fecha', 'cliente', 'total'];
        if (!in_array($order_by, $allowed)) $order_by = 'id';
        if (!in_array($sort, ['asc', 'desc'])) $sort = 'desc';
        
        $ventas = $model->orderBy($order_by, $sort)->findAll();
        
        return view('ventas/index', [
            'ventas'   => $ventas,
            'order_by' => $order_by,
            'sort'     => $sort,
        ]);
    }
    
    public function show($id)
    {
        $ventaModel = new VentaModel();
        $detalleModel = new VentaDetalleModel();
        $mesaModel = new MesaModel();
        
        $venta = $ventaModel->find($id);
        if (!$venta) return redirect()->to('/ventas');
        
        $detalles = $detalleModel->select('ventas_detalle.*, productos.nombre as producto')
                                 ->join('productos', 'productos.id = ventas_detalle.id_producto')
                                 ->where('id_venta', $id)
                                 ->findAll();
        
        $mesa = null;
        if ($venta['id_mesa']) {
            $mesa = $mesaModel->find($venta['id_mesa']);
        }
        
        return view('ventas/detalle', [
            'venta'    => $venta,
            'detalles' => $detalles,
            'mesa'     => $mesa
        ]);
    }
    
    public function create()
    {
        $productoModel = new ProductoModel();
        $productos = $productoModel->where('stock_actual >', 0)->findAll();
        
        return view('ventas/form', [
            'productos' => $productos,
            'venta'     => null,
            'detalles'  => [],
        ]);
    }
    
 public function edit($id)
{
    $ventaModel = new VentaModel();
    $detalleModel = new VentaDetalleModel();
    $productoModel = new ProductoModel();
    $mesaModel = new MesaModel();
    
    $venta = $ventaModel->find($id);
    if (!$venta) {
        return redirect()->to('/ventas')->with('error', 'Venta no encontrada');
    }
    
    // ✅ Tanto admin como vendedor pueden editar ventas pagadas
    // (Eliminamos cualquier restricción por estado_pago)
    
    $detalles = $detalleModel->select('ventas_detalle.*, productos.nombre as producto')
                             ->join('productos', 'productos.id = ventas_detalle.id_producto')
                             ->where('id_venta', $id)
                             ->findAll();
    
    $productos = $productoModel->where('stock_actual >', 0)->findAll();
    
    $mesa = null;
    if ($venta['id_mesa']) {
        $mesa = $mesaModel->find($venta['id_mesa']);
    }
    
    // Advertencia si la venta está pagada (para ambos roles)
    $advertencia = '';
    if ($venta['estado_pago'] == 'pagado') {
        $advertencia = '⚠️ ATENCIÓN: Esta venta ya estaba pagada. Al modificarla, se revertirá el stock anterior y se aplicarán los nuevos cambios.';
    }
    
    return view('ventas/editar_venta', [
        'venta'       => $venta,
        'detalles'    => $detalles,
        'productos'   => $productos,
        'mesa'        => $mesa,
        'advertencia' => $advertencia
    ]);
}


  public function save()
{
    $db = \Config\Database::connect();
    $db->transStart();
    
    $ventaModel = new VentaModel();
    $detalleModel = new VentaDetalleModel();
    $productoModel = new ProductoModel();
    
    $idVenta = $this->request->getPost('id_venta');
    $esEdicion = !empty($idVenta);
    
    // Si es edición, obtener la venta original
    $ventaOriginal = null;
    if ($esEdicion) {
        $ventaOriginal = $ventaModel->find($idVenta);
    }
    
    $dataVenta = [
        'fecha'        => $this->request->getPost('fecha') ?: date('Y-m-d H:i:s'),
        'cliente'      => $this->request->getPost('cliente'),
        'total'        => $this->request->getPost('total'),
        'usuario_id'   => session()->get('id'),
        'estado_pago'  => $this->request->getPost('estado_pago'),
        'metodo_pago'  => $this->request->getPost('metodo_pago'),
        'observacion'  => $this->request->getPost('observacion'),
        'id_mesa'      => $this->request->getPost('id_mesa') ?: null,
    ];
    
    if ($esEdicion) {
        // Revertir stock antiguo (incluso si estaba pagada)
        $oldDetalles = $detalleModel->where('id_venta', $idVenta)->findAll();
        foreach ($oldDetalles as $old) {
            $productoModel->set('stock_actual', "stock_actual + {$old['cantidad']}", false)
                          ->where('id', $old['id_producto'])
                          ->update();
        }
        $ventaModel->update($idVenta, $dataVenta);
        $detalleModel->where('id_venta', $idVenta)->delete();
    } else {
        $idVenta = $ventaModel->insert($dataVenta);
    }
    
    // Insertar nuevos detalles
    $productos_id = $this->request->getPost('producto_id');
    $cantidades   = $this->request->getPost('cantidad');
    $precios      = $this->request->getPost('precio_unitario');
    $subtotales   = $this->request->getPost('subtotal');
    
    if ($productos_id && is_array($productos_id)) {
        foreach ($productos_id as $i => $id_producto) {
            if (empty($id_producto) || $cantidades[$i] <= 0) continue;
            $detalleModel->insert([
                'id_venta'        => $idVenta,
                'id_producto'     => $id_producto,
                'cantidad'        => $cantidades[$i],
                'precio_unitario' => $precios[$i],
                'subtotal'        => $subtotales[$i],
            ]);
            $productoModel->set('stock_actual', "stock_actual - {$cantidades[$i]}", false)
                          ->where('id', $id_producto)
                          ->update();
        }
    }
    
    $db->transComplete();
    
    $venta = $ventaModel->find($idVenta);
    
    // Mensaje personalizado si era una venta pagada
    $mensaje = 'Venta guardada correctamente';
    if ($ventaOriginal && $ventaOriginal['estado_pago'] == 'pagado') {
        $mensaje = '⚠️ Venta pagada modificada. Los cambios han sido aplicados y el stock ha sido actualizado.';
    }
    
    if ($venta['id_mesa']) {
        return redirect()->to('/ventas/edit/' . $idVenta)->with('success', $mensaje);
    }
    return redirect()->to('/ventas')->with('success', $mensaje);
}
    
   public function delete($id)
{
    // 🔐 Solo ADMIN puede eliminar ventas
    if (session()->get('role') != 'admin') {
        return redirect()->to('/ventas')->with('error', '⚠️ Solo los administradores pueden eliminar ventas.');
    }
    
    $db = \Config\Database::connect();
    $db->transStart();
    
    $detalleModel = new VentaDetalleModel();
    $productoModel = new ProductoModel();
    $ventaModel = new VentaModel();
    
    // Revertir stock
    $detalles = $detalleModel->where('id_venta', $id)->findAll();
    foreach ($detalles as $d) {
        $productoModel->set('stock_actual', "stock_actual + {$d['cantidad']}", false)
                      ->where('id', $d['id_producto'])
                      ->update();
    }
    $detalleModel->where('id_venta', $id)->delete();
    $ventaModel->delete($id);
    
    $db->transComplete();
    return redirect()->to('/ventas')->with('success', 'Venta eliminada correctamente');
}
    /**
     * Exportar listado de ventas a PDF
     */
    public function export_pdf()
    {
        $ventaModel = new VentaModel();
        $ventas = $ventaModel->orderBy('id', 'desc')->findAll();

        if (!class_exists('\Dompdf\\Dompdf')) {
            return redirect()->back()->with('error', 'Librería Dompdf no encontrada. Instale con: composer require dompdf/dompdf');
        }

        $data = ['ventas' => $ventas];
        $html = view('ventas/pdf', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $fileName = 'ventas_' . date('Ymd_His') . '.pdf';
        $dompdf->stream($fileName, ['Attachment' => 1]);
        exit;
    }

    /**
     * Exportar una venta individual a PDF
     */
    public function export_pdf_one($id)
    {
        $ventaModel = new VentaModel();
        $detalleModel = new VentaDetalleModel();
        $mesaModel = new MesaModel();

        $venta = $ventaModel->find($id);
        if (!$venta) {
            return redirect()->to('/ventas')->with('error', 'Venta no encontrada');
        }

        $detalles = $detalleModel->select('ventas_detalle.*, productos.nombre as producto')
                                 ->join('productos', 'productos.id = ventas_detalle.id_producto')
                                 ->where('id_venta', $id)
                                 ->findAll();

        $mesa = null;
        if ($venta['id_mesa']) $mesa = $mesaModel->find($venta['id_mesa']);

        if (!class_exists('\Dompdf\\Dompdf')) {
            return redirect()->back()->with('error', 'Librería Dompdf no encontrada. Instale con: composer require dompdf/dompdf');
        }

        $data = ['venta' => $venta, 'detalles' => $detalles, 'mesa' => $mesa];
        $html = view('ventas/pdf_single', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $fileName = 'venta_'.$venta['id'].'_'.date('Ymd_His').'.pdf';
        $dompdf->stream($fileName, ['Attachment' => 1]);
        exit;
    }
    
    // ==================== MÉTODOS ADICIONALES PARA MESAS ====================
    
    public function agregar_mesa()
    {
        $mesaModel = new MesaModel();
        
        $numero = $this->request->getPost('numero');
        $nombre = $this->request->getPost('nombre');
        
        if (!$numero || !$nombre) {
            return redirect()->back()->with('error', 'Todos los campos son obligatorios');
        }
        
        // Verificar si ya existe una mesa con ese número
        $existe = $mesaModel->where('numero', $numero)->first();
        if ($existe) {
            return redirect()->back()->with('error', 'Ya existe una mesa con ese número');
        }
        
        $mesaModel->insert([
            'numero' => $numero,
            'nombre' => $nombre,
            'estado' => 'libre'
        ]);
        
        return redirect()->to('/ventas/mesas')->with('success', 'Mesa agregada correctamente');
    }
    
    public function editar_mesa()
    {
        $mesaModel = new MesaModel();
        
        $id = $this->request->getPost('id');
        $numero = $this->request->getPost('numero');
        $nombre = $this->request->getPost('nombre');
        
        if (!$id || !$numero || !$nombre) {
            return redirect()->back()->with('error', 'Todos los campos son obligatorios');
        }
        
        $mesa = $mesaModel->find($id);
        if (!$mesa) {
            return redirect()->back()->with('error', 'Mesa no encontrada');
        }
        
        // Verificar que el nuevo número no exista en otra mesa
        $existe = $mesaModel->where('numero', $numero)->where('id !=', $id)->first();
        if ($existe) {
            return redirect()->back()->with('error', 'Ya existe otra mesa con ese número');
        }
        
        $mesaModel->update($id, [
            'numero' => $numero,
            'nombre' => $nombre
        ]);
        
        return redirect()->to('/ventas/mesas')->with('success', 'Mesa actualizada correctamente');
    }
    
    public function eliminar_mesa()
    {
        $db = \Config\Database::connect();
        $db->transStart();
        
        $mesaModel = new MesaModel();
        $ventaModel = new VentaModel();
        
        $id = $this->request->getPost('id');
        
        $mesa = $mesaModel->find($id);
        if (!$mesa) {
            return redirect()->back()->with('error', 'Mesa no encontrada');
        }
        
        // Verificar que la mesa esté libre (sin ventas activas o pendientes)
        $ventasPendientes = $ventaModel->where('id_mesa', $id)
                                       ->where('estado_pago !=', 'pagado')
                                       ->countAllResults();
        
        if ($ventasPendientes > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar la mesa porque tiene ventas pendientes. Cierre las ventas primero.');
        }
        
        // Poner null en las ventas pagadas que tengan esta mesa
        $ventaModel->where('id_mesa', $id)->set(['id_mesa' => null])->update();
        
        $mesaModel->delete($id);
        
        $db->transComplete();
        
        return redirect()->to('/ventas/mesas')->with('success', 'Mesa eliminada correctamente');
    }
}