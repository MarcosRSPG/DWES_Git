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
define('DB_NOMBRE', 'gestorrestaurantes');
define('DB_CHARSET', 'utf8mb4');

// ===== RUTAS =====
define('RUTA_APP', dirname(dirname(__DIR__)));
define('RUTA_URL', 'http://mywww/EjercicioRepaso/api-server/');
define('NOMBRESITIO', 'API REST - Gestor Restaurantes');

// ===== AUTENTICACIÓN BASIC AUTH =====
define('API_BASIC_USER', 'admin');
define('API_BASIC_PASS', 'admin123');

// ===== SMTP =====
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USER', 'marcosrs.softwaredeveloper@gmail.com');
define('SMTP_PASS', 'bzky rjsw kogu ausw');
define('SMTP_FROM', 'marcosrs.softwaredeveloper@gmail.com');
define('SMTP_FROM_NAME', 'Gestor Restaurantes API');

// ===== CONFIGURACIÓN ADICIONAL =====
define('TIMEZONE', 'Europe/Madrid');
define('DEBUG_MODE', true);
