<?php
/**
 * Script de diagnóstico para verificar la configuración de mvccurl.
 */
echo "=== DIAGNÓSTICO DE MVCCURL ===\n\n";

// 1. Verificar que el archivo de autoload existe
$autoloadPath = __DIR__.'/vendor/autoload.php';
echo "1. Autoload de Composer:\n";
if (file_exists($autoloadPath)) {
    echo "   ✓ El archivo vendor/autoload.php existe\n";
    require_once $autoloadPath;
} else {
    echo "   ✗ FALTA: vendor/autoload.php\n";
    echo "   → Ejecuta: composer install\n\n";
}

// 2. Verificar constantes de configuración
echo "\n2. Configuración:\n";
if (defined('RUTA_URL')) {
    echo '   ✓ RUTA_URL: '.RUTA_URL."\n";
    echo '   ✓ API_BASE_URL: '.API_BASE_URL."\n";
    echo '   ✓ API_BASIC_USER: '.API_BASIC_USER."\n";
} else {
    echo "   ✗ Las constantes de configuración no están definidas\n";
    echo "   → Verifica que app/config/config.php se carga correctamente\n\n";
}

// 3. Verificar clases
echo "\n3. Clases disponibles:\n";
$classes = [
    'Cls\\Mvc2app\\Core',
    'Cls\\Mvc2app\\Controlador',
    'Cls\\Mvc2app\\Paginas',
    'Cls\\Mvc2app\\Cars',
];

foreach ($classes as $class) {
    if (class_exists($class)) {
        echo '   ✓ '.$class."\n";
    } else {
        echo '   ✗ '.$class." NO ENCONTRADA\n";
    }
}

// 4. Verificar cURL
echo "\n4. Extensiones necesarias:\n";
if (extension_loaded('curl')) {
    echo "   ✓ Extensión cURL está habilitada\n";
} else {
    echo "   ✗ Extensión cURL NO está habilitada\n";
    echo "   → Sin cURL, mvccurl no puede conectarse a la API\n";
}

if (extension_loaded('pdo')) {
    echo "   ✓ Extensión PDO está habilitada\n";
} else {
    echo "   ✗ Extensión PDO NO está habilitada\n";
}

// 5. Verificar conectividad a la API
echo "\n5. Conectividad a la API (mvcapi):\n";
if (defined('API_BASE_URL') && extension_loaded('curl')) {
    $url = API_BASE_URL.'/apicar/debug';
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD => API_BASIC_USER.':'.API_BASIC_PASS,
        CURLOPT_TIMEOUT => 5,
    ]);

    $response = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response && $code == 200) {
        echo "   ✓ La API es accesible\n";
        echo '   → Respuesta HTTP: '.$code."\n";
    } else {
        echo "   ✗ No se puede conectar a la API\n";
        echo '   → URL: '.$url."\n";
        echo '   → Error: '.($error ?: 'HTTP '.$code)."\n";
        echo '   → Verifica que mvcapi está corriendo en '.API_BASE_URL."\n";
    }
} else {
    echo "   ⚠ No se puede verificar (falta config o cURL)\n";
}

// 6. Verificar vistas
echo "\n6. Vistas:\n";
$vistas = [
    'app/vistas/cars/index.php',
    'app/vistas/cars/show.php',
    'app/vistas/paginas/inicio.php',
    'app/vistas/paginas/contacto.php',
    'app/vistas/paginas/cars_form.php',
    'app/vistas/inc/header.php',
    'app/vistas/inc/footer.php',
];

foreach ($vistas as $vista) {
    $path = __DIR__.'/'.$vista;
    if (file_exists($path)) {
        echo '   ✓ '.$vista."\n";
    } else {
        echo '   ✗ '.$vista." FALTA\n";
    }
}

// 7. Verificar .htaccess
echo "\n7. Reescritura de URLs (.htaccess):\n";
$htaccessPath = __DIR__.'/public/.htaccess';
if (file_exists($htaccessPath)) {
    echo "   ✓ public/.htaccess existe\n";
    $content = file_get_contents($htaccessPath);
    if (strpos($content, 'RewriteBase') !== false) {
        echo "   ✓ RewriteBase está configurado\n";
        if (strpos($content, '/UD5_AC2_APIREST/mvccurl/public') !== false) {
            echo "   ✓ RewriteBase está correctamente configurado para /UD5_AC2_APIREST/mvccurl/public\n";
        } else {
            echo "   ⚠ RewriteBase podría no ser la ruta correcta\n";
        }
    } else {
        echo "   ✗ RewriteBase NO está configurado\n";
    }
} else {
    echo "   ✗ public/.htaccess FALTA\n";
}

echo "\n=== FIN DEL DIAGNÓSTICO ===\n";
