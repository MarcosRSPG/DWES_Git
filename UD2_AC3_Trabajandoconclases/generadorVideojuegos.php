<?php

require './jugarVideojuego.php';

class Ayuda
{
    private function generarCadena(): string
    {
        $cadena = '';
        $len = random_int(1, 10);
        for ($i = 0; $i < $len; ++$i) {
            $char = chr(random_int(97, 122)); // a-z
            $cadena .= $char;
        }

        return $cadena;
    }

    private function generarEntero(): int
    {
        return random_int(100, 99999999);
    }

    private function generarDecimal(): float
    {
        $num = random_int(1, 999) + (random_int(0, 99) / 100);

        return (float) $num;
    }

    private function generarFecha(): int
    {
        $inicio = strtotime('2025-01-01');
        $fin = strtotime('2025-12-31');

        return random_int($inicio, $fin);
    }

    private function generarDato(string $tipo)
    {
        return match ($tipo) {
            'cadena' => $this->generarCadena(),
            'entero' => $this->generarEntero(),
            'decimal' => $this->generarDecimal(),
            'fecha' => $this->generarFecha(),
            default => null,
        };
    }

    public static function generarJugarVideojuegos($num)
    {
        $catalogo = [];
        for ($i = 0; $i < $num; ++$i) {
            $catalogo[] = new jugarVideojuegos(generarDato('cadena'), generarDato('cadena'), generarDato('cadena'), generarDato('fecha'), generarDato('decimal'));
        }

        return $catalogo;
    }
}
