<?php
use Mrs\WebCliente\ClienteAPI;

$cliente = new ClienteAPI();
$error = '';
$ok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $clave = trim($_POST['clave'] ?? '');

    if ($correo === '' || $clave === '') {
        $error = 'Correo y contraseña son obligatorios';
    } else {
        $respuesta = $cliente->post('controladorauth/login', [
            'correo' => $correo,
            'clave' => $clave,
        ]);

        if ($respuesta['success'] && isset($respuesta['data']['restaurante'])) {
            $_SESSION['restaurante'] = $respuesta['data']['restaurante'];
            $ok = 'Login correcto, redirigiendo...';
            header('Refresh: 1; url='.WEB_URL.'/paginas/index');
        } else {
            $error = $respuesta['data']['error'] ?? 'Error en el login';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Restaurante</title>
    <link rel="stylesheet" href="<?php echo WEB_URL; ?>/css/estilos.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>

        <?php if ($error) { ?>
            <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
        <?php } ?>

        <?php if ($ok) { ?>
            <div class="mensaje-success"><?php echo htmlspecialchars($ok); ?></div>
        <?php } ?>

        <form method="POST">
            <label>Correo:</label>
            <input type="email" name="correo" required>

            <label>Contraseña:</label>
            <input type="password" name="clave" required>

            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>