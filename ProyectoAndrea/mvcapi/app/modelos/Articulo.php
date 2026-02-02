<?php
namespace Cls\Mvc2app;

use Cls\Mvc2app\Controlador;
use Cls\Mvc2app\Db;
use PDO;

class Articulo{ 
    private $bd;

    private $titulo;
    private $id_articulo;

    public function __construct()
    {
        $this->bd = new Db();
        $this->titulo = '';
        $this->id_articulo = '';

    }

    public function getAll(){
        $this->bd->query("SELECT * FROM articulos");
        return $this->bd->registros();
    }

    public function getById($num_registro){
        $this->bd->query("SELECT * FROM articulos WHERE id_articulo = :id");
        $this->bd->bind(':id', $num_registro, PDO::PARAM_INT);
        return $this->bd->registro();
    }

    public function create(array $data): bool {
        $this->bd->query("INSERT INTO articulos (titulo) VALUES (:titulo)");
        $this->bd->bind(':titulo', $data['titulo']);
        return $this->bd->execute();
    }

    public function delete(int $id): bool {
        $this->bd->query("DELETE FROM articulos WHERE id_articulo = :id");
        $this->bd->bind(':id', $id, PDO::PARAM_INT);
        return $this->bd->execute();
    }

    public function update(int $id, array $data): bool {
        $this->bd->query("UPDATE articulos SET titulo = :titulo WHERE id_articulo = :id");
        $this->bd->bind(':titulo', $data['titulo']);
        $this->bd->bind(':id', $id);
        return $this->bd->execute();
    }

}
