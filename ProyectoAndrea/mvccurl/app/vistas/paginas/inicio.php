<?php require_once RUTA_APP.'/vistas/inc/header.php';?>
<h1><?php echo $datos['titulo'];?></h1>
<h2>Página de inicio del Framework php MVC</h2>

<a href="<?=RUTA_URL;?>">Inicio</a>
<a href="<?=RUTA_URL."/Articulos/index";?>">Artículos</a>

<?php require_once RUTA_APP.'/vistas/inc/footer.php';?>

