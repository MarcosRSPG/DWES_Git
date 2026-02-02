<?php

namespace Mrs\ApiServer\modelos;

use Mrs\ApiServer\librerias\Db;

/**
 * GestorUsuarios - Gestiona usuarios de autenticación API
 * Para Basic Auth con verificación en BD.
 */
class GestorUsuarios
{
    /**
     * Obtiene un usuario por su nombre de usuario.
     *
     * @param string $user Nombre de usuario
     *
     * @return array|null Array con datos del usuario o null si no existe
     */
    public static function obtenerPorNombre($user)
    {
        $pdo = Db::getConexion();

        $sql = 'SELECT id, user, pass, nombre_completo, fecha_creacion
                FROM usuarios_api
                WHERE user = :user
                LIMIT 1';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user' => $user]);

        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Crea un nuevo usuario API.
     *
     * @param string $user            Nombre de usuario
     * @param string $pass            Contraseña (se hasheará automáticamente)
     * @param string $nombre_completo Nombre completo del usuario
     *
     * @return bool True si se creó correctamente
     */
    public static function crearUsuario($user, $pass, $nombre_completo = '')
    {
        $pdo = Db::getConexion();

        // Hash de la contraseña
        $passHash = password_hash($pass, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO usuarios_api (user, pass, nombre_completo)
                VALUES (:user, :pass, :nombre)';

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'user' => $user,
            'pass' => $passHash,
            'nombre' => $nombre_completo,
        ]);
    }

    /**
     * Valida las credenciales de un usuario.
     *
     * @param string $user Nombre de usuario
     * @param string $pass Contraseña en texto plano
     *
     * @return array|null Datos del usuario si las credenciales son válidas, null en caso contrario
     */
    public static function validarCredenciales($user, $pass)
    {
        $usuario = self::obtenerPorNombre($user);

        if (!$usuario) {
            return null;
        }

        // Verificar contraseña con hash
        if (password_verify($pass, $usuario['pass'])) {
            return $usuario;
        }

        return null;
    }

    /**
     * Actualiza la contraseña de un usuario.
     *
     * @param string $user Nombre de usuario
     * @param string $pass Nueva contraseña
     *
     * @return bool True si se actualizó correctamente
     */
    public static function actualizarPassword($user, $pass)
    {
        $pdo = Db::getConexion();

        $passHash = password_hash($pass, PASSWORD_DEFAULT);

        $sql = 'UPDATE usuarios_api
                SET pass = :pass
                WHERE user = :user';

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'pass' => $passHash,
            'user' => $user,
        ]);
    }

    /**
     * Lista todos los usuarios API (sin contraseñas).
     *
     * @return array Lista de usuarios
     */
    public static function listarUsuarios()
    {
        $pdo = Db::getConexion();

        $sql = 'SELECT id, user, nombre_completo, fecha_creacion
                FROM usuarios_api
                ORDER BY user';

        $stmt = $pdo->query($sql);

        return $stmt->fetchAll();
    }
}
