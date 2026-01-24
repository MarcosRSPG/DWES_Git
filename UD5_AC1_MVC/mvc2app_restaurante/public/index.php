<?php

declare(strict_types=1);

require_once dirname(__DIR__).'/vendor/autoload.php';

session_start();

if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $v) {
        if ($v instanceof __PHP_Incomplete_Class) {
            unset($_SESSION['carrito']);
            break;
        }
    }
}

require_once dirname(__DIR__).'/app/iniciador.php';

new MRS\Librerias\Core();
