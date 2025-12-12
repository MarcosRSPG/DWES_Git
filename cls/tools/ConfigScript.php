<?php
//Este script lo usaremos para leer las configuraciones de la aplicación
// Tambíen para ilustrar el manejo de archivos con PHP
function leer_config(){

echo "Iniciando".'<br>';
	//Así toma los datos de configuración sin secciones $config['driver']
	//$config = parse_ini_file(__DIR__."/../config/Config.ini");
	//Si le pasamos 'true', nos creará las secciones en array multidimensional asociativo
	$config = parse_ini_file(__DIR__."/../config/Config.ini", true);	
	echo "Error: ".$e->getMessage();
	die();


//print_r($config);
//echo "<hr>";

// Sacamos SOLO la parte de [database]
$db = $config['database'];
print_r($db);

$driver = $db['mysql'];   // mysql o pgsql
$host   = $db['localhost'];
$dbname = $db['nuevaDB'];
$port   = $db['4000'];
$user   = $db['root'];
$pass   = $db['rpwd'];

// DSN básico
$dsn = "$driver:host=$host;dbname=$dbname;port=$port";

echo "dsn: ".$dsn;

}








