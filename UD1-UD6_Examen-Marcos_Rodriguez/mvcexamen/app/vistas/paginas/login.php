<?php
use Mrs\Webcliente\ClienteAPI;

$cliente = new ClienteAPI();
$error = '';
$ok = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $clave = trim($_POST['clave'] ?? '');

    if ($email === '' || $clave === '') {
        $error = 'Correo y contraseña son obligatorios';
    } else {
        $respuesta = $cliente->post('controladorauth/login', [
            'email' => $email,
            'clave' => $clave,
        ]);

        if ($respuesta['success'] && isset($respuesta['data']['veterinario'])) {
            $_SESSION['veterinario'] = $respuesta['data']['veterinario'];
            $ok = 'Login correcto, redirigiendo...';
            header('Refresh: 1; url='.RUTA_URL.'/paginas/inicio');
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
    <title>Login - Mascotas</title>
    <link rel="stylesheet" href="<?php echo RUTA_URL; ?>/css/estilos.css">
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
            <input type="email" name="email" required>

            <label>Contraseña:</label>
            <input type="password" name="clave" required>

            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>