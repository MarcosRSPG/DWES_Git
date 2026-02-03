<?php

namespace Mrs\ApiServer\modelos;

use Mrs\ApiServer\librerias\Db;

/**
 * GestorVeterinarios - Gestiona operaciones de veterinarios y autenticación.
 */
class GestorVeterinarios
{
    /**
     * Valida credenciales de un veterinario.
     *
     * @param string $email Email del veterinario
     * @param string $clave Contraseña en texto plano
     *
     * @return array|null Datos del veterinario o null si no válido
     */
    public static function validarCredenciales($email, $clave)
    {
        $pdo = Db::getConexion();

        $sql = 'SELECT id, email, clave, nombre
                FROM veterinarios
                WHERE email = :email
                LIMIT 1';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        $veterinario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$veterinario) {
            return null;
        }

        // Verificar contraseña
        if ($clave === $veterinario['clave']) {
            unset($veterinario['clave']);

            return $veterinario;
        }

        return null;
    }

    /**
     * Obtiene un veterinario por email.
     *
     * @param string $email Email del veterinario
     *
     * @return array|null Datos del veterinario (sin contraseña)
     */
    public static function getVeterinarioPorCorreo($email)
    {
        $pdo = Db::getConexion();

        $sql = 'SELECT id, email, nombre
                FROM veterinarios
                WHERE email = :email
                LIMIT 1';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Obtiene el UUID de un veterinario por su email.
     *
     * @param string $email Email del veterinario
     *
     * @return string|null UUID del veterinario
     */
    public static function getidPorCorreo($email)
    {
        $pdo = Db::getConexion();

        $sql = 'SELECT id FROM veterinarios WHERE email = :email LIMIT 1';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        $row = $stmt->fetch();

        return $row ? $row['id'] : null;
    }

    /**
     * Crea un nuevo veterinario.
     *
     * @param string $codRes UUID del veterinario
     * @param string $email  Email
     * @param string $clave  Contraseña en texto plano (se hasheará)
     *
     * @return bool True si se creó correctamente
     */
    public static function crearVeterinario(
        $codRes,
        $email,
        $clave,
        $nombre,
    ) {
        $pdo = Db::getConexion();

        // Hashear la contraseña
        $claveHash = password_hash($clave, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO veterinarios (id, email, clave, nombre)
                VALUES (:cod, :email, :clave, :nombre)';

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'cod' => $codRes,
            'email' => $email,
            'clave' => $claveHash,
            'nombre' => $nombre,
        ]);
    }
}
