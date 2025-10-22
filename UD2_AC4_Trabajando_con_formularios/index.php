<?php
require_once './php/jugarVideojuego.php';
$nuevoVideojuego = new jugarVideojuegos();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Trabajando con formularios</title>
  </head>
  <body>
    <header>
      <h1>Trabajando con formularios</h1>
    </header>
    <main>
      <form action="#" method="post">
        <label for="nombre">Título:</label>
        <input type="text" id="nombre" name="nombre" required /><br /><br />

        <label for="plataforma">Plataforma:</label>
        <input type="text" id="plataforma" name="plataforma" required /><br /><br />

        <label for="genero">Género:</label><br />
        <input type="text" id="genero" name="genero" required /><br /><br />

        <label for="fechaLanzamiento">Fecha de lanzamiento:</label><br />
        <input type="date" id="fechaLanzamiento" name="fechaLanzamiento" required /><br /><br />
        <label for="precio">Precio:</label><br />
        <input type="number" id="precio" name="precio" step="0.01" required /><br /><br />
        <label id="IVA">IVA: <?php echo $nuevoVideojuego::IVA; ?></label><br />
        <label for="minTiempo">Tiempo mínimo de juego (en horas): <?php echo $nuevoVideojuego::MINTIEMPO; ?></label><br />
        <label for="maxTiempo">
        Tiempo máximo de juego (en horas): <?php echo $nuevoVideojuego::MAXTIEMPO; ?></label><br /><br />
        </label>
        <input type="submit" value="Enviar" />
      </form>
  </body>
</html>
