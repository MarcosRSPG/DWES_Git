<?php
namespace Mrs\Restaurante;

require_once __DIR__ . '/../vendor/autoload.php';

use Mrs\tools\Conexion;
use PDO;

class GestorProductos
{
    private static ?PDO $pdo = null;

    private static function pdo(): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = Conexion::getConexion();
        }
        return self::$pdo;
    }


    public static function getProductos(): array
    {
        $sql = "SELECT CodProd, Nombre, Descripcion, Peso, Stock, Categoria
                FROM productos
                ORDER BY Nombre";
        return self::pdo()->query($sql)->fetchAll();
    }


    public static function getProductosPorCategoria(string $codCat): array
    {
        $sql = "SELECT CodProd, Nombre, Descripcion, Peso, Stock, Categoria
                FROM productos
                WHERE Categoria = :c
                ORDER BY Nombre";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute(['c' => $codCat]);
        return $stmt->fetchAll();
    }


    public static function getProducto(string $codProd): ?array
    {
        $sql = "SELECT CodProd, Nombre, Descripcion, Peso, Stock, Categoria
                FROM productos
                WHERE CodProd = :id
                LIMIT 1";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute(['id' => $codProd]);
        $row = $stmt->fetch();
        return $row ?: null;
    }


    public static function insertProducto(Producto $producto): void
    {
        $sql = "INSERT INTO productos (CodProd, Nombre, Descripcion, Peso, Stock, Categoria)
                VALUES (:CodProd, :Nombre, :Descripcion, :Peso, :Stock, :Categoria)";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($producto->toDbParams());
    }


    public static function updateProducto(Producto $producto): void
    {
        $sql = "UPDATE productos
                SET Nombre = :Nombre, Descripcion = :Descripcion, Peso = :Peso, Stock = :Stock, Categoria = :Categoria
                WHERE CodProd = :CodProd";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($producto->toDbParams());
    }


    public static function deleteProducto(string $id): void
    {
        $sql = "DELETE FROM productos WHERE CodProd = :id";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute(['id' => $id]);
    }


    public static function restarStock(string $codProd, int $unidades, ?PDO $pdo = null): bool
    {
        if ($unidades <= 0) return true;
        $pdo = $pdo ?? self::pdo();

        $sql = "UPDATE productos
                SET Stock = Stock - :u
                WHERE CodProd = :p AND Stock >= :u";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['u' => $unidades, 'p' => $codProd]);

        return $stmt->rowCount() === 1;
    }
}
