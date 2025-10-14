<?php

require_once './jugarVideojuego.php';
require_once './andar.php';
class Ayuda
{
    private static function generarCadena(): string
    {
        $cadena = '';
        $len = random_int(1, 10);
        for ($i = 0; $i < $len; ++$i) {
            $char = chr(random_int(97, 122)); // a-z
            $cadena .= $char;
        }

        return $cadena;
    }

    private static function generarEntero(): int
    {
        return random_int(100, 99999999);
    }

    private static function generarDecimal(): float
    {
        $num = random_int(1, 999) + (random_int(0, 99) / 100);

        return (float) $num;
    }

    private static function generarFecha(): int
    {
        $inicio = strtotime('2025-01-01');
        $fin = strtotime('2025-12-31');

        return random_int($inicio, $fin);
    }

    private static function generarDato(string $tipo)
    {
        return match ($tipo) {
            'cadena' => self::generarCadena(),
            'entero' => self::generarEntero(),
            'decimal' => self::generarDecimal(),
            'fecha' => date('d/m/Y', self::generarFecha()),
            default => null,
        };
    }

    public static function generarJugarVideojuegos($num)
    {
        $catalogo = [];
        for ($i = 0; $i < $num; ++$i) {
            $catalogo[] = new jugarVideojuegos(self::generarDato('cadena'), self::generarDato('cadena'), self::generarDato('cadena'), self::generarDato('fecha'), self::generarDato('decimal'));
        }

        return $catalogo;
    }

    public static function generarRuta($num)
    {
        $rutas = [];
        for ($i = 0; $i < $num; ++$i) {
            $rutas[] = new Andar(self::generarDato('cadena'));
        }

        return $rutas;
    }
}
