<?php
$hash = password_hash('password123', PASSWORD_DEFAULT);
echo "Hash de 'password123': " . $hash . "\n";
echo "\nCopia esto en tu base de datos.";
