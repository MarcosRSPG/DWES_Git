<?php

use Mrs\Webcliente\ClienteAPI;

session_start();
require_once '../vendor/autoload.php';

if (!isset($_SESSION['veterinario'])) {
    header('Refresh: 1; url='.RUTA_URL.'/paginas/login');
    exit;
}
$cliente = new ClienteAPI();

$mascotas = $cliente->get('controladormascotas/mascotas');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de mascotas</title>
</head>
<body>

<h1>Listado de Mascotas</h1>

<table border="1">
    <tr>
        <th>Nombre</th>
        <th>Tipo</th>
        <th>Fecha nacimiento</th>
        <th>Foto</th>
        <th>Acciones</th>
    </tr>
<?php foreach ($mascotas as $m) { ?>
            <?php

    $id = $m['id'] ?? $m['id'] ?? '';
    $nombre = $m['nombre'] ?? $m['nombre'] ?? '';
    $tipo = $m['tipo'] ?? $m['tipo'] ?? '';
    $fechaNac = $m['fechaNac'] ?? $m['fechaNac'] ?? '';
    $fotoUrl = $m['fotoUrl'] ?? $m['fotoUrl'] ?? '';
    $idPers = (int) ($m['idPers'] ?? $m['idPers'] ?? 0);
    ?>
            <tr>
                <td><?php echo htmlspecialchars($nombre); ?></td>
                <td><?php echo htmlspecialchars($tipo); ?></td>
                <td><?php echo $fechaNac; ?></td>
                <td><img alt=<?php echo htmlspecialchars($nombre); ?> src=<?php echo $fotoUrl; ?> /></td>
                <td><button onclick=<?php $cliente->delete('controladormascotas/eliminar/'.$id); ?>>ELIMINAR</button></td>
            </tr>
        <?php } ?>
</table>

</body>
</html>

