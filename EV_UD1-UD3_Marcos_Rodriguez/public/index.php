<?php
session_start();
require_once __DIR__.'/../tools/login.php';
require_once __DIR__.'/../src/GestorLogs.php';


$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';


    $gestorLogs = new gestorLogs();
    $login = new Login();

    try {
        $user = $login->autenticar($usuario, $password);
        if ($user) {
            $_SESSION['usuario'] = $user['username'];
            $_SESSION['usuario_id'] = $user['id'];
            header('Location: ListadoMascotas.php');
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
    } catch (Exception $e) {
        $error = 'Error al autenticar: '.$e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
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
        <label for="inputUsuario" id="lblUsuario">Usuario:</label>
        <input type="text" name="usuario" id="inputUsuario" required autocomplete="username" />

        <label for="inputPassword" id="lblPassword">Contraseña:</label>
        <input
          type="password"
          name="password"
          id="inputPassword"
          minlength="4"
          required
          autocomplete="current-password"
        />

        <input type="submit" id="submitLogin" value="Login" />
    </form>
</body>
</html>
