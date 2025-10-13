<?php

require './Videojuego.php';

define('NUMERO_LIBROS', random_int(0, 20));

function generarCadena(): string
{
    $cadena = '';
    $len = random_int(1, 10);
    for ($i = 0; $i < $len; ++$i) {
        $char = chr(random_int(97, 122)); // a-z
        $cadena .= $char;
    }

    return $cadena;
}

function generarEntero(): int
{
    return random_int(100, 99999999);
}

function generarDecimal(): float
{
    $num = random_int(1, 999) + (random_int(0, 99) / 100);

    return (float) $num;
}

function generarFecha(): int
{
    $inicio = strtotime('2025-01-01');
    $fin = strtotime('2025-12-31');

    return random_int($inicio, $fin);
}
function generarDato(string $tipo)
{
    return match ($tipo) {
        'cadena' => generarCadena(),
        'entero' => generarEntero(),
        'decimal' => generarDecimal(),
        'fecha' => generarFecha(),
        default => null,
    };
}
$catalogo = [];
for ($i = 0; $i < NUMERO_LIBROS; ++$i) {
    $catalogo[] = new Videojuego(generarDato('cadena'), generarDato('cadena'), generarDato('cadena'), generarDato('fecha'), generarDato('decimal'));
    print_r($catalogo[$i]);
    echo '<br>';
}
