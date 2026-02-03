<?php

namespace Mrs\ApiServer\modelos;

use Mrs\ApiServer\librerias\Db;

/**
 * GestorMascotas - Gestiona operaciones CRUD de mascotas.
 */
class GestorMascotas
{
    /**
     * Obtiene todas las mascotas.
     *
     * @return array Lista de mascotas
     */
    public static function getMascotas()
    {
        $pdo = Db::getConexion();

        $sql = 'SELECT id, nombre, tipo, fecha_nacimiento, foto_url, id_persona 
                FROM mascotas 
                ORDER BY nombre';

        $stmt = $pdo->query($sql);

        return $stmt->fetchAll();
    }

    /**
     * Obtiene una mascota por ID.
     *
     * @return array|null Categoría o null si no existe
     */
    public static function getMascota($id)
    {
        $pdo = Db::getConexion();

        $sql = 'SELECT id, nombre, tipo, fecha_nacimiento, foto_url, id_persona 
                FROM mascotas 
                WHERE id = :id 
                LIMIT 1';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Crea una nueva mascota.
     *
     * @param string $id     UUID de la mascota
     * @param string $nombre Nombre de la mascota
     * @param string $tipo   Tipo
     *
     * @return bool True si se creó correctamente
     */
    public static function crearMascota($id, $nombre, $tipo, $fechaNac, $fotoUrl, $idPers)
    {
        $pdo = Db::getConexion();

        $sql = 'INSERT INTO mascotas (id, nombre, tipo, fecha_nacimiento, foto_url, id_persona) 
                VALUES (:id, :nombre, :tipo, :fechNac, :fotoUrl, :idPers)';

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'nombre' => $nombre,
            'tipo' => $tipo,
            'fechNac' => $fechaNac,
            'fotoUrl' => $fotoUrl,
            'idPers' => $idPers,
        ]);
    }

    /**
     * Actualiza una mascota existente.
     *
     * @param string $id     UUID de la mascota
     * @param string $nombre Nuevo nombre
     * @param string $tipo   Nueva descripción
     *
     * @return bool True si se actualizó
     */
    public static function actualizarMascota($id, $nombre, $tipo, $fechaNac, $fotoUrl, $idPers)
    {
        $pdo = Db::getConexion();

        $sql = 'UPDATE mascotas 
                SET nombre = :nombre, tipo = :tipo, fecha_nacimiento = :fechaNac, foto_url = :fotoUrl, id_persona= :idPers
                WHERE id = :id';

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'nombre' => $nombre,
            'tipo' => $tipo,
            'fechaNac' => $fechaNac,
            'fotoUrl' => $fotoUrl,
            'idPers' => $idPers,
        ]);
    }

    /**
     * Elimina una mascota.
     *
     * @param string $id UUID de la mascota
     *
     * @return bool True si se eliminó
     */
    public static function eliminarMascota($id)
    {
        $pdo = Db::getConexion();

        $sql = 'DELETE FROM mascotas WHERE id = :id';

        $stmt = $pdo->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }
}
