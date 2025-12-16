<?php

namespace Mrs\tools;

require_once __DIR__.'/../vendor/autoload.php';

use PDO;
use PDOException;
class Validadores
{
    public static function validarUsuario($correo, $clave)
    {
        if (empty($correo) || empty($clave)) {
            return false;
        }

        try {
            $pdo = Conexion::getConexion();
            $sql = 'SELECT * FROM restaurantes WHERE correo = :correo AND clave = :clave';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':correo' => $correo,
                ':clave' => $clave,
            ]);

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            return $usuario ?: false;
        } catch (PDOException $e) {
            throw new PDOException('Error al validar usuario: '.$e->getMessage());
        }
    }
}
