<?php
$restaurante = $_SESSION['restaurante'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Restaurante</title>
    <link rel="stylesheet" href="<?php echo WEB_URL; ?>/css/estilos.css">
</head>
<body>
    <div class="nav">
        <a href="<?php echo WEB_URL; ?>/auth/logout">Cerrar Sesión</a>
    </div>

    <div class="welcome-box">
        <h1>Bienvenido, <?php echo htmlspecialchars($restaurante['Nombre']); ?></h1>

        <p><strong>Correo:</strong> <?php echo htmlspecialchars($restaurante['Correo']); ?></p>
        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($restaurante['Telefono']); ?></p>
        <p><strong>Dirección:</strong> <?php echo htmlspecialchars($restaurante['Direccion']); ?></p>
    </div>

    <h2>Menú Principal</h2>
    <ul class="menu-links">
        <li><a href="<?php echo WEB_URL; ?>/productos/index">🛍️ Ver Productos</a></li>
        <li><a href="<?php echo WEB_URL; ?>/carrito/index">🛒 Mi Carrito</a></li>
    </ul>
</body>
</html>