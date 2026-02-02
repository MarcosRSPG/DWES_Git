<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="id=edge">
    <link rel="stylesheet" type="text/css" href="<?php echo RUTA_URL?>/css/estilos.css">
    <title><?php echo NOMBRESITIO; ?> </title>
    <nav>
        <a href="<?=RUTA_URL;?>">Inicio</a> |
        <a href="<?=RUTA_URL;?>/Paginas/contacto">Contacto</a> |
        <a href="<?=RUTA_URL;?>/Articulos/index">Artículos</a> |

        <a href="<?=RUTA_URL;?>/Cars/index">Ver Coches</a> |
        <a href="<?=RUTA_URL;?>/Cars/nuevo">Alta Coche</a> |

        <?php if (isset($_SESSION['api_user'])): ?>
            <span style="color: green;">👤 <?= $_SESSION['api_user']; ?></span>
            <a href="<?= RUTA_URL; ?>/login/salir" class="btn-logout">Cerrar Sesión</a>
        <?php else: ?>
            <a href="<?= RUTA_URL; ?>/login" class="btn-login">Conectarse</a>
        <?php endif; ?>
    </nav>
    <hr>
</head>
<body>