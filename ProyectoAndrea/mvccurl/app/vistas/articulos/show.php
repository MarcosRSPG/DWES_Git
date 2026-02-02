<?php require_once RUTA_APP.'/vistas/inc/header.php'; ?>

<h1><?php echo $datos['titulo'] ?? 'Ficha'; ?></h1>

<?php if (!empty($datos['error'])): ?>
    <pre>
Error HTTP <?php echo (int)$datos['http']; ?>:
<?php echo htmlspecialchars(json_encode($datos['error'], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)); ?>
  </pre>

    <p><a href="<?php echo rtrim(RUTA_URL,'/'); ?>/articulos/index">Volver al listado</a></p>

    <?php require_once RUTA_APP.'/vistas/inc/footer.php'; ?>
    <?php return; ?>
<?php endif; ?>

<?php $car = $datos['articulo'] ?? []; ?>

<ul>
    <li><strong>ID:</strong> <?php echo htmlspecialchars($car['id_articulo'] ?? ''); ?></li>
    <li><strong>Brand:</strong> <?php echo htmlspecialchars($car['titulo'] ?? ''); ?></li>
</ul>

<h3>JSON recibido</h3>
<pre><?php echo htmlspecialchars(json_encode($car, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)); ?></pre>

<p><a href="<?php echo rtrim(RUTA_URL,'/'); ?>/articulos/index">Volver al listado</a></p>

<?php require_once RUTA_APP.'/vistas/inc/footer.php'; ?>
