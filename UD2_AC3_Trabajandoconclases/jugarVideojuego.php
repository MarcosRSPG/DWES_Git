<?php

require_once './Acciones.php';
require_once './Hobby.php';
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
    private static array $extraKeys = [];
    private array $extras = [];

    public function __construct($name, $plataforma, $genero, $fechaLanzamiento, $precio)
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

    public static function setExtraKeys(array $keys): void
    {
        self::$extraKeys = array_values(
            array_unique(
                array_map('strval', $keys)
            )
        );
    }

    public function __get(string $name)
    {
        if (in_array($name, self::$extraKeys, true)) {
            if (!array_key_exists($name, $this->extras)) {
                $this->extras[$name] = $this->fabricarValorExtra($name);
            }

            return $this->extras[$name];
        }
        trigger_error('Propiedad indefinida: '.static::class."::\${$name}", E_USER_NOTICE);

        return null;
    }

    public function __isset(string $name): bool
    {
        return in_array($name, self::$extraKeys, true) || isset($this->$name);
    }

    public function __set(string $name, $value): void
    {
        if (in_array($name, self::$extraKeys, true)) {
            $this->extras[$name] = $value;

            return;
        }
        throw new LogicException("Atributo no permitido: {$name}");
    }

    private function fabricarValorExtra(string $key)
    {
        switch ($key) {
            case 'horas':
                return random_int(1, 500);
            case 'dificultad':
                $opts = ['Fácil', 'Normal', 'Difícil', 'Soulslike'];

                return $opts[random_int(0, count($opts) - 1)];
            case 'coop':
                return (bool) random_int(0, 1);
            case 'dlc':
                return random_int(0, 12);
            default:
                return $this->randomStringExtra();
        }
    }

    private function randomStringExtra(): string
    {
        $len = random_int(5, 12);
        $s = '';
        for ($i = 0; $i < $len; ++$i) {
            $s .= chr(random_int(97, 122));
        }

        return $s;
    }

    public function iniciar($tiempo)
    {
        $this->tiempo = $tiempo;
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
        $fecha = is_numeric($this->getFechaLanzamiento())
            ? date('Y-m-d', (int) $this->getFechaLanzamiento())
            : (string) $this->getFechaLanzamiento();

        $precio = is_numeric($this->getPrecio())
            ? number_format((float) $this->getPrecio(), 2, ',', '.').'€'
            : (string) $this->getPrecio();

        $out = [];

        $out[] = "Título: {$this->getName()}";
        $out[] = "Plataforma: {$this->getPlataforma()}";
        $out[] = "Género: {$this->getGenero()}";
        $out[] = "Lanzamiento: {$fecha}";
        $out[] = "Precio: {$precio}";

        $out[] = "Horas (mágica): {$this->horas}";
        $out[] = "Dificultad (mágica): {$this->dificultad}";
        $out[] = 'Coop (mágica): '.($this->coop ? 'Sí' : 'No');
        $out[] = "DLCs (mágica): {$this->dlc}";

        $yaMostrados = ['horas', 'dificultad', 'coop', 'dlc'];

        if (isset($this->extras) && !empty($this->extras)) {
            $filtrados = array_diff_key($this->extras, array_flip($yaMostrados));
            if (!empty($filtrados)) {
                $out[] = 'Extras:';
                foreach ($filtrados as $k => $v) {
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
