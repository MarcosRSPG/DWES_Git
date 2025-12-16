<?php
    session_start();
    require_once '../vendor/autoload.php';
    if (!isset($_SESSION['correo'])) {
        header('Location: index.php');
    }
?>
<!DOCTYPE html>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Principal</title>
</head>
<body>
    <header>
        <h1>Lista de Categorías</h1>
    </header>
<main>
    <ul>
        <li><a href="BebidasCon.html">Bebidas con</a></li>
        <li><a href="BebidasSin.html">Bebidas sin</a></li>
        <li><a href="Comida.html">Comida</a></li>
    </ul>

    <h6>Usuario: <?php echo $_SESSION['correo']?><a href="carrito.php">Ver Carrito</a><a href="logout.php">Cerrar Sesión</a></h6>
</main>
</body>
</html>