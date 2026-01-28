<?php require_once dirname(dirname(__DIR__)) . '/vistas/inc/header.php';?>
<h1><?php echo $titulo;?></h1>
<ul>
    <?php foreach($articulos as $articulo): ?>
        <li><?php echo $articulo->titulo; ?>
    <?php endforeach; ?>
</ul>
<?php require_once dirname(dirname(__DIR__)) . '/vistas/inc/footer.php';?>