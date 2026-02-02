<?php

//Configuración acceso a base de datos
define('DB_HOST', 'localhost'); //tu servidor de BD.
define('DB_USUARIO', 'root');
define('DB_PASSWORD', 'mysql');
define('DB_NOMBRE', 'test'); // Tu base de datos



//Ruta de la aplicación. /app o /src
define('RUTA_APP', (dirname(__DIR__)));

//Ruta url Ejemplo: http://localhost/ud5/mvc2app
//define ('RUTA_URL', '_URL_');
define ('RUTA_URL', 'http://localhost/DWES/ud6/mvcapi/public');

//define ('NOMBRESITIO', '_NOMBRE_SITIO');
define ('NOMBRESITIO', 'MVC con Composer y API RESTful');

// Cargar archivo INI si es necesario.
//$config = parse_ini_file(RUTA_APP . '/config/config.ini', true);

// Otras configuraciones iniciales pueden ir aquí

// Credenciales Basic Auth (didácticas)
// IMPORTANTE: en producción siempre HTTPS + usuarios reales
define('API_BASIC_USER', 'profesor');
define('API_BASIC_PASS', '1234');

