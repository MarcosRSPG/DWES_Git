<?php

require_once './Hobby.php';
class jugarVideojuegos extends Hobby
{
    private $id;
    private $genero;
    private $plataforma;
    private $fechaLanzamiento;
    private $precio;
    private $jugado;

    public function __construct($name = null, $plataforma = null, $genero = null, $fechaLanzamiento = null, $precio = null, $jugado = false)
    {
        parent::__construct($name);
        $this->genero = $genero;
        $this->plataforma = $plataforma;
        $this->precio = $precio;
        $this->fechaLanzamiento = $fechaLanzamiento;
        $this->jugado = $jugado;
    }

    public function getName()
    {
        return $this->name;
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

    public function getJugado()
    {
        return $this->jugado;
    }

    public function setJugado($jugado)
    {
        $this->jugado = $jugado;
    }

    public function __toString(): string
    {
        $out[] = "Título: {$this->getName()}";
        $out[] = "Plataforma: {$this->getPlataforma()}";
        $out[] = "Género: {$this->getGenero()}";
        $fecha = is_numeric($this->getFechaLanzamiento())
            ? date('Y-m-d', (int) $this->getFechaLanzamiento())
            : (string) $this->getFechaLanzamiento();
        $out[] = "Lanzamiento: {$fecha}";
        $precio = is_numeric($this->getPrecio())
            ? number_format((float) $this->getPrecio(), 2, ',', '.').'€'
            : (string) $this->getPrecio();
        $out[] = "Precio: {$precio}";
        $out[] = 'Jugado: '.$this->jugado ? 'Si' : 'No';

        $out[] = str_repeat('-', 40);
        $out[] = '';

        return implode('<br>', $out);
    }
}
