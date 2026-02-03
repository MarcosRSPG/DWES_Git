<?php

$hash = password_hash('1234', PASSWORD_DEFAULT);
echo "Hash de '1234': ".$hash."\n";
echo "\nCopia esto en tu base de datos.";
