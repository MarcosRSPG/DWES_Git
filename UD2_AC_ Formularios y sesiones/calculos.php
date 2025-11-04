<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
    <title>Calculos</title>
</head>
<body>
    <header><h1>INTRODUCE EL CALCULO</h1></header>
    <main>
        <form action="resolucion.php" method="post">
            <label for="numero1">Número 1:</label>
            <input type="number" id="numero1" name="numero1" required>
            <select id="operacion" name="operacion">
                <option value="sumar">Sumar</option>
                <option value="restar">Restar</option>
                <option value="multiplicar">Multiplicar</option>
                <option value="dividir">Dividir</option>
            </select>
            <label for="numero2">Número 2:</label>
            <input type="number" id="numero2" name="numero2" required>
            <br>
            <label for="resultado">Resultado:</label>
            <label id="resultado" name="resultado"><?php
                echo $_SESSION['resultado'] ?? $_SESSION['resultado'];
$_SESSION['resultado'] = null;
?></label>
            <input type="submit" value="Calcular">
        </form>
    </main>
</body>
</html>