<?php

namespace Mrs\ApiServer\modelos;

use Mrs\ApiServer\librerias\Db;

/**
 * GestorCategorias - Gestiona operaciones CRUD de categorías.
 */
class GestorCategorias
{
    /**
     * Obtiene todas las categorías.
     *
     * @return array Lista de categorías
     */
    public static function getCategorias()
    {
        $pdo = Db::getConexion();

        $sql = 'SELECT CodCat, Nombre, Descripcion 
                FROM categorias 
                ORDER BY Nombre';

        $stmt = $pdo->query($sql);

        return $stmt->fetchAll();
    }

    /**
     * Obtiene una categoría por ID.
     *
     * @param string $codCat Código de la categoría (UUID)
     *
     * @return array|null Categoría o null si no existe
     */
    public static function getCategoria($codCat)
    {
        $pdo = Db::getConexion();

        $sql = 'SELECT CodCat, Nombre, Descripcion 
                FROM categorias 
                WHERE CodCat = :id 
                LIMIT 1';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $codCat]);

        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Crea una nueva categoría.
     *
     * @param string $codCat      UUID de la categoría
     * @param string $nombre      Nombre de la categoría
     * @param string $descripcion Descripción
     *
     * @return bool True si se creó correctamente
     */
    public static function crearCategoria($codCat, $nombre, $descripcion)
    {
        $pdo = Db::getConexion();

        $sql = 'INSERT INTO categorias (CodCat, Nombre, Descripcion) 
                VALUES (:cod, :nombre, :desc)';

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'cod' => $codCat,
            'nombre' => $nombre,
            'desc' => $descripcion,
        ]);
    }

    /**
     * Actualiza una categoría existente.
     *
     * @param string $codCat      UUID de la categoría
     * @param string $nombre      Nuevo nombre
     * @param string $descripcion Nueva descripción
     *
     * @return bool True si se actualizó
     */
    public static function actualizarCategoria($codCat, $nombre, $descripcion)
    {
        $pdo = Db::getConexion();

        $sql = 'UPDATE categorias 
                SET Nombre = :nombre, Descripcion = :desc 
                WHERE CodCat = :cod';

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'cod' => $codCat,
            'nombre' => $nombre,
            'desc' => $descripcion,
        ]);
    }

    /**
     * Elimina una categoría.
     *
     * @param string $codCat UUID de la categoría
     *
     * @return bool True si se eliminó
     */
    public static function eliminarCategoria($codCat)
    {
        $pdo = Db::getConexion();

        $sql = 'DELETE FROM categorias WHERE CodCat = :cod';

        $stmt = $pdo->prepare($sql);

        return $stmt->execute(['cod' => $codCat]);
    }
}
