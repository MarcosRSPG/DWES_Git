<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $datos['titulo'] ?? NOMBRESITIO; ?></title>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/css/estilos.css">
</head>
<body>
    <?php if (!empty($_SESSION['veterinario_email'])) { ?>
    <nav class="navbar">
        <div class="nav-header"><?php echo $datos['titulo'] ?? NOMBRESITIO; ?></div>
        <div class="nav-actions">
            <a href="<?php echo RUTA_URL; ?>/Paginas/inicio" class="btn btn-secondary">Inicio</a>
            <a href="<?php echo RUTA_URL; ?>/Paginas/logout" class="btn btn-logout">Logout</a>
        </div>
    </nav>
    <?php } ?>

    <main class="main-content">
