<?php
declare(strict_types=1);

// Router para el servidor embebido de PHP (sin .htaccess).
// Si existe el archivo solicitado en /public, se sirve tal cual.
// Si no, se enruta a index.php (front controller).

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/');
$path = __DIR__ . $uri;

if ($uri !== '/' && is_file($path)) {
    return false; // servir archivo estático
}

require __DIR__ . '/index.php';
