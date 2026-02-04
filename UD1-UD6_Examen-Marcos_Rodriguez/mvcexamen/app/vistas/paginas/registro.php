<?php

use Mrs\Webcliente\ClienteAPI;

if (!isset($_SESSION['veterinario'])) {
    header('Refresh: 1; url='.RUTA_URL.'auth/login');
    exit;
}
$cliente = new ClienteAPI();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '<!-- DEBUG: Entrando en POST -->';
    var_dump($_POST); // Ver qué llega

    $nombre = trim($_POST['nombre'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');
    $fechaNac = trim($_POST['fecha_nacimiento'] ?? '');
    $fotoUrl = trim($_POST['foto_url'] ?? '');
    $idPers = trim($_POST['id_persona'] ?? '');

    echo "<!-- DEBUG: Datos - nombre: $nombre, tipo: $tipo -->";

    $respuesta = $cliente->post('controladormascotas/crear', ['nombre' => $nombre, 'tipo' => $tipo, 'fechaNac' => $fechaNac, 'fotoUrl' => $fotoUrl, 'idPers' => $idPers]);

    echo '<pre>DEBUG Respuesta:';
    print_r($respuesta);
    echo '</pre>';

    if ($respuesta['success']) {
        $mensaje = 'Mascota creada correctamente';
        header('Location: '.RUTA_URL.'mascotas/index');
        exit;
    } else {
        $mensaje = 'Error: '.($respuesta['data']['error'] ?? 'Error desconocido');
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Mascota</title>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/css/estilos.css">
</head>
<body>

<h1>Registrar Mascota</h1>

<form method="POST" action="<?php echo RUTA_URL; ?>register/index">
    <label>Nombre:</label><br>
    <input type="text" name="nombre"><br>

    <label>Tipo:</label><br>
    <input type="text" name="tipo"><br>

    <label>Fecha nacimiento:</label><br>
    <input type="date" name="fecha_nacimiento"><br>

    <label>Url de la foto:</label><br>
    <input type="text" name="foto_url"><br>

    <label>ID Persona:</label><br>
    <input type="text" name="id_persona"><br>

    <button type="submit">Guardar</button>
</form>

</body>
</html>
