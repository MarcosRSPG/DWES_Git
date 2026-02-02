<?php
require_once __DIR__ . '/app/config/config.php';

$correo = 'restaurante@test.com';
$clave = 'password123';

try {
    $pdo = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NOMBRE, DB_USUARIO, DB_PASSWORD);
    
    $stmt = $pdo->prepare('SELECT Clave FROM restaurantes WHERE Correo = ?');
    $stmt->execute([$correo]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        echo "Hash en BD: " . $row['Clave'] . "\n\n";
        
        if (password_verify($clave, $row['Clave'])) {
            echo "✅ La contraseña 'password123' es CORRECTA\n";
        } else {
            echo "❌ La contraseña 'password123' es INCORRECTA\n";
            echo "\nProbando otras contraseñas comunes...\n";
            $tests = ['admin123', '1234', 'password', 'admin', '123456'];
            foreach ($tests as $test) {
                if (password_verify($test, $row['Clave'])) {
                    echo "✅ La contraseña correcta es: '$test'\n";
                    break;
                }
            }
        }
    } else {
        echo "❌ No se encontró el restaurante\n";
    }
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
