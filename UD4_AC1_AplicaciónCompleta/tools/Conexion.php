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
use Mrs\tools\Config;

class Conexion
{
    private static $conexion;
    private static $config;
    private static $driver;
    private static $host;
    private static $port;
    private static $db;
    private static $user;
    private static $pass;
    private static $charset;

    private function __construct()
    {
    }

    public static function getConexion()
    {
        if (self::$conexion === null) {
            try {
                self::$config = Config::getInstance();
                self::$driver = self::$config->get('database', 'driver');
                self::$host   = self::$config->get('database', 'host');
                self::$db = self::$config->get('database', 'dbname');
                self::$port   = self::$config->get('database', 'port');
                self::$user   = self::$config->get('database', 'user');
                self::$pass   = self::$config->get('database', 'pass');
                self::$charset   = self::$config->get('database', 'charset');
                $dsn = self::$driver.':host='.self::$host.
                       ';port='.self::$port.
                       ';dbname='.self::$db.
                       ';charset='.self::$charset;
                error_log("DB HOST=" . self::$host . " PORT=" . self::$port . " DB=" . self::$db . " USER=" . self::$user. 'charset='.self::$charset);

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
