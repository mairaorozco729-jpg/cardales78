<?php

namespace App\Controllers;

use App\Models\VentaModel;
use App\Models\GastoModel;
use App\Models\CompraModel;
use App\Models\ProductoModel;
use App\Models\VentaDetalleModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $ventaModel = new VentaModel();
        $gastoModel = new GastoModel();
        $compraModel = new CompraModel();
        $productoModel = new ProductoModel();
        $ventaDetalleModel = new VentaDetalleModel();

        // Obtener fechas del filtro (si existen)
        $fecha_inicio = $this->request->getGet('fecha_inicio');
        $fecha_fin    = $this->request->getGet('fecha_fin');

        $mostrar_filtro = false;
        if ($fecha_inicio && $fecha_fin) {
            $mostrar_filtro = true;
        } else {
            $fecha_inicio = date('Y-m-d');
            $fecha_fin    = date('Y-m-d');
        }

        // --- Ventas del período ---
        $total_ventas = $ventaModel->where('DATE(fecha) >=', $fecha_inicio)
                                   ->where('DATE(fecha) <=', $fecha_fin)
                                   ->selectSum('total')
                                   ->first()['total'] ?? 0;

        // --- Gastos del período ---
        $total_gastos = $gastoModel->where('DATE(fecha) >=', $fecha_inicio)
                                   ->where('DATE(fecha) <=', $fecha_fin)
                                   ->selectSum('monto')
                                   ->first()['monto'] ?? 0;

        // --- Compras del período ---
        $total_compras = $compraModel->where('DATE(fecha) >=', $fecha_inicio)
                                     ->where('DATE(fecha) <=', $fecha_fin)
                                     ->selectSum('total')
                                     ->first()['total'] ?? 0;

        // --- Ganancia Neta = Ventas - (Compras + Gastos) ---
        $ganancia_neta = $total_ventas - ($total_compras + $total_gastos);

        // --- Ventas del mes ---
        $inicioMes = date('Y-m-01');
        $ventas_mes = $ventaModel->where('DATE(fecha) >=', $inicioMes)
                                 ->selectSum('total')
                                 ->first()['total'] ?? 0;

        $total_productos = $productoModel->countAll();

        // --- STOCK BAJO: Productos con stock_actual <= stock_minimo ---
        $productos_stock_bajo = $productoModel->where('stock_actual <= stock_minimo', null, false)
                                              ->orderBy('stock_actual', 'asc')
                                              ->findAll();
        $total_stock_bajo = count($productos_stock_bajo);

        // --- Productos más vendidos (últimos 30 días) para tabla ---
        $fecha_limite = date('Y-m-d', strtotime('-30 days'));
        $productos_vendidos = $ventaDetalleModel->select('productos.nombre, SUM(ventas_detalle.cantidad) as total_vendido, SUM(ventas_detalle.subtotal) as total_venta')
                                                ->join('productos', 'productos.id = ventas_detalle.id_producto')
                                                ->join('ventas', 'ventas.id = ventas_detalle.id_venta')
                                                ->where('ventas.fecha >=', $fecha_limite)
                                                ->groupBy('productos.id')
                                                ->orderBy('total_vendido', 'DESC')
                                                ->limit(10)
                                                ->find();

        // --- Datos para el GRÁFICO de productos más vendidos ---
        $top_nombres = [];
        $top_cantidades = [];
        foreach ($productos_vendidos as $p) {
            $top_nombres[] = $p['nombre'];
            $top_cantidades[] = (int)$p['total_vendido'];
        }

        // --- Listado de ventas del período ---
        $ventas = $ventaModel->where('DATE(fecha) >=', $fecha_inicio)
                             ->where('DATE(fecha) <=', $fecha_fin)
                             ->orderBy('fecha', 'DESC')
                             ->limit(10)
                             ->find();

        return view('dashboard/index', [
            'fecha_inicio'         => $fecha_inicio,
            'fecha_fin'            => $fecha_fin,
            'mostrar_filtro'       => $mostrar_filtro,
            'total_ventas'         => $total_ventas,
            'total_gastos'         => $total_gastos,
            'total_compras'        => $total_compras,
            'ganancia_neta'        => $ganancia_neta,
            'ventas_mes'           => $ventas_mes,
            'total_productos'      => $total_productos,
            'productos_vendidos'   => $productos_vendidos,
            'ventas'               => $ventas,
            'productos_stock_bajo' => $productos_stock_bajo,
            'total_stock_bajo'     => $total_stock_bajo,
            'top_nombres'          => json_encode($top_nombres),
            'top_cantidades'       => json_encode($top_cantidades),
        ]);
    }
}