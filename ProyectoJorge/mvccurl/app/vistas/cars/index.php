<?php require_once RUTA_APP.'/vistas/inc/header.php'; ?>
<h1><?php echo $datos['titulo'] ?? 'Listado'; ?></h1>
<p><strong>Servidor API:</strong> <code><?php echo htmlspecialchars(API_BASE_URL); ?></code></p>
<?php if (!empty($datos['error'])): ?>
    <pre>
Error HTTP <?php echo (int)$datos['http']; ?>:
<?php echo htmlspecialchars(json_encode($datos['error'], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT)); ?>
  </pre>
<?php endif; ?>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
    <tr>
        <th>ID</th><th>Brand</th><th>Model</th><th>Color</th><th>Owner</th><th>Acción</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach (($datos['cars'] ?? []) as $c): ?>
        <tr>
            <td><?php echo htmlspecialchars($c['id'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($c['brand'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($c['model'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($c['color'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($c['owner'] ?? ''); ?></td>
            <td>
                <a href="<?php echo rtrim(RUTA_URL,'/'); ?>/cars/show/<?php echo (int)($c['id'] ?? 0); ?>">Ver ficha</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php require_once RUTA_APP.'/vistas/inc/footer.php'; ?>
