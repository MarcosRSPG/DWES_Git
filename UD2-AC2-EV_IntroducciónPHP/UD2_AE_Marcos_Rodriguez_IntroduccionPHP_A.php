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

$numero = 27;

if (comprobarPrimo((int)$numero)) {
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