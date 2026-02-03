<?php

/**
 * Configuración de la API REST
 * Define constantes para BD, URLs, autenticación, SMTP, etc.
 */

// ===== BASE DE DATOS =====
define('DB_HOST', 'localhost');
define('DB_PORT', 8000);
define('DB_USUARIO', 'root');
define('DB_PASSWORD', 'rpwd');
define('DB_NOMBRE', 'examen');
define('DB_CHARSET', 'utf8mb4');

// ===== RUTAS =====
define('RUTA_APP', dirname(dirname(__DIR__)));
define('RUTA_URL', 'http://localhost/UD1-UD6_Examen-Marcos_Rodriguez/api-server/');
define('NOMBRESITIO', 'API REST - Gestor Veterinarios');

// ===== AUTENTICACIÓN BASIC AUTH =====
define('API_BASIC_USER', 'ZHdlcw==');
define('API_BASIC_PASS', 'ZHdlcw==');

// ===== CONFIGURACIÓN ADICIONAL =====
define('TIMEZONE', 'Europe/Madrid');
define('DEBUG_MODE', true);
