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
    <title>Detalles de Mascota</title>
<link rel="stylesheet" href="<?php echo RUTA_URL; ?>/css/estilos.css"></head>
<body>

<h1>Detalles de la Mascota</h1>

<div style="border: 1px solid #ccc; padding: 20px; margin: 20px 0; max-width: 500px;">
    <p><strong>ID:</strong> <?php echo htmlspecialchars($mascota['id']); ?></p>
    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($mascota['nombre']); ?></p>
    <p><strong>Tipo:</strong> <?php echo htmlspecialchars($mascota['tipo']); ?></p>
    <p><strong>Fecha de nacimiento:</strong> <?php echo $mascota['fecha_nacimiento']; ?></p>
    <p><strong>ID Persona:</strong> <?php echo htmlspecialchars($mascota['id_persona']); ?></p>
    
    <?php if (!empty($mascota['foto_url'])) { ?>
        <p><strong>Foto:</strong></p>
        <img src="/UD1-UD6_Examen-Marcos_Rodriguez/mvcexamen<?php echo htmlspecialchars($mascota['foto_url']); ?>" 
             alt="<?php echo htmlspecialchars($mascota['nombre']); ?>" 
             style="max-width: 300px;">
    <?php } ?>
</div>

<div style="margin-top: 20px;">
    <a href="<?php echo RUTA_URL; ?>mascotas/editar/<?php echo $id; ?>">
        <button type="button">Editar</button>
    </a>
    <a href="<?php echo RUTA_URL; ?>mascotas/index">
        <button type="button">Volver al listado</button>
    </a>
</div>

</body>
</html>
