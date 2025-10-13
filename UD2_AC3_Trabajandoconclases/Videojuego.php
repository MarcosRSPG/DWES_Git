<?php

require './Acciones.php';
require './Hobby.php';
class Videojuego extends Hobby implements Acciones
{
    private $genero;
    private $plataforma;
    private $fechaLanzamiento;
    private $precio;

    public function __construct($name, $plataforma, $genero, $fechaLanzamiento, $precio)
    {
        parent::__construct($name);
        $this->genero = $genero;
        $this->plataforma = $plataforma;
        $this->precio = $precio;
        $this->fechaLanzamiento = $fechaLanzamiento;
    }

    public function getName()
    {
        return parent::$name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPlataforma()
    {
        return $this->plataforma;
    }

    public function setPlataforma($plataforma)
    {
        $this->plataforma = $plataforma;
    }

    public function getGenero()
    {
        return $this->genero;
    }

    public function setGenero($genero)
    {
        $this->genero = $genero;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    public function getFechaLanzamiento()
    {
        return $this->fechaLanzamiento;
    }

    public function setFechaLanzamiento($fechaLanzamiento)
    {
        $this->fechaLanzamiento = $fechaLanzamiento;
    }

    public function iniciar()
    {
        echo 'Iniciando videojuego '.$this->getName().' para '.$this->getPlataforma().' del tipo '.$this->getGenero().' lanzado el '.$this->getFechaLanzamiento().' con un precio de '.$this->getPrecio().'€.';
    }

    public function detener()
    {
        echo 'Deteniendo videojuego '.$this->getName().' para '.$this->getPlataforma().' del tipo '.$this->getGenero().' lanzado el '.$this->getFechaLanzamiento().' con un precio de '.$this->getPrecio().'€.';
    }

    public function actualizar(array $a)
    {
    }
}
