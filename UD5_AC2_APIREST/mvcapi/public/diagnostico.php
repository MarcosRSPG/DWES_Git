<?php
/**
 * Script de diagnóstico para verificar la configuración de mvcapi.
 */
echo "=== DIAGNÓSTICO DE MVCAPI ===\n\n";

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
if (defined('DB_HOST')) {
    echo '   ✓ DB_HOST: '.DB_HOST."\n";
    echo '   ✓ DB_USUARIO: '.DB_USUARIO."\n";
    echo '   ✓ DB_NOMBRE: '.DB_NOMBRE."\n";
    echo '   ✓ RUTA_URL: '.RUTA_URL."\n";
} else {
    echo "   ✗ Las constantes de configuración no están definidas\n";
    echo "   → Verifica que app/config/config.php se carga correctamente\n\n";
}

// 3. Verificar clases
echo "\n3. Clases disponibles:\n";
$classes = [
    'Cls\\Mvc2app\\Core',
    'Cls\\Mvc2app\\Controlador',
    'Cls\\Mvc2app\\Db',
    'Cls\\Mvc2app\\Paginas',
    'Cls\\Mvc2app\\Articulos',
    'Cls\\Mvc2app\\ApiCar',
    'Cls\\Mvc2app\\Car',
    'Cls\\Mvc2app\\Articulo',
];

foreach ($classes as $class) {
    if (class_exists($class)) {
        echo '   ✓ '.$class."\n";
    } else {
        echo '   ✗ '.$class." NO ENCONTRADA\n";
    }
}

// 4. Verificar tablas en BD
echo "\n4. Base de datos:\n";
try {
    $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NOMBRE;
    $pdo = new PDO($dsn, DB_USUARIO, DB_PASSWORD);

    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (in_array('cars', $tables)) {
        echo "   ✓ Tabla 'cars' existe\n";
        $countCars = $pdo->query('SELECT COUNT(*) FROM cars')->fetchColumn();
        echo '     → '.$countCars." coches en la BD\n";
    } else {
        echo "   ✗ Tabla 'cars' NO EXISTE\n";
    }

    if (in_array('articulos', $tables)) {
        echo "   ✓ Tabla 'articulos' existe\n";
        $countArticulos = $pdo->query('SELECT COUNT(*) FROM articulos')->fetchColumn();
        echo '     → '.$countArticulos." artículos en la BD\n";
    } else {
        echo "   ✗ Tabla 'articulos' NO EXISTE\n";
    }
} catch (Exception $e) {
    echo '   ✗ Error de conexión a BD: '.$e->getMessage()."\n";
}

// 5. Verificar vistas
echo "\n5. Vistas:\n";
$vistas = [
    'app/vistas/paginas/inicio.php',
    'app/vistas/paginas/contacto.php',
    'app/vistas/paginas/articulo.php',
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

// 6. Verificar .htaccess
echo "\n6. Reescritura de URLs (.htaccess):\n";
$htaccessPath = __DIR__.'/public/.htaccess';
if (file_exists($htaccessPath)) {
    echo "   ✓ public/.htaccess existe\n";
    $content = file_get_contents($htaccessPath);
    if (strpos($content, 'RewriteBase') !== false) {
        echo "   ✓ RewriteBase está configurado\n";
        if (strpos($content, '/UD5_AC2_APIREST/mvcapi/public') !== false) {
            echo "   ✓ RewriteBase está correctamente configurado para /UD5_AC2_APIREST/mvcapi/public\n";
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
