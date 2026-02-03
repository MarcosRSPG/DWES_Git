<?php
use Mrs\Webcliente\ClienteAPI;

if (!isset($_SESSION['veterinario'])) {
    header('Location: '.RUTA_URL.'auth/login');
    exit;
}
$cliente = new ClienteAPI();
if (isset($_GET['eliminar'])) {
    $cliente->delete('controladormascotas/eliminar/'.$_GET['eliminar']);
    header('Location: '.RUTA_URL.'mascotas/index');
    exit;
}
$respuesta = $cliente->get(endpoint: 'controladormascotas/mascotas');
$mascotas = $respuesta['data']['mascotas'] ?? [];

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

    $id = $m['id'] ?? '';
    $nombre = $m['nombre'] ?? '';
    $tipo = $m['tipo'] ?? '';
    $fechaNac = $m['fecha_nacimiento'] ?? '';
    $fotoUrl = $m['foto_url'] ?? '';
    $idPers = (int) ($m['id_persona'] ?? 0);
    ?>
            <tr>
                <td><?php echo htmlspecialchars($nombre); ?></td>
                <td><?php echo htmlspecialchars($tipo); ?></td>
                <td><?php echo $fechaNac; ?></td>
                <td><img alt=<?php echo htmlspecialchars($nombre); ?> src="/UD1-UD6_Examen-Marcos_Rodriguez/mvcexamen<?php echo $fotoUrl; ?>" width="100px"/></td>
                <td>
                    <a href="<?php echo RUTA_URL; ?>mascotas/detalles/<?php echo $id; ?>">Ver</a> |
                    <a href="<?php echo RUTA_URL; ?>mascotas/editar/<?php echo $id; ?>">Editar</a> |
                    <a href="<?php echo RUTA_URL; ?>mascotas/eliminar/<?php echo $id; ?>">Eliminar</a>
                </td>
            </tr>
        <?php } ?>
        
</table>
<a href="<?php echo RUTA_URL; ?>register/index">
    <button type="button">Ir a Registro</button>
</a>
</body>
</html>

