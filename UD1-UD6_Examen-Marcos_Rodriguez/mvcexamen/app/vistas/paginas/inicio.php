<?php
$veterinario = $_SESSION['veterinario'];
session_start();
require_once '../vendor/autoload.php';

if (!isset($_SESSION['veterinario'])) {
    header('Refresh: 1; url='.RUTA_URL.'/paginas/login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Mascotas</title>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/css/estilos.css">
</head>
<body>
    <div class="nav">
        <a href="<?php echo RUTA_URL; ?>/auth/logout">Cerrar Sesión</a>
    </div>

    <div class="welcome-box">
        <h1>Bienvenido, <?php echo htmlspecialchars($veterinario['nombre']); ?></h1>
</div>

    <h2>Menú Principal</h2>
    <ul class="menu-links">
        <li><a href="<?php echo RUTA_URL; ?>/mascotas/index">Mascotas</a></li>
        <li><a href="<?php echo RUTA_URL; ?>/aboutus/index">Quienes somos</a></li>
    </ul>
</body>
</html>