<?php

namespace Mrs\ApiServer\modelos;

use Mrs\ApiServer\librerias\Db;

/**
 * GestorProductos - Gestiona operaciones CRUD de productos.
 */
class GestorProductos
{
    /**
     * Obtiene todos los productos.
     */
    public static function getProductos()
    {
        $pdo = Db::getConexion();

        $sql = 'SELECT p.CodProd, p.Nombre, p.Descripcion, p.Precio, p.Stock, p.Categoria,
                       c.Nombre as CategoriaNombre
                FROM productos p
                LEFT JOIN categorias c ON p.Categoria = c.CodCat
                ORDER BY p.Nombre';

        $stmt = $pdo->query($sql);

        return $stmt->fetchAll();
    }

    /**
     * Obtiene productos de una categoría específica.
     */
    public static function getProductosPorCategoria($codCat)
    {
        $pdo = Db::getConexion();

        $sql = 'SELECT p.CodProd, p.Nombre, p.Descripcion, p.Precio, p.Stock, p.Categoria,
                       c.Nombre as CategoriaNombre
                FROM productos p
                LEFT JOIN categorias c ON p.Categoria = c.CodCat
                WHERE p.Categoria = :cat
                ORDER BY p.Nombre';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['cat' => $codCat]);

        return $stmt->fetchAll();
    }

    /**
     * Obtiene un producto por ID.
     */
    public static function getProducto($codProd)
    {
        $pdo = Db::getConexion();

        $sql = 'SELECT p.CodProd, p.Nombre, p.Descripcion, p.Precio, p.Stock, p.Categoria,
                       c.Nombre as CategoriaNombre
                FROM productos p
                LEFT JOIN categorias c ON p.Categoria = c.CodCat
                WHERE p.CodProd = :id
                LIMIT 1';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $codProd]);

        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Crea un nuevo producto.
     */
    public static function crearProducto(
        $codProd,
        $nombre,
        $descripcion,
        $precio,
        $stock,
        $categoria
    ) {
        $pdo = Db::getConexion();

        $sql = 'INSERT INTO productos (CodProd, Nombre, Descripcion, Precio, Stock, Categoria)
                VALUES (:cod, :nombre, :desc, :precio, :stock, :cat)';

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'cod' => $codProd,
            'nombre' => $nombre,
            'desc' => $descripcion,
            'precio' => $precio,
            'stock' => $stock,
            'cat' => $categoria,
        ]);
    }

    /**
     * Actualiza un producto existente.
     */
    public static function actualizarProducto(
        $codProd,
        $nombre,
        $descripcion,
        $precio,
        $stock,
        $categoria
    ) {
        $pdo = Db::getConexion();

        $sql = 'UPDATE productos
                SET Nombre = :nombre, Descripcion = :desc, Precio = :precio,
                    Stock = :stock, Categoria = :cat
                WHERE CodProd = :cod';

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'cod' => $codProd,
            'nombre' => $nombre,
            'desc' => $descripcion,
            'precio' => $precio,
            'stock' => $stock,
            'cat' => $categoria,
        ]);
    }

    /**
     * Elimina un producto.
     */
    public static function eliminarProducto($codProd)
    {
        $pdo = Db::getConexion();

        $sql = 'DELETE FROM productos WHERE CodProd = :cod';

        $stmt = $pdo->prepare($sql);

        return $stmt->execute(['cod' => $codProd]);
    }

    /**
     * Reduce el stock de un producto.
     */
    public static function restarStock($codProd, $unidades)
    {
        if ($unidades <= 0) {
            return true;
        }

        $pdo = Db::getConexion();

        $sql = 'UPDATE productos
                SET Stock = Stock - :u
                WHERE CodProd = :p AND Stock >= :u';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['u' => $unidades, 'p' => $codProd]);

        return $stmt->rowCount() === 1;
    }
}
