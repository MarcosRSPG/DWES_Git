<?php

namespace Mrs\ApiServer\librerias;

use PDO;

/**
 * Clase Db - Conexión a base de datos (Singleton)
 * Proporciona una única instancia de conexión PDO.
 */
class Db
{
    private static ?\PDO $conexion = null;

    /**
     * Constructor privado (patrón Singleton).
     */
    private function __construct()
    {
    }

    /**
     * Obtiene la conexión PDO (única instancia).
     *
     * @throws \PDOException
     */
    public static function getConexion(): \PDO
    {
        if (self::$conexion === null) {
            try {
                $dsn = 'mysql:host='.DB_HOST.
                       ';port='.DB_PORT.
                       ';dbname='.DB_NOMBRE.
                       ';charset='.DB_CHARSET;

                self::$conexion = new \PDO($dsn, DB_USUARIO, DB_PASSWORD, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (\PDOException $e) {
                error_log('Error de conexión a BD: '.$e->getMessage());
                throw new \PDOException('Error al conectar con la base de datos');
            }
        }

        return self::$conexion;
    }
}
