<?php

/***********************
 * CONFIGURACIÓN CLIENTE (mvccurl)
 ***********************/

// Ruta de la aplicación (app)
define('RUTA_APP', (dirname(__DIR__)));

// Ruta base de ESTE proyecto (mvccurl) para generar enlaces en vistas
// Ajusta a la carpeta real del cliente:
define('RUTA_URL', 'http://localhost/mvccurl');

define('NOMBRESITIO', 'MVC Cliente (cURL) - Consumidor API');

// ---- Config del servidor API (mvcapi) ----
define('API_BASE_URL', 'http://localhost/mvcapi');

// Credenciales Basic Auth (didácticas)
// IMPORTANTE: en producción siempre HTTPS + usuarios reales
define('API_BASIC_USER', 'api');
define('API_BASIC_PASS', '1234');

// ---- Rutas (endpoints) del recurso Cars en la API ----
// Las dejamos como paths relativos para componer con API_SERVER_BASE_URL
define('API_CARS_LIST',  '/api/cars');      // GET (lista) / POST (crear)
define('API_CAR_ITEM',  '/api/car/%d');    // GET/PUT/DELETE por id
define('API_ART_LIST',  '/api/articulos');      // GET (lista) / POST (crear)
define('API_ART_ITEM',  '/api/articulo/%d');    // GET/PUT/DELETE por id

// Opcional: timeouts cURL (evita bloqueos en clase)
define('API_CONNECT_TIMEOUT', 5);
define('API_TIMEOUT', 10);
