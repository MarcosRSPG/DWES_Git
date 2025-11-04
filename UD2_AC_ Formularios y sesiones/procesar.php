<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['usuario'] === 'user' && $_POST['contrasena'] === 'admin') {
        session_start();
        $_SESSION['usuario'] = $_POST['usuario'];
        $_SESSION['contrasena'] = $_POST['contrasena'];
        header('Location: calculos.php');
    } else {
        header('Location: error.php');
    }
}
