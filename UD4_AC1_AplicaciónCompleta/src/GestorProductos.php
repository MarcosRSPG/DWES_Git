<?php
namespace Mrs\Restaurante;

require_once __DIR__.'/../vendor/autoload.php';

use Mrs\tools\Conexion;
use PDO;

class GestorProductos
{
    private static ?PDO $pdo = null;

    private static function pdo(): PDO {
        if (self::$pdo === null) {
            self::$pdo = Conexion::getConexion();
        }
        return self::$pdo;
    }

    public static function getProductos(): array {
        $sql = "SELECT * FROM productos";
        return self::pdo()->query($sql)->fetchAll();
    }

    public static function getProducto(string $id) {
        $sql = "SELECT * FROM productos WHERE CodProd = :id";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public static function getProductosPorCategoria(string $categoria): array {
        $sql = "SELECT * FROM productos WHERE Categoria = :categoria";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute(['categoria' => $categoria]);
        return $stmt->fetchAll();
    }

    public static function insertProducto(Producto $producto): void {
        $sql = "INSERT INTO productos (CodProd, Nombre, Descripcion, Peso, Stock, Categoria)
                VALUES (:CodProd, :Nombre, :Descripcion, :Peso, :Stock, :Categoria)";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($producto->toDbParams());
    }

    public static function updateProducto(Producto $producto): void {
        $sql = "UPDATE productos
                SET Nombre = :Nombre, Descripcion = :Descripcion, Peso = :Peso, Stock = :Stock, Categoria = :Categoria
                WHERE CodProd = :CodProd";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($producto->toDbParams());
    }

    public static function deleteProducto(string $id): void {
        $sql = "DELETE FROM productos WHERE CodProd = :id";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}
