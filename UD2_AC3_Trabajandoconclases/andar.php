<?php

require_once './Acciones.php';
require_once './Hobby.php';
class Andar extends Hobby implements Acciones
{
    public $tiempo = 0;
    public const MINTIEMPO = 30;
    public const MAXTIEMPO = 120;

    private array $actions = [];

    public function __construct($name)
    {
        parent::__construct($name);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function iniciar($tiempo)
    {
        $this->tiempo = $tiempo;
        echo ($this->tiempo > self::MAXTIEMPO)
            ? 'Has jugado demasiado tiempo ya'
            : (($this->tiempo < self::MINTIEMPO)
                ? 'Debes andar más'
                : 'Comenzando a andar la ruta '.$this->getName());
    }

    public function detener()
    {
        echo 'Parando de andar la ruta '.$this->getName();
        echo '<br>';
        echo 'Tiempo acumulado de '.$this->tiempo;
    }

    public function actualizar(array $a)
    {
        echo 'Actualizando actions: ';
        print_r($a);
        $this->actions = array_merge($this->actions, $a);
    }

    public function __toString(): string
    {
        $out = [];

        $out[] = "Título: {$this->getName()}";

        $out[] = str_repeat('-', 40);
        $out[] = '';

        return implode('<br>', $out);
    }

    public function destroy(): void
    {
        $this->name = null;
    }

    public function __destruct()
    {
        $this->destroy();
    }
}
