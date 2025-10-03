<?php

// B) Genera una estructura para que haya al menos una clave cuyo valor represente uno de estos tipos de datos:

define("NUMERO_LIBROS", 10);

function generarDato($tipo){
	switch ($tipo) {
		// B1)
		case 'cadena':
			$cadena = "";
			for ($i=0; $i < rand(1, 10); $i++) { 
				$char = chr(rand(97, 122));
				$cadena .= $char;
			}
			return $cadena;
			break;
		// B2)
		case 'entero':
			return rand(100, 99999999);
			break;
		// B3)
		case 'decimal':
			$num= rand(1, 999) + (rand(0, 99999) / 100000);
			return $num;
			break;
		// B4)
		case 'fecha':
			return mt_rand(strtotime("2025-01-01"), strtotime("2025-12-31"));
			break;
		default:
			break;
	}
}
$estructura = array(
	"titulo" => "cadena", 
	"n_paginas" => "entero", 
	"precio" => "decimal", 
	"fecha_publicacion"=> "fecha",
);
$catalogo = array();
for ($i=0; $i < NUMERO_LIBROS; $i++) { 
	$libro = array(
    "titulo" => generarDato($estructura["titulo"]),
    "n_paginas" => generarDato($estructura["n_paginas"]),
    "precio" => generarDato($estructura["precio"]),
    "fecha_publicacion" => generarDato($estructura["fecha_publicacion"]),
);
	$catalogo[] = $libro;
}
?>