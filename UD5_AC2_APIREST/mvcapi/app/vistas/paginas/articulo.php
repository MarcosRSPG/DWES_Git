<?php require_once dirname(dirname(__DIR__)) . '/vistas/inc/header.php';?>
<h1><?php echo $titulo ?? 'Artículos';?></h1>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach (($articulos ?? []) as $articulo): ?>
        <tr>
            <td><?php echo htmlspecialchars($articulo->id_articulo ?? ''); ?></td>
            <td><?php echo htmlspecialchars($articulo->titulo ?? ''); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php require_once dirname(dirname(__DIR__)) . '/vistas/inc/footer.php';?>
