    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="id=edge">
    <link rel="stylesheet" type="text/css" href="<?php echo RUTA_URL?>/css/estilos.css">
    <title><?php echo NOMBRESITIO; ?> </title>
    <nav>
        <a href="<?=RUTA_URL."/Cars/index";?>">Inicio | </a>
        <a href="<?=RUTA_URL."/quienes-somos.html";?>">Quiénes somos | </a>
        <a href="<?=RUTA_URL."/Paginas/contacto";?>">Contacto | </a>
        <a href="<?=RUTA_URL."/Cars/index";?>">Coches</a>
        <a href="<?=RUTA_URL."/Paginas/cars_form";?>">Alta Coche | </a>
        <a href="<?=RUTA_URL."/Articulos/index";?>">Artículos</a>
        <a href="<?=RUTA_URL."/Paginas/articulos_form";?>">Alta Articulo | </a>
        <a href="<?=RUTA_URL."/Paginas/usuarios_form";?>">Alta Usuario</a>
        <?php if (!empty($_SESSION['usuario'])): ?>
            <span> | Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
            <a href="<?=RUTA_URL."/Auth/logout";?>"> | Logout</a>
        <?php else: ?>
            <a href="<?=RUTA_URL."/Auth/login";?>"> | Login</a>
        <?php endif; ?>
    </nav>
    <hr>
</head>
<body>