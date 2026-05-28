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
        } catch (\Exception $e) {
            echo "<h2>❌ Error de conexión</h2>";
            echo "<p>" . $e->getMessage() . "</p>";
        }
    }
}