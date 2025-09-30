<?php 

// A) Crea un script que determine si un número no es primo y, además, si precede a otro que sí es primo.

function comprobarPrimo($num) {
	for ($i=$num - 1; $i > 1; $i--) { 
		if ($num%$i==0){
			return false;
		}
	}
	return true;
}

$numero = 14;

if (comprobarPrimo((int)$numero)) {
	echo "El número $numero es primo";
}else{
	echo "El número $numero no es primo";
	$numero--;
	if (comprobarPrimo((int)$numero)) {
		echo "<br>El número $numero es primo";
	}else{
		echo "<br>El número $numero tampoco es primo";
	}
}



// B) Genera una estructura para que haya al menos una clave cuyo valor represente uno de estos tipos de datos:

function generarDato($tipo){
	switch ($tipo) {
		case 'cadena':
			return 
			break;
		
		default:
			// code...
			break;
	}
}

$catalogo = array(
    "titulo" => "cadena",
    "n_paginas" => "entero",
    "precio" => "decimal",
    "fecha_publicacion" => "fecha");
