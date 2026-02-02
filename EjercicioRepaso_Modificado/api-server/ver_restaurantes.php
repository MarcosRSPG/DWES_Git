<?php
require_once __DIR__ . '/app/config/config.php';

try {
    $pdo = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NOMBRE, DB_USUARIO, DB_PASSWORD);
    
    echo "=== RESTAURANTES EN LA BD ===\n\n";
    $stmt = $pdo->query('SELECT Correo, Clave, Nombre FROM restaurantes');
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Correo: " . $row['Correo'] . "\n";
        echo "Nombre: " . $row['Nombre'] . "\n";
        echo "Clave (hash): " . substr($row['Clave'], 0, 30) . "...\n";
        echo "---\n";
    }
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
