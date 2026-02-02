<?php require_once RUTA_APP.'/vistas/inc/header.php'; ?>

    <h2>Login API</h2>

    <form action="<?php echo RUTA_URL; ?>/login/entrar" method="POST">
        <label>Usuario:</label>
        <input type="text" name="usuario" required>

        <label>Contraseña:</label>
        <input type="password" name="pass" required>

        <button type="submit">Conectar</button>
    </form>

<?php require_once RUTA_APP.'/vistas/inc/footer.php'; ?>