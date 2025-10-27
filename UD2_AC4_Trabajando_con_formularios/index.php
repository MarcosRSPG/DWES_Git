<?php
ini_set('session.save_path', 'E:\\Ampps\\tmp');
session_start();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./css/styles.css">
    <title>Trabajando con formularios</title>
  </head>
  <body>
    <header>
      <h1>Trabajando con formularios</h1>
    </header>

    <main>
      <?php
      if (!empty($_SESSION['flash_lineas']) && is_array($_SESSION['flash_lineas'])) {
          echo '<div class="alert" role="alert">'.
               nl2br(htmlspecialchars(implode("\n", $_SESSION['flash_lineas']), ENT_QUOTES, 'UTF-8')).
               '</div>';
          unset($_SESSION['flash_lineas']);
      }
?>

      <form action="procesar.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="submitted" value="1">

        <label for="nombre">Título:</label>
        <input type="text" id="nombre" name="nombre" />

        <label for="plataforma">Plataforma:</label>
        <input type="text" id="plataforma" name="plataforma" />

        <label for="genero">Género:</label>
        <input type="text" id="genero" name="genero" />

        <label for="fechaLanzamiento">Fecha de lanzamiento:</label>
        <input type="date" id="fechaLanzamiento" name="fechaLanzamiento" />

        <label for="precio">Precio (€):</label>
        <input type="number" id="precio" name="precio" step="0.01" />

        <label for="fotografia">PDF (opcional):</label>
        <input type="file" id="fotografia" name="fotografia" accept="application/pdf" />

        <input type="submit" value="Enviar" />
      </form>
    </main>
  </body>
</html>
