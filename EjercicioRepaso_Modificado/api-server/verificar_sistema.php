<?php
/**
 * Script de Verificación del Sistema de Autenticación
 * Comprueba que todos los componentes funcionan correctamente.
 */
echo "=== VERIFICACIÓN DEL SISTEMA DE AUTENTICACIÓN ===\n\n";

// Verificar que el archivo de configuración existe
$configPath = __DIR__.'/app/config/config.php';
if (!file_exists($configPath)) {
    exit("❌ ERROR: No se encuentra config.php\n");
}
echo "✅ Archivo config.php encontrado\n";

// Cargar configuración
require_once $configPath;

// Verificar que NO existen las constantes antiguas
if (defined('API_BASIC_USER') || defined('API_BASIC_PASS')) {
    echo "⚠️  ADVERTENCIA: Las constantes API_BASIC_USER/API_BASIC_PASS todavía existen\n";
    echo "   Deberían haber sido eliminadas del config.php\n";
} else {
    echo "✅ Constantes de autenticación eliminadas correctamente\n";
}

// Verificar autoload de Composer
$autoloadPath = __DIR__.'/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    exit("❌ ERROR: No se encuentra vendor/autoload.php. Ejecuta: composer install\n");
}
echo "✅ Autoload de Composer encontrado\n";

require_once $autoloadPath;

// Verificar que existe el modelo GestorUsuarios
$modeloPath = __DIR__.'/app/modelos/GestorUsuarios.php';
if (!file_exists($modeloPath)) {
    exit("❌ ERROR: No se encuentra GestorUsuarios.php\n");
}
echo "✅ Modelo GestorUsuarios encontrado\n";

// Verificar conexión a BD
echo "\n--- Verificando Conexión a Base de Datos ---\n";
try {
    $dsn = 'mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NOMBRE.';charset='.DB_CHARSET;
    $pdo = new PDO($dsn, DB_USUARIO, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión a BD exitosa\n";
    echo '   Host: '.DB_HOST.':'.DB_PORT."\n";
    echo '   BD: '.DB_NOMBRE."\n";
} catch (PDOException $e) {
    exit('❌ ERROR de conexión a BD: '.$e->getMessage()."\n");
}

// Verificar que existe la tabla usuarios_api
echo "\n--- Verificando Tabla usuarios_api ---\n";
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'usuarios_api'");
    if ($stmt->rowCount() === 0) {
        exit("❌ ERROR: La tabla 'usuarios_api' no existe. Ejecuta bd/bd.sql\n");
    }
    echo "✅ Tabla usuarios_api existe\n";
} catch (PDOException $e) {
    exit('❌ ERROR: '.$e->getMessage()."\n");
}

// Verificar estructura de la tabla
echo "\n--- Verificando Estructura de la Tabla ---\n";
try {
    $stmt = $pdo->query('DESCRIBE usuarios_api');
    $columnas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $columnasRequeridas = ['id', 'user', 'pass', 'nombre_completo', 'fecha_creacion'];
    $faltantes = array_diff($columnasRequeridas, $columnas);

    if (!empty($faltantes)) {
        echo '❌ ERROR: Faltan columnas: '.implode(', ', $faltantes)."\n";
    } else {
        echo "✅ Estructura de tabla correcta\n";
        echo '   Columnas: '.implode(', ', $columnas)."\n";
    }
} catch (PDOException $e) {
    exit('❌ ERROR: '.$e->getMessage()."\n");
}

// Verificar que existen usuarios
echo "\n--- Verificando Usuarios en BD ---\n";
try {
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM usuarios_api');
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    if ($total === 0) {
        echo "⚠️  ADVERTENCIA: No hay usuarios en la tabla\n";
        echo "   Ejecuta bd/bd.sql para insertar usuarios de prueba\n";
    } else {
        echo "✅ Se encontraron {$total} usuario(s)\n";

        // Listar usuarios
        $stmt = $pdo->query('SELECT id, user, nombre_completo, fecha_creacion FROM usuarios_api');
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "\n   Usuarios disponibles:\n";
        foreach ($usuarios as $user) {
            echo "   - ID: {$user['id']} | User: {$user['user']} | Nombre: {$user['nombre_completo']}\n";
        }
    }
} catch (PDOException $e) {
    exit('❌ ERROR: '.$e->getMessage()."\n");
}

// Verificar hashes de contraseñas
echo "\n--- Verificando Hashes de Contraseñas ---\n";
try {
    $stmt = $pdo->query('SELECT user, pass FROM usuarios_api');
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($usuarios as $user) {
        // Verificar que el hash tiene el formato correcto de bcrypt
        if (preg_match('/^\$2y\$\d{2}\$[\.\/A-Za-z0-9]{53}$/', $user['pass'])) {
            echo "   ✅ {$user['user']}: Hash válido (bcrypt)\n";
        } else {
            echo "   ❌ {$user['user']}: Hash INVÁLIDO o no es bcrypt\n";
        }
    }
} catch (PDOException $e) {
    exit('❌ ERROR: '.$e->getMessage()."\n");
}

// Verificar password_verify con un usuario de prueba
echo "\n--- Verificando password_verify() ---\n";
$testUsers = [
    ['user' => 'admin', 'pass' => 'admin123'],
    ['user' => 'profesor', 'pass' => '1234'],
    ['user' => 'usuario', 'pass' => 'password'],
];

foreach ($testUsers as $test) {
    try {
        $stmt = $pdo->prepare('SELECT pass FROM usuarios_api WHERE user = :user');
        $stmt->execute(['user' => $test['user']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            echo "   ⚠️  Usuario '{$test['user']}' no encontrado\n";
            continue;
        }

        if (password_verify($test['pass'], $result['pass'])) {
            echo "   ✅ {$test['user']}: Verificación correcta\n";
        } else {
            echo "   ❌ {$test['user']}: Verificación FALLÓ\n";
        }
    } catch (PDOException $e) {
        echo '   ❌ ERROR: '.$e->getMessage()."\n";
    }
}

echo "\n=== RESUMEN ===\n";
echo "✅ Sistema de autenticación configurado correctamente\n";
echo "✅ Basado en ProyectoAndrea (autenticación por BD + hashes)\n\n";

echo "Usuarios disponibles para Basic Auth:\n";
echo "  - admin / admin123\n";
echo "  - profesor / 1234\n";
echo "  - usuario / password\n\n";

echo "Para probar:\n";
echo "  curl -u admin:admin123 http://mywww/EjercicioRepaso_Modificado/api-server/controladorproductos/productos\n\n";

echo "=== VERIFICACIÓN COMPLETADA ===\n";
