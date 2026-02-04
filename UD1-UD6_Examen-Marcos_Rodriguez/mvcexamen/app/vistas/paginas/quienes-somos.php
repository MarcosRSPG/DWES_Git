<?php
if (!isset($_SESSION['veterinario'])) {
    header('Location: '.RUTA_URL.'auth/login');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página estática de Quienes somos</title>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/css/estilos.css">
</head>
<body>

    <h1>Quienes somos</h1>
    <p>
        Esta es una página estática de Quienes somos NO generada ni llamada desde el controlador.
        Lorem ipsum dolor sit amet consectetur adipisicing elit.
    </p>
</body>
</html>