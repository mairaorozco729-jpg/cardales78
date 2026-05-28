<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==================== RUTAS PÚBLICAS (SIN AUTENTICACIÓN) ====================
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/auth/authenticate', 'Auth::authenticate');
$routes->get('/logout', 'Auth::logout');

// Rutas de prueba públicas (útiles para depuración)
$routes->get('/prueba-bd', 'PruebaBD::index');
$routes->get('/test-hash', 'PruebaBD::testHash');
$routes->get('/test-hash-publico', 'PruebaBD::testHashPublico');

// ==================== RUTAS PROTEGIDAS CON FILTRO 'AUTH' ====================
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // ==================== DASHBOARD ====================
    $routes->get('/dashboard', 'Dashboard::index');

    // ==================== PRODUCTOS ====================
    $routes->get('/productos', 'Productos::index');
    $routes->get('/productos/create', 'Productos::create');
    $routes->post('/productos/save', 'Productos::save');
    $routes->get('/productos/edit/(:num)', 'Productos::edit/$1');
    $routes->post('/productos/update/(:num)', 'Productos::update/$1');
    $routes->get('/productos/delete/(:num)', 'Productos::delete/$1');

    // ==================== COMPRAS ====================
    $routes->get('/compras', 'Compras::index');
    $routes->get('/compras/create', 'Compras::create');
    $routes->post('/compras/save', 'Compras::save');
    $routes->get('/compras/detalle/(:num)', 'Compras::show/$1');
    $routes->get('/compras/edit/(:num)', 'Compras::edit/$1');
    $routes->post('/compras/update/(:num)', 'Compras::update/$1');
    $routes->get('/compras/delete/(:num)', 'Compras::delete/$1');

    // ==================== VENTAS (TRADICIONALES Y MESAS) ====================
    // Listado y gestión básica
    $routes->get('/ventas', 'Ventas::index');
    $routes->get('/ventas/create', 'Ventas::create');
    $routes->get('/ventas/export_pdf', 'Ventas::export_pdf');
    $routes->get('/ventas/export_pdf_one/(:num)', 'Ventas::export_pdf_one/$1');
    $routes->post('/ventas/save', 'Ventas::save');
    $routes->get('/ventas/detalle/(:num)', 'Ventas::show/$1');
    $routes->get('/ventas/edit/(:num)', 'Ventas::edit/$1');
    $routes->post('/ventas/update/(:num)', 'Ventas::update/$1');
    $routes->get('/ventas/delete/(:num)', 'Ventas::delete/$1');

    // Rutas unificadas para gestión de productos en venta (funcionan para ventas normales y mesas)
    $routes->post('/ventas/agregar_producto_venta', 'Ventas::agregar_producto_venta');
    $routes->post('/ventas/editar_cantidad_venta', 'Ventas::editar_cantidad_venta');
    $routes->get('/ventas/eliminar_producto_venta/(:num)', 'Ventas::eliminar_producto_venta/$1');
    $routes->post('/ventas/actualizar_venta', 'Ventas::actualizar_venta');

    // ==================== GESTIÓN DE MESAS ====================
    // Tablero de mesas
    $routes->get('/ventas/mesas', 'Ventas::mesas');
    
    // Gestión de consumo de mesa (redirige a edit)
    $routes->get('/ventas/mesa_consumo/(:num)', 'Ventas::mesa_consumo/$1');
    
    // Acciones sobre mesas
    $routes->post('/ventas/cerrar_mesa/(:num)', 'Ventas::cerrar_mesa/$1');
    $routes->post('/ventas/agregar_mesa', 'Ventas::agregar_mesa');
    $routes->post('/ventas/editar_mesa', 'Ventas::editar_mesa');
    $routes->post('/ventas/eliminar_mesa', 'Ventas::eliminar_mesa');

    // ==================== GASTOS ====================
    $routes->get('/gastos', 'Gastos::index');
    $routes->get('/gastos/create', 'Gastos::create');
    $routes->post('/gastos/save', 'Gastos::save');
    $routes->get('/gastos/detalle/(:num)', 'Gastos::show/$1');
    $routes->get('/gastos/edit/(:num)', 'Gastos::edit/$1');
    $routes->post('/gastos/update/(:num)', 'Gastos::update/$1');
    $routes->get('/gastos/delete/(:num)', 'Gastos::delete/$1');

    // Gestión de usuarios (solo admin)
$routes->get('/usuarios', 'Usuarios::index');
$routes->get('/usuarios/create', 'Usuarios::create');
$routes->post('/usuarios/save', 'Usuarios::save');
$routes->get('/usuarios/delete/(:num)', 'Usuarios::delete/$1');
});