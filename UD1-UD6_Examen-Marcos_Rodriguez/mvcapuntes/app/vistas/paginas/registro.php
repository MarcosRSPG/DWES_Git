<?php require RUTA_APP.'/vistas/inc/header.php'; ?>

        <form method="POST">
            <h1>Registrar Mascota</h1>

            <?php if (!empty($datos['error'])) { ?>
                <div class="mensaje-error"><?php echo htmlspecialchars($datos['error']); ?></div>
            <?php } ?>

            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Tipo:</label>
            <input type="text" name="tipo" required>

            <label>Fecha nacimiento:</label>
            <input type="date" name="fecha_nacimiento">

            <label>Url de la foto:</label>
            <input type="text" name="foto_url">

            <label>ID Persona:</label>
            <input type="text" name="id_persona" required>

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>

<?php require RUTA_APP.'/vistas/inc/footer.php'; ?>
