<?php

/**
 * Punto de entrada de la API REST
 * Todo el tráfico se redirige aquí mediante .htaccess.
 */

// Mostrar errores en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar autoloader de Composer
require_once __DIR__.'/../vendor/autoload.php';

// Cargar iniciador (configuración y setup)
require_once __DIR__.'/../app/iniciador.php';

// Importar el Core
use Mrs\ApiServer\librerias\Core;

try {
    // Iniciar el Core que enrutará la petición
    $app = new Core();
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'Error interno del servidor',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ], JSON_PRETTY_PRINT);
}
