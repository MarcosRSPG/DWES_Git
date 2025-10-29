<?php

if (!isset($_COOKIE['visitas'])) {
    setcookie('visitas', 1, time() + 3600);
    echo '<h1>BIENVENIDO</h1>';
} else {
    $visitas = (int) $_COOKIE['visitas'];
    if ($visitas < 10) {
        ++$visitas;
        setcookie('visitas', $visitas, time() + 3600);
        echo '<h1>VISITA '.$visitas.'</h1>';
    } else {
        setcookie('visitas', '', time() - 3600);
        echo '<h2>Cookie eliminada. Reseteo el contador de visitas</h2>';
        exit;
    }
}
