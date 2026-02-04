<?php require RUTA_APP.'/vistas/inc/header.php'; ?>

        <section class="card">
            <h1>Iniciar Sesión</h1>

            <?php if (!empty($datos['error'])) { ?>
                <div class="mensaje-error"><?php echo htmlspecialchars($datos['error']); ?></div>
            <?php } ?>

            <form method="POST">
                <label>Correo:</label>
                <input type="email" name="email" required>

                <label>Contraseña:</label>
                <input type="password" name="clave" required>

                <button type="submit" class="btn btn-primary">Entrar</button>
            </form>
        </section>

<?php require RUTA_APP.'/vistas/inc/footer.php'; ?>