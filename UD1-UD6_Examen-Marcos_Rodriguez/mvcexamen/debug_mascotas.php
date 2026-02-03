<?php

require_once 'app/iniciador.php';

use Mrs\Webcliente\ClienteAPI;

$cliente = new ClienteAPI();
$respuesta = $cliente->get('controladormascotas/mascotas');

echo '<h2>Respuesta completa de la API:</h2>';
echo '<pre>';
print_r($respuesta);
echo '</pre>';

echo '<h2>Estructura de $respuesta:</h2>';
echo '<pre>';
var_dump($respuesta);
echo '</pre>';

if (isset($respuesta['data'])) {
    echo "<h2>Contenido de \$respuesta['data']:</h2>";
    echo '<pre>';
    print_r($respuesta['data']);
    echo '</pre>';
}

if (isset($respuesta['data']['mascotas'])) {
    echo "<h2>Contenido de \$respuesta['data']['mascotas']:</h2>";
    echo '<pre>';
    print_r($respuesta['data']['mascotas']);
    echo '</pre>';
}
