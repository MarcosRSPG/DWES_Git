<?php
/**
 * aquí debes definir tu clase para conectarte a la base de datos.
 * Lo más profesional es leer los datos de un archivo "config.ini"
 * o "config.json" los parámetros necesarios para la conexión.
 */
class Conexion
{
    private static $host = 'ep-rapid-queen-agwaqadx-pooler.c-2.eu-central-1.aws.neon.tech';
    private static $db = 'dws_bbdd_ud3_ac';
    private static $user = 'neondb_owner';
    private static $pass = 'npg_dzwj7S1axYJE';
    private static $sslmode = 'require';
    private static $channelBinding = 'require';

    public function __construct()
    {
    }

    public static function getConexion()
    {
        $pdo = null;

        $dsn = 'pgsql:host='.self::$host.';dbname='.self::$db.';sslmode='.self::$sslmode.';channel_binding='.self::$channelBinding;

        try {
            $pdo = new PDO($dsn, self::$user, self::$pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            $pdo->exec("SET NAMES 'UTF8'");
        }

        return $pdo;
    }
}
