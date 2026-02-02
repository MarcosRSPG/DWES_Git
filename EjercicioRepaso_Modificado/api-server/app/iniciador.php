<?php

/**
 * Iniciador de la aplicación API
 * Carga configuración y prepara el entorno.
 */

// Cargar configuración
require_once __DIR__.'/config/config.php';

// Configurar reporte de errores según modo debug
if (defined('DEBUG_MODE') && DEBUG_MODE) {
    // Modo desarrollo: mostrar todos los errores
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    // Modo producción: ocultar errores (solo loguear)
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL);
    ini_set('log_errors', '1');
    ini_set('error_log', RUTA_APP.'/logs/php_errors.log');
}

// Configurar zona horaria
date_default_timezone_set(TIMEZONE);

// Iniciar sesión (para autenticación de restaurantes)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Headers CORS (Cross-Origin Resource Sharing)
// Permite que el cliente web (en otro puerto/dominio) consuma la API
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Session-Token');

// Manejar preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
