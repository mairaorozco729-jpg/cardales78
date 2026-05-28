<?php

namespace App\Controllers;

use CodeIgniter\Database\Config;

class PruebaBD extends BaseController
{
    public function index()
    {
        try {
            $db = Config::connect();
            $query = $db->query("SELECT username FROM users");
            $result = $query->getResult();

            echo "<h2>✅ Conexión exitosa a la base de datos</h2>";
            echo "<p>Usuarios encontrados:</p><ul>";
            foreach ($result as $row) {
                echo "<li>" . $row->username . "</li>";
            }
            echo "</ul>";
            echo "<p>Configuración cargada desde: " . (getenv('database.default.hostname') ? '.env' : 'Database.php') . "</p>";
        } catch (\Exception $e) {
            echo "<h2>❌ Error de conexión</h2>";
            echo "<p>" . $e->getMessage() . "</p>";
        }
    }

    public function testHash()
    {
        $hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $password = '123456';
        if (password_verify($password, $hash)) {
            echo "✅ La contraseña '123456' VERIFICA correctamente con el hash.";
        } else {
            echo "❌ La contraseña NO verifica.";
        }
    }

    public function testHashPublico()
    {
        $hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $password = '123456';
        if (password_verify($password, $hash)) {
            echo "✅ La contraseña '123456' VERIFICA correctamente con el hash.";
        } else {
            echo "❌ La contraseña NO verifica.";
        }
    }
}