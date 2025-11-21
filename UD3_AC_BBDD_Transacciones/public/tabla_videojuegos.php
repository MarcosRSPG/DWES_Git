<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__.'/../src/GestorJugarVideojuego.php';

$gestor = new GestorJugarVideojuego();
$mensaje = '';
$videojuegoEditar = null;

if (isset($_GET['accion']) && $_GET['accion'] === 'borrar' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    try {
        $gestor->eliminar($id);
        $mensaje = "Videojuego con ID $id borrado correctamente.";
    } catch (Exception $e) {
        $mensaje = 'Error al borrar: '.$e->getMessage();
    }
}

if (isset($_GET['accion']) && $_GET['accion'] === 'editar' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    try {
        $videojuegoEditar = $gestor->obtenerPorId($id);
        if (!$videojuegoEditar) {
            $mensaje = "No se ha encontrado el videojuego con ID $id.";
        }
    } catch (Exception $e) {
        $mensaje = 'Error al obtener videojuego: '.$e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;

    $datos = [
        'name' => $_POST['name'] ?? '',
        'genero' => $_POST['genero'] ?? null,
        'plataforma' => $_POST['plataforma'] ?? null,
        'fecha_lanzamiento' => $_POST['fecha_lanzamiento'] ?? null,
        'precio' => $_POST['precio'] ?? null,
        'jugado' => isset($_POST['jugado']) ? 1 : 0,
    ];

    try {
        if ($id === null) {
            $gestor->insertar($datos);
            $mensaje = 'Videojuego insertado correctamente.';
        } else {
            $gestor->actualizar($id, $datos);
            $mensaje = 'Videojuego actualizado correctamente.';
        }
    } catch (Exception $e) {
        $mensaje = 'Error al guardar: '.$e->getMessage();
    }
}

$lista = $gestor->listar();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<link rel="stylesheet" href="style.css">
    <title>Visualización de videojuegos</title>
</head>
<body>
    <h1>Visualización de videojuegos</h1>
    <p><a href="principal.php">Volver a la principal</a></p>

    <?php if (!empty($mensaje)) { ?>
        <p><strong><?php echo htmlspecialchars($mensaje); ?></strong></p>
    <?php } ?>

    <h2><?php echo $videojuegoEditar ? 'Editar videojuego' : 'Nuevo videojuego'; ?></h2>

    <form method="post" action="tabla_videojuegos.php">
        <?php if ($videojuegoEditar) { ?>
            <input type="hidden" name="id" value="<?php echo $videojuegoEditar->getId(); ?>">
        <?php } ?>

        <label>Nombre:</label>
        <input type="text" name="name" required
               value="<?php echo $videojuegoEditar ? htmlspecialchars($videojuegoEditar->getName()) : ''; ?>">
        <br>

        <label>Género:</label>
        <input type="text" name="genero"
               value="<?php echo $videojuegoEditar ? htmlspecialchars($videojuegoEditar->getGenero()) : ''; ?>">
        <br>

        <label>Plataforma:</label>
        <input type="text" name="plataforma"
               value="<?php echo $videojuegoEditar ? htmlspecialchars($videojuegoEditar->getPlataforma()) : ''; ?>">
        <br>

        <label>Fecha lanzamiento:</label>
        <input type="date" name="fecha_lanzamiento"
               value="<?php echo $videojuegoEditar ? htmlspecialchars($videojuegoEditar->getFechaLanzamiento()) : ''; ?>">
        <br>

        <label>Precio:</label>
        <input type="number" step="0.01" name="precio"
               value="<?php echo $videojuegoEditar ? htmlspecialchars($videojuegoEditar->getPrecio()) : ''; ?>">
        <br>

        <label>Jugado:</label>
        <input type="checkbox" name="jugado"
            <?php echo ($videojuegoEditar && $videojuegoEditar->getJugado()) ? 'checked' : ''; ?>>
        <br><br>

        <button type="submit" name="guardar">Guardar</button>
    </form>

    <h2>Listado de videojuegos</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Género</th>
            <th>Plataforma</th>
            <th>Fecha lanzamiento</th>
            <th>Precio</th>
            <th>Jugado</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($lista as $vj) { ?>
            <tr>
                <td><?php echo $vj->getId(); ?></td>
                <td><?php echo htmlspecialchars($vj->getName()); ?></td>
                <td><?php echo htmlspecialchars($vj->getGenero()); ?></td>
                <td><?php echo htmlspecialchars($vj->getPlataforma()); ?></td>
                <td><?php echo htmlspecialchars($vj->getFechaLanzamiento()); ?></td>
                <td><?php echo htmlspecialchars($vj->getPrecio()); ?></td>
                <td><?php echo $vj->getJugado() ? 'Sí' : 'No'; ?></td>
                <td>
                    <a href="tabla_videojuegos.php?accion=editar&id=<?php echo $vj->getId(); ?>">Editar</a> |
                    <a href="tabla_videojuegos.php?accion=borrar&id=<?php echo $vj->getId(); ?>"
                       onclick="return confirm('¿Seguro que quieres borrar este registro?');">
                       Borrar
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>

</body>
</html>
