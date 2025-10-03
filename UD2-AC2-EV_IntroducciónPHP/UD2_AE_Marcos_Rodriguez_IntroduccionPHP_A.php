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

$numero = "hola";
try {
	if (!is_numeric($numero)) {
		throw new Exception("El valor $numero no es un número");
	}
	if (comprobarPrimo((int)$numero) && $numero > 1) {
	echo "El número $numero es primo";
}else{
	echo "El número $numero no es primo";
}
$numero++;
if (comprobarPrimo((int)$numero)) {
	echo "<br>El número $numero es primo";
}else{
	echo "<br>El número $numero tampoco es primo";
}
} catch (Exception $e) {
	echo "Error: ".$e->getMessage();
	exit();
}
