<?php require_once RUTA_APP.'/vistas/inc/header.php'; ?>

    <h1><?php echo $datos['titulo'] ?? 'Editar'; ?></h1>

<?php if (!empty($datos['error'])): ?>
    <div style="color: red; background: #fee; border: 1px solid red; padding: 10px;">
        <strong>Error HTTP <?php echo (int)$datos['http']; ?>:</strong>
        <?php echo htmlspecialchars(json_encode($datos['error'], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)); ?>
    </div>
<?php endif; ?>

<?php
// Extraemos el artículo de los datos que pasó el controlador
$art = $datos['articulo'] ?? null;
?>

<?php if ($art): ?>
    <form action="<?php echo RUTA_URL; ?>/articulos/editar/<?php echo $art['id_articulo']; ?>" method="POST">
        <div style="margin-top: 10px;">
            <label>Título del artículo:</label><br>
            <input type="text" name="titulo" value="<?php echo htmlspecialchars($art['titulo'] ?? ''); ?>" required style="width: 300px;">
        </div>

        <div style="margin-top: 20px;">
            <button type="submit">Guardar Cambios</button>
            <a href="<?php echo RUTA_URL; ?>/articulos/index">Cancelar</a>
        </div>
    </form>
<?php else: ?>
    <p>No se pudo cargar el artículo para editar.</p>
    <p><a href="<?php echo RUTA_URL; ?>/articulos/index">Volver al listado</a></p>
<?php endif; ?>

<?php require_once RUTA_APP.'/vistas/inc/header.php'; ?>