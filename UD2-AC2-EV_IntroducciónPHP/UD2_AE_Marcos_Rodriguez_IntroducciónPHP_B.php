<?php 
// B) Genera una estructura para que haya al menos una clave cuyo valor represente uno de estos tipos de datos:

define("NUMERO_LIBROS", 10);

function generarDato($tipo){
	switch ($tipo) {
		case 'cadena':
			$cadena = "";
			for ($i=0; $i < rand(1, 10); $i++) { 
				$char = chr(rand(97, 122));
				$cadena .= $char;
			}
			return $cadena;
			break;
		case 'entero':
			return rand(100, 99999999);
			break;
		case 'decimal':
			$num= rand(1, 999) + (rand(0, 99999) / 100000);
			return $num;
			break;
		case 'fecha':
			return rand(1, 28)."/".rand(1, 9)."/"."2025";
			break;
		default:
			// code...
			break;
	}
}
$catalogo = array();
for ($i=0; $i < NUMERO_LIBROS; $i++) { 
	$libro = array(
    "titulo" => generarDato("cadena"),
    "n_paginas" => generarDato("entero"),
    "precio" => generarDato("decimal"),
    "fecha_publicacion" => generarDato("fecha"),
);
	$catalogo[] = $libro;
}

foreach ($catalogo as $libro) {
	foreach ($libro as $key => $value) {
		echo "$key : $value <br>";
	}
	echo "<br>";
}