<?php

use Mrs\Webcliente\ClienteAPI;

session_start();
require_once '../vendor/autoload.php';

if (!isset($_SESSION['veterinario'])) {
    header('Refresh: 1; url='.RUTA_URL.'/paginas/login');
    exit;
}
$cliente = new ClienteAPI();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');
    $fechaNac = trim($_POST['fecha_nacimiento'] ?? '');
    $idPers = trim($_POST['id_persona'] ?? '');

    $cliente->post('controladormascotas/crear', ['nombre' => $nombre, 'tipo' => $tipo, 'fechaNac' => $fechaNac, 'idPers' => $idPers]);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Mascota</title>
</head>
<body>

<h1>Registrar Mascota</h1>

<form method="" action="">
    <label>Nombre:</label><br>
    <input type="text" name="nombre"><br>

    <label>Tipo:</label><br>
    <input type="text" name="tipo"><br>

    <label>Fecha nacimiento:</label><br>
    <input type="date" name="fecha_nacimiento"><br>

    <label>ID Persona:</label><br>
    <input type="number" name="id_persona"><br>

    <button type="submit">Guardar</button>
</form>

</body>
</html>
