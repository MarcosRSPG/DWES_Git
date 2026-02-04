<?php

declare(strict_types=1);

namespace Mrs\Mvcapuntes\Librerias;

class Db
{
    private \PDO $dbh;
    private $stmt;

    public function __construct()
    {
        $dsn = 'mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NOMBRE.';charset='.DB_CHARSET;

        $opciones = [
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ];

        try {
            $this->dbh = new \PDO($dsn, DB_USUARIO, DB_PASSWORD, $opciones);
        } catch (\PDOException $e) {
            http_response_code(500);
            exit('Error DB: '.$e->getMessage());
        }
    }

    public function query(string $sql): self
    {
        $this->stmt = $this->dbh->prepare($sql);

        return $this;
    }

    public function bind(string $parametro, mixed $valor, ?int $tipo = null): self
    {
        if ($tipo === null) {
            $tipo = match (true) {
                is_int($valor) => \PDO::PARAM_INT,
                is_bool($valor) => \PDO::PARAM_BOOL,
                is_null($valor) => \PDO::PARAM_NULL,
                default => \PDO::PARAM_STR,
            };
        }

        $this->stmt->bindValue($parametro, $valor, $tipo);

        return $this;
    }

    public function execute(): bool
    {
        return $this->stmt->execute();
    }

    public function registros(): array
    {
        $this->execute();

        return $this->stmt->fetchAll();
    }

    public function registro(): array|false
    {
        $this->execute();

        return $this->stmt->fetch();
    }

    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    }

    public function beginTransaction(): bool
    {
        return $this->dbh->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->dbh->commit();
    }

    public function rollBack(): bool
    {
        return $this->dbh->rollBack();
    }

    public function pdo(): \PDO
    {
        return $this->dbh;
    }
}
