<?php

namespace App\Controllers;
use App\Models\UserModel;


class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function authenticate()
    {
        $session = session();
        $model = new UserModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $model->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
    $sessionData = [
        'id'       => $user['id'],
        'username' => $user['username'],
        'role'     => $user['role'],
        'logged_in'=> true,
    ];
    $session->set($sessionData);
    
    // Redirigir según el rol
    if ($user['role'] == 'admin') {
        return redirect()->to('/dashboard');
    } else {
        return redirect()->to('/ventas'); // Vendedor va a Ventas
    }
}
    }

   public function logout()
{
    session()->destroy();
    return redirect()->to('/');  // Redirige a la raíz (login)
}
}