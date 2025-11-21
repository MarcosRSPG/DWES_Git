<?php

require_once __DIR__.'/../tools/Conexion.php';
require_once __DIR__.'/JugarVideojuego.php';

class GestorJugarVideojuego
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexion::getConexion();
    }

    public function insertar(array $datos)
    {
        try {
            $sql = 'INSERT INTO videojuegos (name, genero, plataforma, fecha_lanzamiento, precio, jugado)
                    VALUES (:name, :genero, :plataforma, :fecha_lanzamiento, :precio, :jugado)';

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':name' => $datos['name'],
                ':genero' => $datos['genero'] ?? null,
                ':plataforma' => $datos['plataforma'] ?? null,
                ':fecha_lanzamiento' => $datos['fecha_lanzamiento'] ?? null,
                ':precio' => $datos['precio'] ?? null,
                ':jugado' => !empty($datos['jugado']) ? 1 : 0,
            ]);

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception('Error al insertar videojuego: '.$e->getMessage());
        }
    }

    public function eliminar(int $id)
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM videojuegos WHERE id = :id');
            $stmt->execute([':id' => $id]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception('Error al eliminar videojuego: '.$e->getMessage());
        }
    }

    public function actualizar(int $id, array $datos)
    {
        try {
            $sql = 'UPDATE videojuegos
                    SET name = :name,
                        genero = :genero,
                        plataforma = :plataforma,
                        fecha_lanzamiento = :fecha_lanzamiento,
                        precio = :precio,
                        jugado = :jugado
                    WHERE id = :id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':name' => $datos['name'],
                ':genero' => $datos['genero'] ?? null,
                ':plataforma' => $datos['plataforma'] ?? null,
                ':fecha_lanzamiento' => $datos['fecha_lanzamiento'] ?? null,
                ':precio' => $datos['precio'] ?? null,
                ':jugado' => !empty($datos['jugado']) ? 1 : 0,
            ]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception('Error al actualizar videojuego: '.$e->getMessage());
        }
    }

    public function listar(): array
    {
        try {
            $stmt = $this->pdo->query('SELECT * FROM videojuegos ORDER BY id');
            $resultado = [];
            while ($fila = $stmt->fetch()) {
                $resultado[] = JugarVideojuego::fromArray($fila);
            }

            return $resultado;
        } catch (PDOException $e) {
            throw new Exception('Error al listar videojuegos: '.$e->getMessage());
        }
    }

    public function obtenerPorId(int $id)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM videojuegos WHERE id = :id');
            $stmt->execute([':id' => $id]);
            $fila = $stmt->fetch();
            if ($fila) {
                return JugarVideojuego::fromArray($fila);
            }

            return null;
        } catch (PDOException $e) {
            throw new Exception('Error al obtener videojuego: '.$e->getMessage());
        }
    }

    private function altaSimultanea(array $registros)
    {
        try {
            $this->pdo->beginTransaction();

            $sql = 'INSERT INTO videojuegos (name, genero, plataforma, fecha_lanzamiento, precio, jugado)
                    VALUES (:name, :genero, :plataforma, :fecha_lanzamiento, :precio, :jugado)';
            $stmt = $this->pdo->prepare($sql);

            foreach ($registros as $datos) {
                $stmt->execute([
                    ':name' => $datos['name'],
                    ':genero' => $datos['genero'] ?? null,
                    ':plataforma' => $datos['plataforma'] ?? null,
                    ':fecha_lanzamiento' => $datos['fecha_lanzamiento'] ?? null,
                    ':precio' => $datos['precio'] ?? null,
                    ':jugado' => !empty($datos['jugado']) ? 1 : 0,
                ]);
            }

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new Exception('Error en alta simultánea: '.$e->getMessage());
        }
    }

    public function ejecutarAltaSimultanea(array $registros)
    {
        $this->altaSimultanea($registros);
    }
}
