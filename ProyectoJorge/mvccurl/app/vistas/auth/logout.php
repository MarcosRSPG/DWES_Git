<?php require_once RUTA_APP.'/vistas/inc/header.php'; ?>

<h1><?php echo $datos['titulo'] ?? 'Logout'; ?></h1>

<p>Has cerrado sesión correctamente.</p>

<p><a href="<?php echo rtrim(RUTA_URL,'/'); ?>/Auth/login">Ir al login</a></p>

<?php require_once RUTA_APP.'/vistas/inc/footer.php'; ?>
