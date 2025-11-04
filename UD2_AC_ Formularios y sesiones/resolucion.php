<?php

session_start();
if (!isset($_SESSION['acumulado'])) {
    $_SESSION['acumulado'] = 0;
}
if (!isset($_SESSION['contador'])) {
    $_SESSION['contador'] = 0;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $numero1 = $_POST['numero1'];
        $numero2 = $_POST['numero2'];
        $operacion = $_POST['operacion'];
        $_SESSION['resultado'] = $operacion($numero1, $numero2);
        $_SESSION['acumulado'] += (int) $_SESSION['resultado'];

        if ((int) $_SESSION['acumulado'] <= 1000) {
            (int) ++$_SESSION['contador'];
        }

        if ($_SESSION['contador'] >= 5) {
            $_SESSION['acumulado'] = 0;
            $_SESSION['contador'] = 0;
            header('Location: ecuacion.php');
        } else {
            header('Location: calculos.php');
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}

function sumar($a, $b)
{
    return $a + $b;
}
function restar($a, $b)
{
    return $a - $b;
}
function multiplicar($a, $b)
{
    return $a * $b;
}
function dividir($a, $b)
{
    if ($b != 0) {
        return $a / $b;
    } else {
        return 'Error: División por cero';
    }
}
