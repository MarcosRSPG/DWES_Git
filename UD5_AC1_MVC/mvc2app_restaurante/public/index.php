<?php

declare(strict_types=1);

// 1) Composer SIEMPRE antes de session_start
require_once dirname(__DIR__).'/vendor/autoload.php';

// 2) Arrancar sesión (ya con autoload activo)
session_start();

// 3) Si quedó un carrito corrupto de antes, lo limpiamos
if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $v) {
        if ($v instanceof __PHP_Incomplete_Class) {
            unset($_SESSION['carrito']);
            break;
        }
    }
}

// 4) Iniciador y Core
require_once dirname(__DIR__).'/app/iniciador.php';

new MRS\Librerias\Core();
