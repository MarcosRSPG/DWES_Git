<?php
// Prueba de autenticación
echo "=== PRUEBA DE AUTENTICACIÓN ===\n\n";

// Lo que tienes en config.php
$config_user = 'ZHdlcw=='; // base64_encode('dwes')
$config_pass = 'ZHdlcw=='; // base64_encode('dwes')

echo "1. Valores en config.php de la API:\n";
echo "   API_BASIC_USER: {$config_user}\n";
echo "   API_BASIC_PASS: {$config_pass}\n\n";

// Lo que envías en ClienteAPI.php (línea 96)
$sending_user = base64_encode('dwes');
$sending_pass = base64_encode('dwes');
$sending_header = 'Authorization: Basic ' . base64_encode($sending_user) . ':' . base64_encode($sending_pass);

echo "2. Lo que envía ClienteAPI.php (INCORRECTO):\n";
echo "   Header: {$sending_header}\n\n";

// Lo que DEBERÍA enviarse
$correct_header = 'Authorization: Basic ' . base64_encode('dwes:dwes');
echo "3. Lo que DEBERÍA enviarse (CORRECTO):\n";
echo "   Header: {$correct_header}\n\n";

// Comparación
echo "=== PROBLEMAS IDENTIFICADOS ===\n\n";

echo "PROBLEMA 1 en config.php:\n";
echo "- Tienes: define('API_BASIC_USER', 'ZHdlcw==');\n";
echo "- Debería ser: define('API_BASIC_USER', 'dwes');\n\n";

echo "PROBLEMA 2 en ClienteAPI.php (línea 96):\n";
echo "- Tienes: 'Authorization: Basic ' . base64_encode(\$this->basicUser) . ':' . base64_encode(\$this->basicPass)\n";
echo "- Debería ser: 'Authorization: Basic ' . base64_encode(\$this->basicUser . ':' . \$this->basicPass)\n\n";

echo "El formato correcto de Basic Auth es:\n";
echo "Authorization: Basic base64(usuario:contraseña)\n";
echo "NO es: Authorization: Basic base64(usuario):base64(contraseña)\n";
