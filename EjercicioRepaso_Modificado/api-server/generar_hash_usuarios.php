<?php
/**
 * Script para generar hashes de contraseñas para usuarios_api
 * Uso: php generar_hash_usuarios.php.
 */
echo "=== Generador de Hashes para usuarios_api ===\n\n";

$usuarios = [
    ['user' => 'admin', 'pass' => 'admin123', 'nombre' => 'Administrador API'],
    ['user' => 'profesor', 'pass' => '1234', 'nombre' => 'Profesor Test'],
    ['user' => 'usuario', 'pass' => 'password', 'nombre' => 'Usuario Normal'],
];

echo "Hashes generados:\n\n";

foreach ($usuarios as $usuario) {
    $hash = password_hash($usuario['pass'], PASSWORD_DEFAULT);

    echo "Usuario: {$usuario['user']}\n";
    echo "Contraseña: {$usuario['pass']}\n";
    echo "Hash: {$hash}\n";
    echo "SQL: INSERT INTO usuarios_api (user, pass, nombre_completo) VALUES ('{$usuario['user']}', '{$hash}', '{$usuario['nombre']}');\n";
    echo str_repeat('-', 80)."\n";
}

echo "\n=== Script SQL completo ===\n\n";
echo "-- Limpiar tabla\n";
echo "TRUNCATE TABLE usuarios_api;\n\n";
echo "-- Insertar usuarios\n";

foreach ($usuarios as $usuario) {
    $hash = password_hash($usuario['pass'], PASSWORD_DEFAULT);
    echo "INSERT INTO usuarios_api (user, pass, nombre_completo) VALUES ('{$usuario['user']}', '{$hash}', '{$usuario['nombre']}');\n";
}

echo "\n=== Verificación ===\n";
echo "SELECT id, user, nombre_completo, fecha_creacion FROM usuarios_api;\n\n";

echo "Hashes generados correctamente.\n";
