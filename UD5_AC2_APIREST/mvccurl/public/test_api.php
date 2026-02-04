<?php
/**
 * Script de depuración para mvccurl - muestra qué devuelve la API
 * Accede a: http://mywww/UD5_AC2_APIREST/mvccurl/public/test_api.php.
 */

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../app/config/config.php';

echo "<h1>Prueba de conexión a la API</h1>\n";

// Test 1: Verificar que cURL está disponible
echo "<h2>1. Verificar cURL</h2>\n";
if (function_exists('curl_init')) {
    echo "<p style='color:green'>✓ cURL está disponible</p>\n";
} else {
    echo "<p style='color:red'>✗ cURL NO está disponible</p>\n";
    exit('cURL es necesario para continuar');
}

// Test 2: Intentar conectar a la API
echo "<h2>2. Conectar a la API</h2>\n";
$apiUrl = API_BASE_URL.'/apicar/cars';
echo '<p>URL: '.$apiUrl."</p>\n";
echo '<p>Usuario: '.API_BASIC_USER."</p>\n";

$ch = curl_init($apiUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Accept: application/json',
    ],
    CURLOPT_USERPWD => API_BASIC_USER.':'.API_BASIC_PASS,
    CURLOPT_TIMEOUT => 10,
]);

$raw = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo '<p>HTTP Code: <strong>'.$httpCode."</strong></p>\n";

if ($raw === false) {
    echo "<p style='color:red'>✗ Error de cURL: ".$curlError."</p>\n";
} else {
    echo "<p style='color:green'>✓ Respuesta recibida</p>\n";
}

// Test 3: Mostrar respuesta
echo "<h2>3. Respuesta de la API</h2>\n";
if ($raw) {
    echo "<h3>Raw Response:</h3>\n";
    echo '<pre>'.htmlspecialchars($raw)."</pre>\n";

    echo "<h3>JSON Decodificado:</h3>\n";
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        echo '<pre>'.print_r($decoded, true)."</pre>\n";

        if (is_array($decoded) && count($decoded) > 0) {
            echo "<p style='color:green'>✓ Se recibieron ".count($decoded)." coches</p>\n";
        } else {
            echo "<p style='color:orange'>⚠ La API devolvió un array vacío</p>\n";
        }
    } else {
        echo "<p style='color:red'>✗ No se pudo decodificar JSON</p>\n";
    }
} else {
    echo "<p style='color:red'>✗ No hay respuesta</p>\n";
}

// Test 4: Verificar que la tabla cars tiene datos
echo "<h2>4. Verificar datos en la BD de mvcapi</h2>\n";
$apiDebugUrl = API_BASE_URL.'/apicar/debug';
echo '<p>Accediendo a: '.$apiDebugUrl."</p>\n";

$ch = curl_init($apiDebugUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_USERPWD => API_BASIC_USER.':'.API_BASIC_PASS,
    CURLOPT_TIMEOUT => 10,
]);

$debugRaw = curl_exec($ch);
curl_close($ch);

if ($debugRaw) {
    $debug = json_decode($debugRaw, true);
    echo '<pre>'.print_r($debug, true)."</pre>\n";
}

echo "<hr>\n";
echo "<p><a href='".RUTA_URL."'>Volver a inicio</a></p>\n";
echo "<p><a href='".API_BASE_URL."/public/setup_data.php'>Insertar datos de prueba en mvcapi</a></p>\n";
