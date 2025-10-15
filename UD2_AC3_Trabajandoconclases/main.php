<?php

require_once './generadorHobbies.php';
require_once './jugarVideojuego.php';

define('NUM_HOBBIES', 15);

$GLOBALS['AMPLIACION_HOBBY_KEYS'] = ['horas', 'dificultad', 'coop', 'dlc'];

jugarVideojuegos::setExtraKeys($GLOBALS['AMPLIACION_HOBBY_KEYS']);

$hobbies = Ayuda::generarJugarVideojuegos(NUM_HOBBIES);

foreach ($hobbies as $i => $juego) {
    echo 'Juego #'.($i + 1).'<br>';
    echo $juego;
    echo '<br>';
}

$hobbies[2]->destroy();
unset($hobbies[2]);
$hobbies = array_values($hobbies);

echo 'IVA: '.$hobbies[0]::IVA;
echo '<br>';
echo 'Videojuegos Creados: '.$hobbies[0]::$totalJuegosCreados;
echo '<br>';
// $hobbies[0]::IVA = 8;      No se puede cambiar una constante
try {
    $hobbies[0]->cambiarEstatico();
} catch (Error $e) {
    echo 'No se puede cambiar un estático con un metodo de la clase';
}
echo '<br>';
$hobbies[0]->iniciar(5);
echo '<br>';
$hobbies[0]->iniciar(12);
echo '<br>';
echo '<br>';
$rutas = Ayuda::generarRuta(NUM_HOBBIES);
$rutas = array_values($rutas);
foreach ($rutas as $y => $ruta) {
    echo 'Ruta #'.($y + 1).'<br>';
    echo $ruta.'<br>';
}
