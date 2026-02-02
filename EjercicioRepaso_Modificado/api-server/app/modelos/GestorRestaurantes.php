<?php

namespace Mrs\ApiServer\modelos;

use Mrs\ApiServer\librerias\Db;

/**
 * GestorRestaurantes - Gestiona operaciones de restaurantes y autenticación.
 */
class GestorRestaurantes
{
    /**
     * Valida credenciales de un restaurante.
     *
     * @param string $correo Email del restaurante
     * @param string $clave  Contraseña en texto plano
     *
     * @return array|null Datos del restaurante o null si no válido
     */
    public static function validarCredenciales($correo, $clave)
    {
        $pdo = Db::getConexion();

        $sql = 'SELECT CodRes, Correo, Clave, Nombre, Telefono, Direccion
                FROM restaurantes
                WHERE Correo = :correo
                LIMIT 1';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['correo' => $correo]);

        $restaurante = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$restaurante) {
            return null;
        }

        // Verificar contraseña
        if (password_verify($clave, $restaurante['Clave'])) {
            // No devolver la contraseña
            unset($restaurante['Clave']);

            return $restaurante;
        }

        return null;
    }

    /**
     * Obtiene un restaurante por correo.
     *
     * @param string $correo Email del restaurante
     *
     * @return array|null Datos del restaurante (sin contraseña)
     */
    public static function getRestaurantePorCorreo($correo)
    {
        $pdo = Db::getConexion();

        $sql = 'SELECT CodRes, Correo, Nombre, Telefono, Direccion
                FROM restaurantes
                WHERE Correo = :correo
                LIMIT 1';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['correo' => $correo]);

        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Obtiene el UUID de un restaurante por su correo.
     *
     * @param string $correo Email del restaurante
     *
     * @return string|null UUID del restaurante
     */
    public static function getCodResPorCorreo($correo)
    {
        $pdo = Db::getConexion();

        $sql = 'SELECT CodRes FROM restaurantes WHERE Correo = :correo LIMIT 1';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['correo' => $correo]);

        $row = $stmt->fetch();

        return $row ? $row['CodRes'] : null;
    }

    /**
     * Crea un nuevo restaurante.
     *
     * @param string $codRes    UUID del restaurante
     * @param string $correo    Email
     * @param string $clave     Contraseña en texto plano (se hasheará)
     * @param string $direccion Dirección
     *
     * @return bool True si se creó correctamente
     */
    public static function crearRestaurante(
        $codRes,
        $correo,
        $clave,
        $nombre,
        $telefono = null,
        $direccion = null
    ) {
        $pdo = Db::getConexion();

        // Hashear la contraseña
        $claveHash = password_hash($clave, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO restaurantes (CodRes, Correo, Clave, Nombre, Telefono, Direccion)
                VALUES (:cod, :correo, :clave, :nombre, :telefono, :direccion)';

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'cod' => $codRes,
            'correo' => $correo,
            'clave' => $claveHash,
            'nombre' => $nombre,
            'telefono' => $telefono,
            'direccion' => $direccion,
        ]);
    }
}
