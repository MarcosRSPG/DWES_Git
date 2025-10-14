<?php

require_once './generadorVideojuegos.php';
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

$hobbies[$i]->destroy();
unset($hobbies[2]);
$hobbies = array_values($hobbies);
foreach ($hobbies as $i => $juego) {
    echo 'Juego #'.($i + 1).'<br>';
    echo $juego;
    echo '<br>';
}
echo 'IVA: '.$hobbies[0]::IVA;
echo '<br>';
echo 'Videojuegos Creados: '.$hobbies[0]::$totalJuegosCreados;
echo '<br>';
$hobbies[0]::$totalJuegosCreados = 42;
echo 'Videojuegos creados actuales: '.$hobbies[0]::$totalJuegosCreados;
