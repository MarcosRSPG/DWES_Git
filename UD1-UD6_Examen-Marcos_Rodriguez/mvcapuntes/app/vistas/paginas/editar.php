<?php
$mascota = $datos['mascota'] ?? null;
if (!$mascota) {
    echo 'Error: Mascota no encontrada';
    exit;
}
$id = $mascota['id'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Mascota</title>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/public/css/estilos.css">
</head>
<body>

<h1>Editar Mascota</h1>

<?php if (!empty($datos['error'])) { ?>
    <div class="mensaje-error"><?php echo htmlspecialchars($datos['error']); ?></div>
<?php } ?>

<form method="POST">
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
    <a href="<?php echo RUTA_URL; ?>/Mascota/mascotas">Cancelar</a>
</form>

</body>
</html>
