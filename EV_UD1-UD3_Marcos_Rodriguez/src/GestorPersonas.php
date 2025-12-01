<?php

require_once __DIR__.'/../tools/Conexion.php';
require_once __DIR__.'/persona.php';

class GestorPersonas
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexion::getConexion();
    }

    public function insertar(array $datos)
    {
        try {
            $sql = 'INSERT INTO personas (nombre, apellido, email, fecha_registro)
                    VALUES (:nombre, :apellido, :email, :fecha_registro)';

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => $datos['nombre'],
                ':apellido' => $datos['apellido'] ?? null,
                ':email' => $datos['email'] ?? null,
                ':fecha_registro' => $datos['fecha_registro'] ?? null,
            ]);

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception('Error al insertar videojuego: '.$e->getMessage());
        }
    }

    public function eliminar(int $id)
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM personas WHERE id = :id');
            $stmt->execute([':id' => $id]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception('Error al eliminar videojuego: '.$e->getMessage());
        }
    }

    public function actualizar(int $id, array $datos)
    {
        try {
            $sql = 'UPDATE personas
                    SET nombre = :nombre,
                        apellido = :apellido,
                        email = :email,
                        fecha_registro = :fecha_registro
                    WHERE id = :id';

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':nombre' => $datos['nombre'],
                ':apellido' => $datos['apellido'] ?? null,
                ':email' => $datos['email'] ?? null,
                ':fecha_registro' => $datos['fecha_registro'] ?? null,
            ]);

            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception('Error al actualizar videojuego: '.$e->getMessage());
        }
    }

    public function listar(): array
    {
        try {
            $stmt = $this->pdo->query('SELECT * FROM personas');
            $resultado = [];
            while ($fila = $stmt->fetch()) {
                $resultado[] = Persona::fromArray($fila);

            }
            
            return $resultado;
        } catch (PDOException $e) {
            throw new Exception('Error al listar personas: '.$e->getMessage());
        }
    }

    public function obtenerPorId(int $id)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM personas WHERE id = :id');
            $stmt->execute([':id' => $id]);
            $fila = $stmt->fetch();
            if ($fila) {
                return Persona::fromArray($fila);
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

            $sql = 'INSERT INTO personas (nombre, apellido, email, fecha_registro)
                    VALUES (:nombre, :apellido, :email, :fecha_registro)';
            $stmt = $this->pdo->prepare($sql);

            foreach ($registros as $datos) {
                $stmt->execute([
                    ':nombre' => $datos['nombre'],
                    ':apellido' => $datos['apellido'] ?? null,
                    ':email' => $datos['email'] ?? null,
                    ':fecha_registro' => $datos['fecha_registro'] ?? null,
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
