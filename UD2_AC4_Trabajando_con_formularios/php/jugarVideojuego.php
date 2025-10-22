<?php

require_once './php/Acciones.php';
require_once './php/Hobby.php';
class jugarVideojuegos extends Hobby implements Acciones
{
    public const IVA = 0.21;
    public static $totalJuegosCreados = 0;
    public $tiempo = 0;
    public const MINTIEMPO = 2;
    public const MAXTIEMPO = 10;
    private $genero;
    private $plataforma;
    private $fechaLanzamiento;
    private $precio;
    private array $actions = [];
    private array $extras = [];

    public function __construct($name = null, $plataforma = null, $genero = null, $fechaLanzamiento = null, $precio = null)
    {
        parent::__construct($name);
        $this->genero = $genero;
        $this->plataforma = $plataforma;
        $this->precio = $precio;
        $this->fechaLanzamiento = $fechaLanzamiento;

        ++self::$totalJuegosCreados;
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

    public function __get(string $name)
    {
        if (in_array($name, array_keys(self::$extras), true)) {
            return $this->extras[$name];
        }

        return null;
    }

    public function __isset(string $name): bool
    {
        return in_array($name, array_keys(self::$extras), true);
    }

    public function __set(string $name, $value): void
    {
        if (in_array($name, array_keys(self::$extras), true)) {
            $this->extras[$name] = $value;

            return;
        }
        throw new LogicException("Atributo {$name} ya existe");
    }

    public function iniciar($tiempo)
    {
        $this->tiempo += $tiempo;
        echo ($this->tiempo > self::MAXTIEMPO)
            ? 'Has jugado demasiado tiempo ya'
            : (($this->tiempo < self::MINTIEMPO)
                ? 'Debes jugar más'
                : 'Iniciando videojuego '.$this->getName().
                  ' para '.$this->getPlataforma().
                  ' del tipo '.$this->getGenero().
                  ' lanzado el '.$this->getFechaLanzamiento().
                  ' con un precio de '.$this->getPrecio().'€.');
    }

    public function detener()
    {
        echo 'Deteniendo videojuego '.$this->getName().' para '.$this->getPlataforma().' del tipo '.$this->getGenero().' lanzado el '.$this->getFechaLanzamiento().' con un precio de '.$this->getPrecio().'€.';
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
        if (isset($this->extras) && !empty($this->extras)) {
            if (!empty($extras)) {
                $out[] = 'Extras:';
                foreach ($extras as $k => $v) {
                    $out[] = '  - '.$k.': '.(is_bool($v) ? ($v ? 'Sí' : 'No') : (string) $v);
                }
            }
        }

        $out[] = str_repeat('-', 40);
        $out[] = '';

        return implode('<br>', $out);
    }

    public function destroy(): void
    {
        $this->actions = [];
        if (property_exists($this, 'extras')) {
            $this->extras = [];
        }

        $this->genero = null;
        $this->plataforma = null;
        $this->fechaLanzamiento = null;
        $this->precio = null;
        $this->name = null;
    }

    public function __destruct()
    {
        $this->destroy();
    }

    public function cambiarEstatico()
    {
        $this->totalJuegosCreados = 0;
    }
}
