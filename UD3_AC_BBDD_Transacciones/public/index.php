<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="style.css">
	<title>Archivo de entrada a la aplicación: formulario de login</title>
</head>
<body>
<!--	<h1> Incluye aquí tu formulario de Login </h1> -->
<!--	<p> Si el login es correcto redireciona al usuario a "principal.php", sino no le dejes pasar. </p> -->

	<h1>LOGIN</h1>
	<form id="formLogin">
        <label for="inputUsuario" id="lblUsuario">Usuario:</label>
        <input type="name" id="inputUsuario" required autocomplete="username" />
        <label for="inputPassword" id="lblPassword">Contraseña:</label>
        <input
          type="password"
          id="inputPassword"
          minlength="8"
          required
          autocomplete="current-password"
        />
        <input type="submit" id="submitLogin" value="Login" />
      </form>
</body>
</html>