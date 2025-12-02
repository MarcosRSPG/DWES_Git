<?php

$alfabeto = range('a', 'z');
$nombreFichero = 'alfabeto.txt';

$f = fopen($nombreFichero, 'w');

$contador = 0;
foreach ($alfabeto as $letra) {
    ++$contador;

    fwrite($f, $letra);

    if ($contador % 5 == 0) {
        fwrite($f, PHP_EOL);
    } else {
        fwrite($f, ' ');
    }
}

fclose($f);

$f = fopen($nombreFichero, 'r');

if (!is_dir('letras')) {
    mkdir('letras');
}
if (!is_dir('copiasletras')) {
    mkdir('copiasletras');
}

while (($datos = fscanf($f, '%s%s%s%s%s')) !== false) {
    foreach ($datos as $letra) {
        if (is_null($letra)) {
            continue;
        }
        $archivoLetra = "letras/$letra.txt";
        $fl = fopen($archivoLetra, 'w');
        fwrite($fl, $letra);
        fclose($fl);
    }
}

rewind($f);

while (($datos = fscanf($f, '%s%s%s%s%s')) !== false) {
    foreach ($datos as $letra) {
        if (is_null($letra)) {
            continue;
        }
        $origen = "letras/$letra.txt";
        $destino = "copiasletras/$letra.txt";

        if (file_exists($origen)) {
            copy($origen, $destino);
        }
    }
}

fclose($f);
