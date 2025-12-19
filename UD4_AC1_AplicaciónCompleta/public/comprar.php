<?php
session_start();
require_once '../vendor/autoload.php';

use Mrs\Restaurante\GestorPedidos;

if (!isset($_SESSION['correo'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: principal.php');
    exit;
}

$codProd  = trim((string)($_POST['CodProd'] ?? ''));
$cantidad = (int)($_POST['cantidad'] ?? 0);
$cat      = trim((string)($_POST['cat'] ?? ''));

if ($codProd === '' || $cantidad <= 0) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Datos inválidos.'];
    header('Location: tabla_lectura.php' . ($cat !== '' ? '?cat=' . urlencode($cat) : ''));
    exit;
}

try {
    GestorPedidos::comprarPorCorreo($_SESSION['correo'], $codProd, $cantidad);
    $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Añadido al carrito.'];
} catch (Throwable $e) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Error: ' . $e->getMessage()];
}

header('Location: tabla_lectura.php' . ($cat !== '' ? '?cat=' . urlencode($cat) : ''));
exit;
