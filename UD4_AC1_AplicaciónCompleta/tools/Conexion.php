<?php
/**
 * aquí debes definir tu clase para conectarte a la base de datos.
 * Lo más profesional es leer los datos de un archivo "config.ini"
 * o "config.json" los parámetros necesarios para la conexión.
 */
namespace Mrs\tools;

require_once __DIR__.'/../vendor/autoload.php';

use PDO;
use PDOException;

class Conexion
{
    private static $conexion;

    private static $host = '127.0.0.1';
    private static $port = 8000;
    private static $db = 'gestorrestaurantes';
    private static $user = 'root';
    private static $pass = 'rpwd';
    private static $charset = 'utf8mb4';

    private function __construct()
    {
    }

    public static function getConexion()
    {
        if (self::$conexion === null) {
            try {
                $dsn = 'mysql:host='.self::$host.
                       ';port='.self::$port.
                       ';dbname='.self::$db.
                       ';charset='.self::$charset;

                self::$conexion = new PDO($dsn, self::$user, self::$pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                throw new PDOException('Error de conexión a la BD: '.$e->getMessage());
            }
        }

        return self::$conexion;
    }
}
