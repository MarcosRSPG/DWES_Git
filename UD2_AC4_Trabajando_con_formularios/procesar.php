<?php

require_once __DIR__.'/php/jugarVideojuego.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$juego = new jugarVideojuegos();
$juego->setName(isset($_POST['nombre']) ? (string) $_POST['nombre'] : '');
$juego->setPlataforma(isset($_POST['plataforma']) ? (string) $_POST['plataforma'] : '');
$juego->setGenero(isset($_POST['genero']) ? (string) $_POST['genero'] : '');
$juego->setFechaLanzamiento(isset($_POST['fechaLanzamiento']) ? date_create($_POST['fechaLanzamiento']) : null);
$juego->setPrecio(isset($_POST['precio']) ? (float) $_POST['precio'] : 0);

if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] === UPLOAD_ERR_OK) {
    $tipo = $_FILES['fotografia']['type'];
    $nombreArchivo = basename($_FILES['fotografia']['name']);
    $ext = explode('.', $nombreArchivo)[1];

    if ($tipo === 'application/pdf' && $ext === 'pdf') {
        $base = dirname(__FILE__).'/pdfs';

        $destino = $base.'/'.$nombreArchivo;
        $juego->nomAr = $nombreArchivo;
        if (!file_exists($destino) && $_FILES['fotografia']['size'] <= (2 * 1024 * 1024)) {
            @move_uploaded_file($_FILES['fotografia']['tmp_name'], $destino);
        }
    }
}

$errores = [];

if (($juego->getName() ?? '') === '') {
    $errores[] = 'Falta el título';
}
if (($juego->getPlataforma() ?? '') === '') {
    $errores[] = 'Falta la plataforma';
}
if (($juego->getGenero() ?? '') === '') {
    $errores[] = 'Falta el género';
}

if (!(($juego->getFechaLanzamiento()) instanceof DateTimeInterface)) {
    $errores[] = 'Fecha inválida o ausente';
}

$precio = $juego->getPrecio() ?? null;
if (!is_float($precio) || $precio <= 0) {
    $errores[] = 'Precio inválido';
}

error_log(serialize($juego), LOG_INFO);

$juego = unserialize($juego);

$lineas = [];
if (!empty($errores)) {
    $lineas[] = '⚠️ Faltan/son inválidos algunos campos:';
    foreach ($errores as $e) {
        $lineas[] = '- '.$e;
    }
} else {
    $lineas[] = '✅ Se recibieron todos los parámetros requeridos.';
    $lineas[] = 'Nuevo videojuego añadido:';
    $lineas[] = 'Título: '.$juego->getName();
    $lineas[] = 'Plataforma: '.$juego->getPlataforma();
    $lineas[] = 'Género: '.$juego->getGenero();

    $fechaVal = $juego->getFechaLanzamiento();
    $fechaVal = $fechaVal instanceof DateTimeInterface ? $fechaVal->format('Y-m-d') : '';
    $lineas[] = 'Lanzamiento: '.$fechaVal;

    $lineas[] = 'Precio: '.$precio;
    $lineas[] = 'PDF: '.($juego->nomAr ?? '');
}
ini_set('session.save_path', 'E:\\Ampps\\tmp');

session_start();
$_SESSION['flash_lineas'] = $lineas;
header('Location: index.php');
exit;
