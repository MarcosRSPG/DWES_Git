<?php
declare(strict_types=1);

/**
 * Config del proyecto.
 * - Lee primero app/config/config.ini (si existe)
 * - Si no, usa variables de entorno o defaults
 * - Evita warnings si se incluye dos veces
 */

if (defined('RUTA_APP')) {
    return;
}

// Rutas
define('RUTA_APP', dirname(__DIR__));
define('RUTA_PUBLIC', dirname(RUTA_APP).'/public');
define('RUTA_URL', 'http://mywww/UD5_AC1_MVC/mvc2app_restaurante');
define('NOMBRESITIO', 'Restaurante');

// Cargar INI (opcional)
$iniPath = __DIR__.'/config.ini';
$ini = is_file($iniPath) ? (parse_ini_file($iniPath, true, INI_SCANNER_TYPED) ?: []) : [];

$db = $ini['database'] ?? [];
$smtp = $ini['smtp'] ?? [];

// DB (INI > ENV > default)
$dbHost = (string)($db['host'] ?? (getenv('DB_HOST') ?: '127.0.0.1'));
$dbUser = (string)($db['user'] ?? (getenv('DB_USUARIO') ?: 'root'));
$dbPass = (string)($db['pass'] ?? (getenv('DB_PASSWORD') ?: '')); // en AMPPS/XAMPP suele ser ''
$dbName = (string)($db['dbname'] ?? (getenv('DB_NOMBRE') ?: 'gestorrestaurantes'));
$dbPort = (int)($db['port'] ?? (getenv('DB_PORT') ?: 3306));
$dbCharset = (string)($db['charset'] ?? (getenv('DB_CHARSET') ?: 'utf8mb4'));

define('DB_HOST', $dbHost);
define('DB_USUARIO', $dbUser);
define('DB_PASSWORD', $dbPass);
define('DB_NOMBRE', $dbName);
define('DB_PORT', $dbPort);
define('DB_CHARSET', $dbCharset);

// SMTP (opcional)
define('SMTP_HOST', (string)($smtp['host'] ?? ''));
define('SMTP_PORT', (int)($smtp['port'] ?? 587));
define('SMTP_SECURE', (string)($smtp['secure'] ?? 'tls'));
define('SMTP_USER', (string)($smtp['user'] ?? ''));
define('SMTP_PASS', (string)($smtp['pass'] ?? ''));
define('SMTP_FROM', (string)($smtp['from'] ?? (string)($smtp['user'] ?? '')));
define('SMTP_FROM_NAME', (string)($smtp['from_name'] ?? 'Restaurante'));
