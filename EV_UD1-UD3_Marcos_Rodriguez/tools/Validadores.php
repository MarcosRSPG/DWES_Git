<?php

require_once __DIR__.'/Conexion.php';
class Validadores
{
    public static function validarUsuario($username, $password)
    {
        if (empty($username) || empty($password)) {
            return false;
        }

        try {
            $pdo = Conexion::getConexion();
            $sql = 'SELECT * FROM usuarios WHERE username = :username AND password = :password';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':username' => $username,
                ':password' => $password,
            ]);

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            return $usuario ?: false;
        } catch (PDOException $e) {
            throw new Exception('Error al validar usuario: '.$e->getMessage());
        }
    }
}
