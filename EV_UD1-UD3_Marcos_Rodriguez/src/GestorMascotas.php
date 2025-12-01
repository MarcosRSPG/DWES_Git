<?php

require_once __DIR__.'/../tools/Conexion.php';
require_once __DIR__.'/mascota.php';

class GestorMascotas
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexion::getConexion();
    }

    public function insertar(array $datos)
    {
        try {
            $sql = 'INSERT INTO mascotas (nombre, tipo, fecha_nacimiento, foto_url, id_persona)
                    VALUES (:nombre, :tipo, :fecha_nacimiento, :foto_url, :id_persona)';

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $datos['nombre'],
                ':tipo' => $datos['tipo'] ?? null,
                ':fecha_nacimiento' => $datos['fecha_nacimiento'] ?? null,
                ':foto_url' => $datos['foto_url'] ?? null,
                ':id_persona' => $datos['id_persona'] ?? null,
            ]);

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception('Error al insertar videojuego: '.$e->getMessage());
        }
    }

    public function eliminar(int $id)
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM mascotas WHERE id = :id');
            $stmt->execute([':id' => $id]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception('Error al eliminar videojuego: '.$e->getMessage());
        }
    }

    public function actualizar(int $id, array $datos)
    {
        try {
            $sql = 'UPDATE mascotas
                    SET nombre = :nombre,
                        tipo = :tipo,
                        fecha_nacimiento = :fecha_nacimiento,
                        foto_url = :foto_url,
                        id_persona = :id_persona,
                    WHERE id = :id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':nombre' => $datos['nombre'],
                ':tipo' => $datos['tipo'] ?? null,
                ':fecha_nacimiento' => $datos['fecha_nacimiento'] ?? null,
                ':foto_url' => $datos['foto_url'] ?? null,
                ':id_persona' => $datos['id_persona'] ?? null,
            ]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception('Error al actualizar videojuego: '.$e->getMessage());
        }
    }

    public function listar(): array
    {
        try {
            $stmt = $this->pdo->query('SELECT * FROM mascotas ORDER BY id');
            $resultado = [];
            while ($fila = $stmt->fetch()) {
                $newMascota= Mascota::fromArray($fila);
                $resultado[] = $newMascota->toArray();
            }

            return $resultado;
        } catch (PDOException $e) {
            throw new Exception('Error al listar mascotas: '.$e->getMessage());
        }
    }

    public function obtenerPorId(int $id)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM mascotas WHERE id = :id');
            $stmt->execute([':id' => $id]);
            $fila = $stmt->fetch();
            if ($fila) {
                return Mascota::fromArray($fila);
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

            $sql = 'INSERT INTO mascotas (nombre, tipo, fecha_nacimiento, foto_url, id_persona)
                    VALUES (:nombre, :tipo, :fecha_nacimiento, :foto_url, :id_persona)';
            $stmt = $this->pdo->prepare($sql);

            foreach ($registros as $datos) {
                $stmt->execute([
                    ':nombre' => $datos['nombre'],
                    ':tipo' => $datos['tipo'] ?? null,
                    ':fecha_nacimiento' => $datos['fecha_nacimiento'] ?? null,
                    ':foto_url' => $datos['foto_url'] ?? null,
                    ':id_persona' => $datos['id_persona'] ?? null,
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
