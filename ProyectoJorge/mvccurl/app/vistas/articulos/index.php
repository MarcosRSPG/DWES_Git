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
        <th>ID_Articulo</th><th>Título</th><th>Descripción</th><th>Foto</th><th>Cosa</th><th>Acción</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach (($datos['articulos'] ?? []) as $c): ?>
        <tr>
            <td><?php echo htmlspecialchars($c['id_articulo'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($c['titulo'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($c['descripcion'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($c['foto'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($c['cosa'] ?? ''); ?></td>
            <td>
                <a href="<?php echo rtrim(RUTA_URL,'/'); ?>/articulos/show/<?php echo (int)($c['id_articulo'] ?? 0); ?>">Ver ficha</a>
                <a href="<?php echo rtrim(RUTA_URL,'/'); ?>/articulos/update/<?php echo (int)($c['id_articulo'] ?? 0); ?>">Editar ficha</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php require_once RUTA_APP.'/vistas/inc/footer.php'; ?>
