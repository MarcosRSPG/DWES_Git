<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}
    require_once __DIR__.'/../src/GestorMascotas.php';
    require_once __DIR__.'/../src/GestorPersonas.php';

    $gestorMascotas = new GestorMascotas();
    $gestorPersonas = new GestorPersonas();


    $listaPersonas = $gestorPersonas->listar();

    

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {


    if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] === UPLOAD_ERR_OK) {
    $nombreArchivo = basename($_FILES['fotografia']['name']);
    $base = "../public/img";
    $destino = $base.'/'.$nombreArchivo;
    @move_uploaded_file($_FILES['fotografia']['tmp_name'], $destino);
        
    
}



    $datos = [
        'nombre' => $_POST['nombre'] ?? '',
        'tipo' => $_POST['tipo'] ?? null,
        'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
        'foto_url' => $destino ?? null,
        'id_persona' => $_POST['responsable'] ?? null,
    ];

    try {
            $gestorMascotas->insertar($datos);
            $mensaje = 'Mascota insertada correctamente.';
    } catch (Exception $e) {
        $mensaje = 'Error al guardar: '.$e->getMessage();
    }
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Mascota</title>

    <link href="css/bootstrap.min_002.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* NO pisamos .container de Bootstrap, usamos una clase propia */
        .form-wrapper {
            max-width: 800px;
            margin: 20px auto;
        }
    </style>
</head>
<body>

    <div class="container form-wrapper">
        <div class="row justify-content-center">
            <!-- TARJETA: REGISTRAR MASCOTA -->
            <div class="col-md-6 mb-4">
                <div class="card p-4">
                    <h2 class="mb-3">Registrar Mascota</h2>

                    <form action="RegistroMascota.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" name="nombre" id="nombre" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo:</label>
                            <select name="tipo" id="tipo" class="form-select">
                                <option value="">-- Selecciona tipo --</option>
                                <option value="gato">gato</option>
                                <option value="perro">perro</option>
                                <option value="tortuga">tortuga</option>
                                <option value="agaporni">agaporni</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_nac" class="form-label">Fecha de Nacimiento:</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto:</label>
                            <input type="file" name="fotografia" id="fotografia" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="responsable" class="form-label">Responsable:</label>
                            <select name="responsable" id="responsable" class="form-select">
                                <?php foreach($listaPersonas as $pr) { ?>
                                <option value=<?php echo $pr->getId();?>><?php echo $pr->getNombre();?> <?php echo $pr->getApellido();?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <button type="submit" name="guardar" class="btn btn-primary w-100">
                            Registrar Mascota
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
