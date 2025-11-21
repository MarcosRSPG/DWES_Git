<?php

require_once __DIR__.'/Hobby.php';

class JugarVideojuego extends Hobby
{
    private $id;
    private $genero;
    private $plataforma;
    private $fechaLanzamiento;
    private $precio;
    private $jugado;

    public function __construct(
        $id = null,
        $name = null,
        $genero = null,
        $plataforma = null,
        $fechaLanzamiento = null,
        $precio = null,
        $jugado = false
    ) {
        parent::__construct($name);
        $this->id = $id;
        $this->genero = $genero;
        $this->plataforma = $plataforma;
        $this->fechaLanzamiento = $fechaLanzamiento;
        $this->precio = $precio;
        $this->jugado = (bool) $jugado;
    }

    public static function fromArray(array $fila)
    {
        return new self(
            $fila['id'] ?? null,
            $fila['name'] ?? null,
            $fila['genero'] ?? null,
            $fila['plataforma'] ?? null,
            $fila['fecha_lanzamiento'] ?? null,
            $fila['precio'] ?? null,
            $fila['jugado'] ?? false
        );
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->getName(),
            'genero' => $this->genero,
            'plataforma' => $this->plataforma,
            'fecha_lanzamiento' => $this->fechaLanzamiento,
            'precio' => $this->precio,
            'jugado' => $this->jugado ? 1 : 0,
        ];
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getGenero()
    {
        return $this->genero;
    }

    public function setGenero($genero)
    {
        $this->genero = $genero;
    }

    public function getPlataforma()
    {
        return $this->plataforma;
    }

    public function setPlataforma($plataforma)
    {
        $this->plataforma = $plataforma;
    }

    public function getFechaLanzamiento()
    {
        return $this->fechaLanzamiento;
    }

    public function setFechaLanzamiento($fechaLanzamiento)
    {
        $this->fechaLanzamiento = $fechaLanzamiento;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    public function getJugado()
    {
        return $this->jugado;
    }

    public function setJugado($jugado)
    {
        $this->jugado = (bool) $jugado;
    }
}
