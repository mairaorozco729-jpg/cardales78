<?php
namespace App\Controllers;

use App\Models\UserModel;

class Usuarios extends BaseController
{
    public function index()
    {
        // Solo admin puede ver esta sección
        if (session()->get('role') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Acceso denegado');
        }
        
        $model = new UserModel();
        $usuarios = $model->findAll();
        
        return view('usuarios/index', ['usuarios' => $usuarios]);
    }
    
    public function create()
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Acceso denegado');
        }
        
        return view('usuarios/form');
    }
    
    public function save()
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Acceso denegado');
        }
        
        $model = new UserModel();
        $password = $this->request->getPost('password');
        
        $model->save([
            'username' => $this->request->getPost('username'),
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role')
        ]);
        
        return redirect()->to('/usuarios')->with('success', 'Usuario creado correctamente');
    }
    
    public function delete($id)
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Acceso denegado');
        }
        
        // No permitir eliminar el propio usuario
        if ($id == session()->get('id')) {
            return redirect()->back()->with('error', 'No puedes eliminarte a ti mismo');
        }
        
        $model = new UserModel();
        $model->delete($id);
        
        return redirect()->to('/usuarios')->with('success', 'Usuario eliminado');
    }
}