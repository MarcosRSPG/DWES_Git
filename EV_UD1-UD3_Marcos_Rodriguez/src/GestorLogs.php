<?php

require_once __DIR__.'/../tools/Conexion.php';
require_once __DIR__.'/logs.php';

class GestorLogs
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexion::getConexion();
    }

    public function insertar(string $accion)
    {
        try {
            $sql = 'INSERT INTO logs (accion)
                    VALUES (:accion)';

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':accion' => $accion,
            ]);

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception('Error al insertar log: '.$e->getMessage());
        }
    }

    public function eliminar(int $id)
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM logs WHERE id = :id');
            $stmt->execute([':id' => $id]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception('Error al eliminar log: '.$e->getMessage());
        }
    }

    public function actualizar(int $id, string $accion)
    {
        try {
            $sql = 'UPDATE logs
                    SET accion = :accion,
                    WHERE id = :id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':accion' => $accion,
            ]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception('Error al actualizar log: '.$e->getMessage());
        }
    }

    public function listar(): array
    {
        try {
            $stmt = $this->pdo->query('SELECT * FROM logs ORDER BY id');
            $resultado = [];
            while ($fila = $stmt->fetch()) {
                $resultado[]= Logs::fromArray($fila);
            }

            return $resultado;
        } catch (PDOException $e) {
            throw new Exception('Error al listar logs: '.$e->getMessage());
        }
    }

    public function obtenerPorId(int $id)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM logs WHERE id = :id');
            $stmt->execute([':id' => $id]);
            $fila = $stmt->fetch();
            if ($fila) {
                return Logs::fromArray($fila);
            }

            return null;
        } catch (PDOException $e) {
            throw new Exception('Error al obtener log: '.$e->getMessage());
        }
    }

    private function altaSimultanea(array $registros)
    {
        try {
            $this->pdo->beginTransaction();

            $sql = 'INSERT INTO logs (accion)
                    VALUES (:accion)';
            $stmt = $this->pdo->prepare($sql);

            foreach ($registros as $accion) {
                $stmt->execute([
                    ':accion' => $accion,
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
