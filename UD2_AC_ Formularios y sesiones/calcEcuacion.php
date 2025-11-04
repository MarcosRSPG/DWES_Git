<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valorA = (float) $_POST['inputA'];
    $valorB = (float) $_POST['inputB'];
    $valorC = (float) $_POST['inputC'];

    $resultadoSuma = calcularFormula($valorA, $valorB, $valorC, 'suma');
    $resultadoResta = calcularFormula($valorA, $valorB, $valorC, 'resta');
    if ($resultadoResta === $resultadoSuma) {
        header('Location: ecuacion.php?result='.urlencode($resultadoResta));
    } else {
        header('Location: ecuacion.php?result='.urlencode($resultadoSuma.' y '.$resultadoResta));
    }

    exit;
}

function calcularFormula($valorA, $valorB, $valorC, $tipo)
{
    if ($valorA == 0) {
        return 'A no puede ser 0';
    }

    $discriminante = ($valorB ** 2) - (4 * $valorA * $valorC);

    if ($discriminante < 0) {
        return 'Sin soluciones reales';
    }

    $raiz = sqrt($discriminante);

    if ($tipo === 'resta') {
        return (-$valorB - $raiz) / (2 * $valorA);
    }

    if ($tipo === 'suma') {
        return (-$valorB + $raiz) / (2 * $valorA);
    }

    return null;
}
