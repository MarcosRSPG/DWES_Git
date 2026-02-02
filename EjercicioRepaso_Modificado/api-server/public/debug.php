<?php
// Script de debug para ver errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== DEBUG API ===\n\n";

echo "1. Verificando autoload...\n";
$autoloadPath = __DIR__.'/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    echo "   ✅ Autoload existe\n";
    require_once $autoloadPath;
    echo "   ✅ Autoload cargado\n";
} else {
    die("   ❌ No existe: $autoloadPath\n");
}

echo "\n2. Verificando iniciador...\n";
$iniciadorPath = __DIR__.'/../app/iniciador.php';
if (file_exists($iniciadorPath)) {
    echo "   ✅ Iniciador existe\n";
    require_once $iniciadorPath;
    echo "   ✅ Iniciador cargado\n";
} else {
    die("   ❌ No existe: $iniciadorPath\n");
}

echo "\n3. Verificando Core...\n";
if (class_exists('Mrs\ApiServer\librerias\Core')) {
    echo "   ✅ Clase Core existe\n";
} else {
    die("   ❌ Clase Core no encontrada\n");
}

echo "\n4. Request Info:\n";
echo "   REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n";
echo "   QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'N/A') . "\n";
echo "   GET url: " . ($_GET['url'] ?? 'N/A') . "\n";

echo "\n✅ Todo OK - El problema está en Core o controladores\n";
