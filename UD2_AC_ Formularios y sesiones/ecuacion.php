<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./style.css">
    <title>Ecuacion</title>
</head>
<body>
    <header><h1>HAS REALIZADO 5 OPERACIONES MUY BAJAS</h1></header>
    <main>
        <form action="calcEcuacion.php" method="post">
            <label for="inputA">Da el parametro A: </label>
            <input type="number" id="inputA" name="inputA" required>
            <br>
            <label for="inputB">Da el parametro B: </label>
            <input type="number" id="inputB" name="inputB" required>
            <br>
            <label for="inputC">Da el parametro C: </label>
            <input type="number" id="inputC" name="inputC" required>
            <br>
            <label for="resultado">Resultado:</label>
            <label id="resultado" name="resultado"><?php echo isset($_GET['result']) ? $_GET['result'] : ''; ?></label>
            <input type="submit" value="Calcular">
        </form>
    </main>
    <footer>
        <p>Volver a la <a href="calculos.php">calculadora</a></p>
        <p>Volver al <a href="logout.php">inicio y cerrar sesion</a></p>
    </footer>
</body>
</html>