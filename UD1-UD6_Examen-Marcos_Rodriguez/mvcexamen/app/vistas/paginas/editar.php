<?php
use Mrs\Webcliente\ClienteAPI;

if (!isset($_SESSION['veterinario'])) {
    header('Location: '.RUTA_URL.'auth/login');
    exit;
}

$cliente = new ClienteAPI();
$id = $datos['id'] ?? '';

// Obtener datos de la mascota
$respuesta = $cliente->get('controladormascotas/mascota/'.$id);
$mascota = $respuesta['data']['mascota'] ?? null;

if (!$mascota) {
    header('Location: '.RUTA_URL.'mascotas/index');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Mascota</title>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/css/estilos.css">
</head>
<body>

<h1>Editar Mascota</h1>

<form method="POST" action="<?php echo RUTA_URL; ?>mascotas/actualizar/<?php echo $id; ?>">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="<?php echo htmlspecialchars($mascota['nombre']); ?>" required><br>

    <label>Tipo:</label><br>
    <input type="text" name="tipo" value="<?php echo htmlspecialchars($mascota['tipo']); ?>" required><br>

    <label>Fecha nacimiento:</label><br>
    <input type="date" name="fecha_nacimiento" value="<?php echo $mascota['fecha_nacimiento']; ?>" required><br>

    <label>URL de la foto:</label><br>
    <input type="text" name="foto_url" value="<?php echo htmlspecialchars($mascota['foto_url']); ?>"><br>

    <label>ID Persona:</label><br>
    <input type="text" name="id_persona" value="<?php echo htmlspecialchars($mascota['id_persona']); ?>" required><br>

    <button type="submit">Actualizar</button>
    <a href="<?php echo RUTA_URL; ?>mascotas/index">Cancelar</a>
</form>

</body>
</html>
