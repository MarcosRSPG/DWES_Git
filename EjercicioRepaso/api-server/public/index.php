<?php

/**
 * Punto de entrada de la API REST
 * Todo el tráfico se redirige aquí mediante .htaccess.
 */

// Cargar autoloader de Composer
require_once __DIR__.'/../vendor/autoload.php';

// Cargar iniciador (configuración y setup)
require_once __DIR__.'/../app/iniciador.php';

// Importar el Core
use Mrs\ApiServer\librerias\Core;

// Iniciar el Core que enrutará la petición
$app = new Core();
