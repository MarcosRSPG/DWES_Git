<?php
// tools/Conexion.php
namespace Mrs\tools;
require_once __DIR__.'/../vendor/autoload.php';
use Mrs\tools\Config;
// Esta clase también deberia ser singleton.
// Crearla ...

class Conexion
{
    public static function getConexion() :\PDO
    {
        /*
        // Leemos el archivo de configuración con secciones
        $config = parse_ini_file(__DIR__ . '/../config/config.ini', true);

        if ($config === false) {
            die("No se pudo leer config.ini");
        }

        // Sacamos SOLO la parte de [database]
        $db = $config['database'];

        $driver = $db['driver'];   // mysql o pgsql
        $host   = $db['host'];
        $dbname = $db['dbname'];
        $port   = $db['port'];
        $user   = $db['user'];
        $pass   = $db['pass'];

        */

        $config = Config::getInstance();

        
        $driver = $config->get('database', 'driver');
        $host   = $config->get('database', 'host');
        $dbname = $config->get('database', 'dbname');
        $port   = $config->get('database', 'port');
        $user   = $config->get('database', 'user');
        $pass   = $config->get('database', 'pass');
        
        // DSN básico
        $dsn = "$driver:host=$host;dbname=$dbname;port=$port";

        // Ajuste especial solo para MySQL
        if ($driver === 'mysql') {
            $dsn .= ";charset=utf8mb4";
        }

        

        try {
            $pdo = new \PDO($dsn, $user, $pass);
            // Mostramos errores como excepciones (más fácil de depurar)
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (\PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
