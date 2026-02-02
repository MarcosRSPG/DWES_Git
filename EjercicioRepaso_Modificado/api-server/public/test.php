<?php

echo 'La API está funcionando correctamente';
echo "\n\nPHP Version: ".PHP_VERSION;
echo "\n\nDocumentRoot: ".$_SERVER['DOCUMENT_ROOT'];
echo "\n\nScript Filename: ".$_SERVER['SCRIPT_FILENAME'];
echo "\n\nRequest URI: ".$_SERVER['REQUEST_URI'];
echo "\n\nQuery String: ".($_SERVER['QUERY_STRING'] ?? 'NINGUNO');
