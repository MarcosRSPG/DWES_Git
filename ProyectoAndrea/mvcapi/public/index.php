<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Cls\Mvc2app\Core;

// --- CONFIGURACIÓN DE ERRORES PARA PRODUCCIÓN/EXAMEN ---
// Desactivamos la salida por pantalla (para que no rompa el JSON)
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Activamos el guardado en archivo (Log)
ini_set('log_errors', '1');
error_reporting(E_ALL);
// Ruta al archivo donde se guardarán los errores (ajústala a tu Ampps)
ini_set('error_log', __DIR__.'/../mislogs.txt');

// Cargamos el iniciador
require_once __DIR__.'/../app/iniciador.php';

// Arrancamos el Core
$iniciar = new Core();