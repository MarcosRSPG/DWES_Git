<?php

// Test simple de conexión y verificación de tabla usuarios_api

require_once __DIR__.'/app/config/config.php';

echo "=== TEST RÁPIDO DE AUTENTICACIÓN ===\n\n";

// Conectar a BD
try {
    $dsn = 'mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NOMBRE.';charset='.DB_CHARSET;
    $pdo = new PDO($dsn, DB_USUARIO, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión a BD exitosa\n\n";
} catch (PDOException $e) {
    exit('❌ Error de conexión: '.$e->getMessage()."\n");
}

// Verificar tabla
try {
    $stmt = $pdo->query('SELECT COUNT(*) as total FROM usuarios_api');
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    echo "✅ Tabla usuarios_api existe\n";
    echo "   Total usuarios: {$total}\n\n";

    if ($total > 0) {
        echo "Usuarios disponibles:\n";
        $stmt = $pdo->query('SELECT user, nombre_completo FROM usuarios_api');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "  - {$row['user']} ({$row['nombre_completo']})\n";
        }
        echo "\n";

        // Test de password_verify
        echo "Test de autenticación:\n";
        $tests = [
            ['user' => 'admin', 'pass' => 'admin123'],
            ['user' => 'profesor', 'pass' => '1234'],
        ];

        foreach ($tests as $test) {
            $stmt = $pdo->prepare('SELECT pass FROM usuarios_api WHERE user = :user');
            $stmt->execute(['user' => $test['user']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && password_verify($test['pass'], $result['pass'])) {
                echo "  ✅ {$test['user']} / {$test['pass']} - OK\n";
            } else {
                echo "  ❌ {$test['user']} / {$test['pass']} - FALLO\n";
            }
        }
    } else {
        echo "⚠️  No hay usuarios. Ejecuta: mysql -u root -p < bd/bd.sql\n";
    }
} catch (PDOException $e) {
    echo '❌ Error: '.$e->getMessage()."\n";
    echo "\nPara crear la tabla, ejecuta:\n";
    echo "  mysql -u root -p < bd/bd.sql\n";
}

echo "\n=== TEST COMPLETADO ===\n";
