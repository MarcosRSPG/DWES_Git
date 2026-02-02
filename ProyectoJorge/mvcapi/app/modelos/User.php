<?php
namespace Cls\Mvc2app;

use Cls\Mvc2app\Controlador;
use Cls\Mvc2app\Db;
use PDO;

class User{ 
    private $bd;

    private $id;
    private $nombre;
    private $clave;

    public function __construct()
    {
        $this->bd = new Db();
        $this->id = '';
        $this->nombre = '';
        $this->clave = '';
    }

    public function obtenerUsuarios(){
        $this->bd->query("SELECT * FROM users");
        return $this->bd->registros();
    }

    public function obtenerUser($num_registro){
        $this->bd->query("SELECT * FROM users WHERE id = :id");
        $this->bd->bind(':id', $num_registro, PDO::PARAM_INT);
        return $this->bd->registro();
    }

    public function loguearUser($nombre, $clave){
        $this->bd->query("SELECT * FROM users WHERE nombre = :nombre AND clave = :clave");
        $this->bd->bind(':nombre', $nombre);
        $this->bd->bind(':clave', $clave);
        return $this->bd->registro();
    }

    public function create(array $data): bool {
        $this->bd->query(
            "INSERT INTO users (nombre, clave)
         VALUES (:nombre, :clave)"
        );
        $this->bd->bind(':nombre', $data['nombre']);
        $this->bd->bind(':clave', $data['clave']);
        return $this->bd->execute();
    }

    public function update(int $id, array $data): bool {
        $this->bd->query(
            "UPDATE users 
         SET nombre = :nombre, clave = :clave
         WHERE id = :id"
        );
        $this->bd->bind(':nombre', $data['nombre']);
        $this->bd->bind(':clave', $data['clave']);
        $this->bd->bind(':id', $id);
        return $this->bd->execute();
    }

    public function delete(int $id): bool {
        $this->bd->query("DELETE FROM users WHERE id = :id");
        $this->bd->bind(':id', $id);
        return $this->bd->execute();
    }

}
