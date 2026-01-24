<?php
if (!function_exists('h')) {
    function h(string $s): string
    {
        return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }
}
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aplicacion MVC</title>
  <link rel="stylesheet" href="<?php echo h(RUTA_URL); ?>/public/css/estilos.css">
</head>
<body>
<header class="topbar">
  <div class="wrap">
    <h1 class="brand"><?php echo h(NOMBRESITIO); ?></h1>
    <nav class="nav">
      <?php if (!empty($_SESSION['correo'])) { ?>
        <span class="user">👤 <?php echo h((string) $_SESSION['correo']); ?></span>
        <a href="<?php echo h(RUTA_URL); ?>/Categoria/categorias">Categorías</a>
        <a href="<?php echo h(RUTA_URL); ?>/Carrito/listar">Carrito</a>
        <a href="<?php echo h(RUTA_URL); ?>/Restaurante/logout">Salir</a>
      <?php } else { ?>
        <a href="<?php echo h(RUTA_URL); ?>/Paginas/login">Login</a>
      <?php } ?>
    </nav>
  </div>
</header>
<main class="wrap">
