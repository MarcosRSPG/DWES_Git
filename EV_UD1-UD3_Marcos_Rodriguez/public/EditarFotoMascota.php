<?php

    session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}
    require_once __DIR__.'/../src/GestorMascotas.php';
    $gestorMascotas = new GestorMascotas();
    $mascota = null;

if (isset($_GET['accion']) && $_GET['accion'] === 'editar' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];


    try {
        $mascota = $gestorMascotas->obtenerPorId($id);
    } catch (Exception $e) {
        $mensaje = 'Error al obtener: '.$e->getMessage();
    }
}
if (isset($_GET['accion']) && $_GET['accion'] === 'ejecutar' && isset($_GET['id'])) {
    
    $id = (int) $_GET['id'];
    try {
        $mascota = $gestorMascotas->obtenerPorId($id);

 if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] === UPLOAD_ERR_OK) {
    $nombreArchivo = basename($_FILES['fotografia']['name']);
    $base = "../public/img";
    $destino = $base.'/'.$nombreArchivo;
    @move_uploaded_file($_FILES['fotografia']['tmp_name'], $destino);
    
    $mascota->setFoto_url($destino);
    $gestorMascotas->actualizar($id, $mascota->toArray());
}
    error_log('La mascota es: '.$mascota->getFoto_url());
    } catch (Exception $e) {
        $mensaje = 'Error al obtener: '.$e->getMessage();
    }
}

    
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Foto Mascota</title>

    <link href="css/bootstrap.min_002.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* No modificamos .container de Bootstrap */
        .form-wrapper {
            max-width: 700px;
            margin: 20px auto;
        }
    </style>
</head>
<body>

    <div class="container form-wrapper">
        <div class="row justify-content-center">
            <div class="col-md-6 mb-4">
                <div class="card p-4">

                    <h2 class="mb-3">Cambiar Foto</h2>

                    <p><strong>Nombre:</strong> <?php echo $mascota->getNombre(); ?></p>
                    <p><strong>Tipo:</strong> <?php echo $mascota->getTipo();?></p>
                    <p><strong>Fecha de Nacimiento:</strong> <?php echo $mascota->getFecha_nacimiento();?></p>

                    <img src=<?php echo $mascota->getFoto_url();?>
                         alt="Foto de <?php echo $mascota->getNombre();?>"
                         class="img-fluid mb-3"
                         style="max-width: 200px;">

                    <form action="EditarFotoMascota.php?accion=ejecutar&id=<?php echo $id?>" method="post" enctype="multipart/form-data">

                        <input type="hidden" name="id_mascota" value="4">

                        <div class="mb-3">
                            <label for="fotografia" class="form-label">Seleccione nueva foto:</label>
                            <input type="file" name="fotografia" id="fotografia" class="form-control">
                        </div>

                        <button type="submit" name="cambiarFoto" class="btn btn-primary w-100">
                            Cambiar Foto
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>

</body>
</html>