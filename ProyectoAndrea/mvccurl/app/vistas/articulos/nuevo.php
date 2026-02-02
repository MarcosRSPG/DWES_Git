<?php require_once RUTA_APP.'/vistas/inc/header.php'; ?>

    <h1><?php echo $datos['titulo']; ?></h1>

<?php if (!empty($datos['error'])): ?>
    <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 10px;">
        <strong>Error <?php echo $datos['http']; ?>:</strong>
        <?php echo htmlspecialchars(json_encode($datos['error'])); ?>
    </div>
<?php endif; ?>

    <form action="<?php echo RUTA_URL; ?>/articulos/nuevo" method="POST">
        <div style="margin-bottom: 15px;">
            <label for="titulo">Título del Artículo:</label><br>
            <input type="text" name="titulo" id="titulo" required style="width: 300px; padding: 5px;">
        </div>

        <button type="submit" style="padding: 5px 15px; background-color: #4CAF50; color: white; border: none; cursor: pointer;">
            Guardar Artículo
        </button>
        <a href="<?php echo RUTA_URL; ?>/articulos/index" style="margin-left: 10px;">Cancelar</a>
    </form>

<?php require_once RUTA_APP.'/vistas/inc/footer.php'; ?>