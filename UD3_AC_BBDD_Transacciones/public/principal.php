<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__.'/../src/GestorJugarVideojuego.php';

$mensajeTransaccion = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaccion'])) {
    $gestor = new GestorJugarVideojuego();

    try {
        if ($_POST['transaccion'] === 'A') {
            $registros = [
                [
                    'name' => 'Zelda BOTW',
                    'genero' => 'Aventura',
                    'plataforma' => 'Switch',
                    'fecha_lanzamiento' => '2017-03-03',
                    'precio' => 59.99,
                    'jugado' => 1,
                ],
                [
                    'name' => 'Hades',
                    'genero' => 'Roguelike',
                    'plataforma' => 'PC',
                    'fecha_lanzamiento' => '2020-09-17',
                    'precio' => 24.99,
                    'jugado' => 0,
                ],
            ];
            $gestor->ejecutarAltaSimultanea($registros);
            $mensajeTransaccion = 'Transacción A realizada correctamente. Se han insertado todos los registros.';
        } elseif ($_POST['transaccion'] === 'B') {
            $registros = [
                [
                    'name' => 'JuegoDuplicado',
                    'genero' => 'Acción',
                    'plataforma' => 'PC',
                    'fecha_lanzamiento' => '2024-01-01',
                    'precio' => 19.99,
                    'jugado' => 0,
                ],
                [
                    'name' => 'JuegoDuplicado',
                    'genero' => 'Acción',
                    'plataforma' => 'PS5',
                    'fecha_lanzamiento' => '2024-02-01',
                    'precio' => 39.99,
                    'jugado' => 0,
                ],
            ];
            $gestor->ejecutarAltaSimultanea($registros);
            $mensajeTransaccion = 'Esto no debería verse si el UNIQUE funciona, se lanzará excepción.';
        }
    } catch (Exception $e) {
        $mensajeTransaccion = 'Error en la transacción (rollback realizado): '.$e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="style.css">
	<title>Página principal de aplicación</title>
</head>
<body>
	<h1>Gestor de Videojuegos</h1>
	<p>Usuario: <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong></p>
	<p><a href="tabla_videojuegos.php">Ver / gestionar videojuegos</a></p>
	<hr>

    <h2>Pruebas de transacciones</h2>

    <?php if (!empty($mensajeTransaccion)) { ?>
        <p><strong><?php echo htmlspecialchars($mensajeTransaccion); ?></strong></p>
    <?php } ?>

    <form method="post" action="">
        <button type="submit" name="transaccion" value="A"
            style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px;">
            Transacción A: registros válidos
        </button>
        <button type="submit" name="transaccion" value="B"
            style="background-color: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px;">
            Transacción B: registros erróneos (UNIQUE)
        </button>
    </form>

</body>
</html>
