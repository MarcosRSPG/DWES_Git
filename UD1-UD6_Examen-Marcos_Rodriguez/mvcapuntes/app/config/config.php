<?php

declare(strict_types=1);

if (defined('RUTA_APP')) {
    return;
}

define('RUTA_APP', dirname(__DIR__));
define('RUTA_PUBLIC', dirname(RUTA_APP).'/public');
define('RUTA_URL', 'http://mywww/UD1-UD6_Examen-Marcos_Rodriguez/mvcapuntes');
define('NOMBRESITIO', 'Veterinaria - Sistema de Gestión');

$iniPath = __DIR__.'/config.ini';
$ini = is_file($iniPath) ? (parse_ini_file($iniPath, true, INI_SCANNER_TYPED) ?: []) : [];

$db = $ini['database'] ?? [];

$dbHost = (string) ($db['host'] ?? (getenv('DB_HOST') ?: 'localhost'));
$dbUser = (string) ($db['user'] ?? (getenv('DB_USUARIO') ?: 'root'));
$dbPass = (string) ($db['pass'] ?? (getenv('DB_PASSWORD') ?: 'rpwd'));
$dbName = (string) ($db['dbname'] ?? (getenv('DB_NOMBRE') ?: 'examen'));
$dbPort = (int) ($db['port'] ?? (getenv('DB_PORT') ?: 8000));
$dbCharset = (string) ($db['charset'] ?? (getenv('DB_CHARSET') ?: 'utf8mb4'));

define('DB_HOST', $dbHost);
define('DB_USUARIO', $dbUser);
define('DB_PASSWORD', $dbPass);
define('DB_NOMBRE', $dbName);
define('DB_PORT', $dbPort);
define('DB_CHARSET', $dbCharset);
