<?php
session_start();
require_once '../vendor/autoload.php';

use Mrs\Restaurante\GestorCategorias;
use Mrs\Restaurante\GestorProductos;

if (!isset($_SESSION['correo'])) {
    header('Location: index.php');
    exit;
}


$catId = $_GET['cat'] ?? null;


$categorias = GestorCategorias::getCategorias();


if ($catId) {
    $productos = GestorProductos::getProductosPorCategoria($catId);


    $titulo = $catId;
    foreach ($categorias as $c) {
        $id = $c['CodCat'] ?? $c['id'] ?? '';
        if ($id === $catId) {
            $titulo = $c['Nombre'] ?? $c['nombre'] ?? $catId;
            break;
        }
    }
    $titulo = htmlspecialchars($titulo);
} else {
    $productos = GestorProductos::getProductos();
    $titulo = "Todos los productos";
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?= $titulo ?></title>
</head>
<body>
<main>

    <!-- Selector de categoría (GET) -->
    <form method="get" action="">
        <label for="cat">Categoría:</label>
        <select name="cat" id="cat" onchange="this.form.submit()">
            <option value="">Todas</option>

            <?php foreach ($categorias as $c): ?>
                <?php
                $id = $c['CodCat'] ?? $c['id'] ?? '';
                $nombre = $c['Nombre'] ?? $c['nombre'] ?? '';
                ?>
                <option value="<?= htmlspecialchars($id) ?>" <?= ($catId === $id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($nombre) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <noscript><button type="submit">Filtrar</button></noscript>
    </form>

    <hr>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Peso</th>
            <th>Stock</th>
            <th>Cantidad</th>
            <th>Comprar</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($productos as $p): ?>
            <?php

            $id = $p['CodProd'] ?? $p['id'] ?? '';
            $nombre = $p['Nombre'] ?? $p['nombre'] ?? '';
            $desc = $p['Descripcion'] ?? $p['descripcion'] ?? '';
            $peso = $p['Peso'] ?? $p['peso'] ?? '';
            $stock = (int)($p['Stock'] ?? $p['stock'] ?? 0);
            ?>
            <tr>
                <td><?= htmlspecialchars($nombre) ?></td>
                <td><?= htmlspecialchars($desc) ?></td>
                <td><?= htmlspecialchars((string)$peso) ?></td>
                <td><?= $stock ?></td>

                <!-- POST: comprar -->
                <td>
                    <form method="post" action="comprar.php">
                        <input type="hidden" name="CodProd" value="<?= htmlspecialchars($id) ?>">
                        <input type="hidden" name="cat" value="<?= htmlspecialchars($catId ?? '') ?>">
                        <input type="number" name="cantidad" min="1" max="<?= $stock ?>" value="1" required>
                </td>
                <td>
                    <button type="submit" <?= ($stock <= 0) ? 'disabled' : '' ?>>Comprar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (empty($productos)): ?>
            <tr><td colspan="6">No hay productos para esa categoría.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
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
