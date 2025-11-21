<?php

require_once __DIR__.'/Conexion.php';
class Validadores
{
    public static function validarUsuario($name, $password)
    {
        if (empty($name) || empty($password)) {
            return false;
        }

        try {
            $pdo = Conexion::getConexion();
            $sql = 'SELECT * FROM users WHERE name = :name AND password = :password';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':password' => $password,
            ]);

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            return $usuario ?: false;
        } catch (PDOException $e) {
            throw new Exception('Error al validar usuario: '.$e->getMessage());
        }
    }
}
