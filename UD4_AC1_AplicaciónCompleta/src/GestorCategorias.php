<?php
namespace Mrs\Restaurante;

require_once __DIR__ . '/../vendor/autoload.php';

use Mrs\tools\Conexion;
use PDO;

class GestorCategorias
{
    private static ?PDO $pdo = null;

    private static function pdo(): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = Conexion::getConexion();
        }
        return self::$pdo;
    }


    public static function getCategorias(): array
    {
        $sql = "SELECT CodCat, Nombre, Descripcion FROM categorias ORDER BY Nombre";
        return self::pdo()->query($sql)->fetchAll();
    }


    public static function getCategoria(string $codCat): ?array
    {
        $sql = "SELECT CodCat, Nombre, Descripcion FROM categorias WHERE CodCat = :id LIMIT 1";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute(['id' => $codCat]);
        $row = $stmt->fetch();
        return $row ?: null;
    }


    public static function getCategoriaPorNombre(string $nombre): ?array
    {
        $sql = "SELECT CodCat, Nombre, Descripcion FROM categorias WHERE Nombre = :n LIMIT 1";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute(['n' => $nombre]);
        $row = $stmt->fetch();
        return $row ?: null;
    }


    public static function insertCategoria(Categoria $categoria): void
    {
        $sql = "INSERT INTO categorias (CodCat, Nombre, Descripcion)
                VALUES (:CodCat, :Nombre, :Descripcion)";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($categoria->toDbParams());
    }


    public static function updateCategoria(Categoria $categoria): void
    {
        $sql = "UPDATE categorias
                SET Nombre = :Nombre, Descripcion = :Descripcion
                WHERE CodCat = :CodCat";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($categoria->toDbParams());
    }


    public static function deleteCategoria(string $id): void
    {
        $sql = "DELETE FROM categorias WHERE CodCat = :id";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}
