<?php require_once dirname(dirname(__DIR__)) . '/vistas/inc/header.php';?>
<h1><?php echo $titulo;?></h1>
<h2>Página de inicio del Framework php MVC</h2>

<a href="<?=RUTA_URL;?>">Inicio</a>
<a href="<?=RUTA_URL."/Articulos/index";?>">Artículos</a>

<?php require_once dirname(dirname(__DIR__)) . '/vistas/inc/footer.php';?>

