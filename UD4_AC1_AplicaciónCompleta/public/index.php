<?php

require_once __DIR__.'/../vendor/autoload.php';

use Mrs\tools\Login;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['email'] ?? '';
    $clave = $_POST['clave'] ?? '';

    $login = new Login();

    try {
        $user = $login->autenticar($correo, $clave);
        if ($user) {
            session_start();
            $_SESSION['correo'] = $user['Correo'];
            header('Location: principal.php');
            exit;
        } else {
            $error = 'Correo o clave incorrectos.';
        }
    } catch (Exception $e) {
        $error = 'Error al autenticar: '.$e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="style.css">
	<title>Archivo de entrada a la aplicación: formulario de login</title>
</head>
<body>
	<h1>LOGIN</h1>

    <?php if (!empty($error)) { ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>

	<form id="formLogin" method="post" action="">
        <label for="inputCorreo" id="lblCorreo">Correo:</label>
        <input type="email" name="email" id="inputCorreo" required autocomplete="email" />

        <label for="inputPassword" id="lblPassword">Clave:</label>
        <input
          type="password"
          name="clave"
          id="inputPassword"
          minlength="4"
          required
          autocomplete="current-clave"
        />

        <input type="submit" id="submitLogin" value="Login" />
    </form>
</body>
</html>
