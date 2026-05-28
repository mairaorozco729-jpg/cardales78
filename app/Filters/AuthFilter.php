<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Obtener la ruta actual
        $currentPath = $request->getPath();
        
        // 🔓 RUTAS PÚBLICAS (NO requieren autenticación)
        $publicPaths = [
            '/',
            '/login',
            '/auth/authenticate',
            '/logout',
            '/prueba-bd',
            '/test-hash',
            '/test-hash-publico'
        ];
        
        // Si es ruta pública, permitir acceso
        if (in_array($currentPath, $publicPaths)) {
            return;
        }
        
        // 🔐 Verificar si el usuario está logueado
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        // 👤 Obtener el rol
        $role = session()->get('role');
        
        // 🚫 RUTAS PROHIBIDAS para VENDEDORES
        $rutasProhibidas = [
            '/dashboard',         // 📌 Dashboard NO visible para vendedores
            '/compras',           // Módulo de compras
            '/gastos',            // Módulo de gastos
            '/usuarios',          // Gestión de usuarios
            '/productos/create',  // Crear producto
            '/productos/edit',    // Editar producto
            '/productos/delete'   // Eliminar producto
        ];
        
        // Si es vendedor, verificar que no acceda a rutas prohibidas
        if ($role === 'vendedor') {
            foreach ($rutasProhibidas as $prohibida) {
                if (strpos($currentPath, $prohibida) === 0) {
                    // Redirigir a la página de VENTAS (acceso permitido)
                    return redirect()->to('/ventas')
                                     ->with('error', '⚠️ Acceso denegado. No tienes permiso para esta sección.');
                }
            }
        }
        
        return;
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No hacer nada
    }
}