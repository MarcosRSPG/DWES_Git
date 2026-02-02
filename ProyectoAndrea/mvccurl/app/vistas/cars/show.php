<?php require_once RUTA_APP.'/vistas/inc/header.php'; ?>

<h1><?php echo $datos['titulo'] ?? 'Ficha'; ?></h1>

<?php if (!empty($datos['error'])): ?>
    <pre>
Error HTTP <?php echo (int)$datos['http']; ?>:
<?php echo htmlspecialchars(json_encode($datos['error'], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)); ?>
  </pre>

    <p><a href="<?php echo rtrim(RUTA_URL,'/'); ?>/cars/index">Volver al listado</a></p>

    <?php require_once RUTA_APP.'/vistas/inc/footer.php'; ?>
    <?php return; ?>
<?php endif; ?>

<?php $car = $datos['car'] ?? []; ?>

<ul>
    <li><strong>ID:</strong> <?php echo htmlspecialchars($car['id'] ?? ''); ?></li>
    <li><strong>Brand:</strong> <?php echo htmlspecialchars($car['brand'] ?? ''); ?></li>
    <li><strong>Model:</strong> <?php echo htmlspecialchars($car['model'] ?? ''); ?></li>
    <li><strong>Color:</strong> <?php echo htmlspecialchars($car['color'] ?? ''); ?></li>
    <li><strong>Owner:</strong> <?php echo htmlspecialchars($car['owner'] ?? ''); ?></li>
</ul>

<h3>JSON recibido</h3>
<pre><?php echo htmlspecialchars(json_encode($car, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)); ?></pre>

<p><a href="<?php echo rtrim(RUTA_URL,'/'); ?>/cars/index">Volver al listado</a></p>

<?php require_once RUTA_APP.'/vistas/inc/footer.php'; ?>
