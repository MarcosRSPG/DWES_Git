<?php
session_start();
require_once '../vendor/autoload.php';

use Mrs\Restaurante\GestorCategorias;

if (!isset($_SESSION['correo'])) {
    header('Location: index.php');
    exit;
}

$categorias = GestorCategorias::getCategorias();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title>Principal</title>
</head>
<body>
<header>
    <h1>Lista de Categorías</h1>
</header>

<main>
    <ul>
        <?php foreach ($categorias as $c): ?>
            <?php
            $id = $c['CodCat'] ?? $c['id'] ?? '';
            $nombre = $c['Nombre'] ?? $c['nombre'] ?? '';
            ?>
            <li>
                <a href="tabla_lectura.php?cat=<?= urlencode($id) ?>">
                    <?= htmlspecialchars($nombre) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>


</main>
<footer>
    <h6>
        Usuario: <?= htmlspecialchars($_SESSION['correo']) ?>
        <a href="carrito.php">Ver Carrito</a>
        <a href="logout.php">Cerrar Sesión</a>
    </h6>
</footer>
</body>
</html>
