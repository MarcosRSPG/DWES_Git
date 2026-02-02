<?php
namespace Cls\Mvc2app;

use Cls\Mvc2app\Controlador;
use Cls\Mvc2app\Db;
use PDO;

class Articulo{ 
    private $bd;

    private $id_articulo;
    private $titulo;
    private $descripcion;
    private $foto;
    private $cosa;

    public function __construct()
    {
        $this->bd = new Db();
        $this->id_articulo = '';
        $this->titulo = '';
        $this->descripcion = '';
        $this->foto = '';
        $this->cosa = '';
    }

    public function obtenerArticulos(){
        $this->bd->query("SELECT * FROM articulos");
        return $this->bd->registros();
    }

    public function obtenerArticulo($num_registro){
        $this->bd->query("SELECT * FROM articulos WHERE id_articulo = :id");
        $this->bd->bind(':id', $num_registro, PDO::PARAM_INT);
        return $this->bd->registro();
    }

    public function create(array $data): bool {
        $this->bd->query(
            "INSERT INTO articulos (titulo, descripcion, foto, cosa)
         VALUES (:titulo, :descripcion, :foto, :cosa)"
        );
        $this->bd->bind(':titulo', $data['titulo']);
        $this->bd->bind(':descripcion', $data['descripcion']);
        $this->bd->bind(':foto', $data['foto']);
        $this->bd->bind(':cosa', $data['cosa']);
        return $this->bd->execute();
    }

    public function update(int $id, array $data): bool {
        $this->bd->query(
            "UPDATE articulos 
         SET titulo = :titulo, descripcion = :descripcion, foto = :foto, cosa = :cosa
         WHERE id_articulo = :id"
        );
        $this->bd->bind(':titulo', $data['titulo']);
        $this->bd->bind(':descripcion', $data['descripcion']);
        $this->bd->bind(':foto', $data['foto']);
        $this->bd->bind(':cosa', $data['cosa']);
        $this->bd->bind(':id', $id);
        return $this->bd->execute();
    }

    public function delete(int $id): bool {
        $this->bd->query("DELETE FROM articulos WHERE id_articulo = :id");
        $this->bd->bind(':id', $id);
        return $this->bd->execute();
    }

}
