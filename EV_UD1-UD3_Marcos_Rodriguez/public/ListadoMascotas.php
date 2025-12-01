<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

require_once __DIR__.'/../src/GestorMascotas.php';
require_once __DIR__.'/../src/GestorPersonas.php';
require_once __DIR__.'/../src/GestorLogs.php';

$gestorMascotas = new GestorMascotas();
$gestorPersonas = new GestorPersonas();
$gestorLogs = new gestorLogs();

$mensaje = '';
$mascotaEditar = null;

if (isset($_GET['accion']) && $_GET['accion'] === 'borrar' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    try {
        $gestorMascotas->eliminar($id);
        $mensaje = "Mascota con ID $id borrado correctamente.";
    } catch (Exception $e) {
        $mensaje = 'Error al borrar: '.$e->getMessage();
    }
}

$lista = $gestorMascotas->listar();

if (isset($_GET['orden']) && $_GET['orden'] === 'NOM_ASC') {
    try {
        $columna = array_column($lista, "nombre");
        array_multisort($columna, SORT_ASC, $lista);
        $gestorLogs->insertar("Listado Mascotas: NOM_ASC");
    } catch (Exception $e) {
        $mensaje = 'Error al obtener mascota: '.$e->getMessage();
    }
}
if (isset($_GET['orden']) && $_GET['orden'] === 'NOM_DES') {
    try {
        $columna = array_column($lista, "nombre");
        array_multisort($columna, SORT_DESC, $lista);
        $gestorLogs->insertar("Listado Mascotas: NOM_DESC");
    } catch (Exception $e) {
        $mensaje = 'Error al obtener mascota: '.$e->getMessage();
    }
}
if (isset($_GET['orden']) && $_GET['orden'] === 'TIPO_ASC') {
    try {
        $columna = array_column($lista, "tipo");
        array_multisort($columna, SORT_ASC, $lista);
        $gestorLogs->insertar("Listado Mascotas: TIPO_ASC");
    } catch (Exception $e) {
        $mensaje = 'Error al obtener mascota: '.$e->getMessage();
    }
}
if (isset($_GET['orden']) && $_GET['orden'] === 'TIPO_DES') {
    try {
        $columna = array_column($lista, "tipo");
        array_multisort($columna, SORT_DESC, $lista);
        $gestorLogs->insertar("Listado Mascotas: TIPO_DESC");
    } catch (Exception $e) {
        $mensaje = 'Error al obtener mascota: '.$e->getMessage();
    }
}


?>

<!DOCTYPE html>
<html lang="es"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado Mascotas</title>
    <link href="css/bootstrap.min_002.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
    .container {
        /* Si quieres que el contenedor ocupe todo el ancho disponible, puedes eliminar max-width */
        max-width: 800px;
        margin: auto;
        padding: 20px;
        display: flex;           /* Añade flex para que las tarjetas se muestren en línea */
        flex-wrap: wrap;         /* Permite que las tarjetas se ajusten y pasen a la siguiente línea si no hay espacio */
        justify-content: space-between; /* Espacio entre tarjetas. Puedes ajustar según prefieras */
    }
    .card {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
        /* Ancho de las tarjetas. Puedes ajustar según prefieras */
        width: calc(33% - 10px); /* Esto asume que quieres 3 tarjetas por fila y resta 20px por el espacio entre tarjetas */
        box-sizing: border-box; /* Asegura que el padding y el borde se incluyan en el ancho total de la tarjeta */
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

</head>
<body>
    <div class="container">
        <h1>Listado de Mascotas</h1>
         <!-- Fragmento para mostrar mensajes -->
        <div>
            Ordenar por:
            <a href="ListadoMascotas.php?orden=NOM_ASC" class="btn btn-success mt-2">Nombre ASC</a> |
            <a href="ListadoMascotas.php?orden=NOM_DES" class="btn btn-warning">Nombre DES</a> |
            <a href="ListadoMascotas.php?orden=TIPO_ASC" class="btn btn-success mt-2">Tipo ASC</a> |
            <a href="ListadoMascotas.php?orden=TIPO_DES" class="btn btn-warning">Tipo DES</a>
        </div>
        <?php foreach ($lista as $linea) {
        $ms = Mascota::fromArray($linea) ?>
        <div class="card">
            <div class="card-content">
                <img src=<?php echo $ms->getFoto_url();?> alt="Foto de <?php echo $ms->getNombre();?>" class="img-fluid" style="max-width: 200px;">
                <div class="card-text">
                    <strong>Responsable:</strong><?php echo $gestorPersonas->obtenerPorId($ms->getId_persona())->getNombre();?><br>
                    <strong>Nombre:</strong> <?php echo $ms->getNombre();?><br>
                    <strong>Tipo:</strong> <?php echo $ms->getTipo();?><br>
                    <strong>Fecha de Nacimiento:</strong> <?php echo $ms->getFecha_nacimiento();?>
                </div>
            </div>
        <div>
                <a href="EditarFotoMascota.php?accion=editar&id=<?php echo $ms->getId(); ?>" class="btn btn-primary">Cambiar Foto</a> |
                <a href="ListadoMascotas.php?accion=borrar&id=<?php echo $ms->getId(); ?>"
                       onclick="return confirm('¿Seguro que quieres borrar este registro?');"
                        class="btn btn-danger">Eliminar</a>
            </div>
        </div>
        <?php } ?>
        
    </div>
    <div class="text-center mt-3">
    <h1>Listados Efectuados (LOGs PERSISTENTES)</h1>
    <table style="margin-left: auto; margin-right: auto;">
        <thead>
            <tr>
                <th>Acción</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($gestorLogs->listar() as $log) { ?>
            <tr>
                <td><?php echo $log->getAccion()?></td>
                <td><?php echo $log->getFecha()?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <h1>Navegación Web (LOGs de SESIÓN)</h1>
    <table style="margin-left: auto; margin-right: auto;">
        <thead>
            <tr>
                <th>Acción</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Acción 1</td>
                <td>Listado mascotas</td>
            </tr>
            <tr>
                <td>Acción 2</td>
                <td>Cambio foto mascota</td>
            </tr>
        </tbody>
    </table>
</div>

    <div class="text-center mt-3">
            <a href="RegistroMascota.php" class="btn btn-success mt-2">Registrar Mascota</a> | 
            <a href="logout.php" class="btn btn-secondary mt-2">Cerrar Sesión</a>
    </div>
    <script src="Listado%20Mascotas_files/bootstrap.min.js"></script>


</body></html>