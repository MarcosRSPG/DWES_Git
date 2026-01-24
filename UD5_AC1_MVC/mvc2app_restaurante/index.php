<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';
session_start();

if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        if ($item instanceof __PHP_Incomplete_Class) {
            unset($_SESSION['carrito']);
            break;
        }
    }
}

require_once __DIR__.'/app/iniciador.php';

$iniciar = new MRS\Librerias\Core();
