<?php
session_start();
require_once '../vendor/autoload.php';

use Mrs\Restaurante\GestorPedidos;

if (!isset($_SESSION['correo'])) {
    header('Location: index.php');
    exit;
}

function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = (string)($_POST['accion'] ?? '');

    try {
        if ($accion === 'actualizar') {
            $unidades = $_POST['unidades'] ?? [];
            if (!is_array($unidades)) $unidades = [];
            GestorPedidos::actualizarCarritoPorCorreo($_SESSION['correo'], $unidades);
            $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Carrito actualizado.'];
        }

        if ($accion === 'eliminar') {
            $codProd = trim((string)($_POST['CodProd'] ?? ''));
            if ($codProd !== '') {
                GestorPedidos::eliminarDelCarritoPorCorreo($_SESSION['correo'], $codProd);
                $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Producto eliminado.'];
            }
        }

        if ($accion === 'enviar') {
            GestorPedidos::enviarCarritoPorCorreo($_SESSION['correo']);
            $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Enviado correctamente.'];
        }
    } catch (Throwable $e) {
        $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Fallo en la BBDD: ' . $e->getMessage()];
    }

    header('Location: carrito.php');
    exit;
}

$data = GestorPedidos::getCarritoPorCorreo($_SESSION['correo']);
$lineas = $data['lineas'] ?? [];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1>Carrito</h1>

    <?php if ($flash): ?>
        <p class="<?= $flash['type'] === 'ok' ? 'ok' : 'error' ?>">
            <?= $flash['type'] === 'ok' ? '✅ ' : '❌ ' ?><?= h((string)$flash['msg']) ?>
        </p>
    <?php endif; ?>

    <?php if (empty($lineas)): ?>
        <p>Carrito vacío.</p>
    <?php else: ?>
        <form method="post" action="carrito.php">
            <input type="hidden" name="accion" value="actualizar">

            <table border="1" cellpadding="6" cellspacing="0">
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Peso</th>
                    <th>Unidades</th>
                    <th>Eliminar</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($lineas as $l): ?>
                    <tr>
                        <td><?= h((string)$l['Nombre']) ?></td>
                        <td><?= h((string)$l['Descripcion']) ?></td>
                        <td><?= h((string)$l['Peso']) ?></td>
                        <td>
                            <input type="number"
                                   name="unidades[<?= h((string)$l['CodProd']) ?>]"
                                   min="0"
                                   value="<?= (int)$l['Unidades'] ?>">
                        </td>
                        <td>
                            <form method="post" action="carrito.php" style="display:inline">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="CodProd" value="<?= h((string)$l['CodProd']) ?>">
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <br>
            <button type="submit">Actualizar</button>
        </form>

        <hr>

        <form method="post" action="carrito.php">
            <input type="hidden" name="accion" value="enviar">
            <button type="submit">Enviar pedido</button>
        </form>
    <?php endif; ?>

</main>

<footer>
    <h6>
        Usuario: <?= h((string)$_SESSION['correo']) ?>
        <a href="tabla_lectura.php">Productos</a>
        <a href="logout.php">Cerrar Sesión</a>
    </h6>
</footer>
</body>
</html>
