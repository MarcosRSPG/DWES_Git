<?php
	require 'PHP_UD2_AE_Marcos_Rodriguez_IntroduccionPHP_B.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ejercicio B</title>
	<link rel="stylesheet" href="CSS_UD2_AE_Marcos_Rodriguez_IntroduccionPHP_B.css">
</head>
<body>
	<h1>Ejercicio B</h1>
	<li>
<p>Usando PHP, crea un array que represente un catálogo de libros. Cada libro debe ser un array asociativo con las siguientes claves:</p>
</li>
	<table class="tabla">
		<tr class="header">
			<td>Título</td>
			<td>Número de páginas</td>
			<td>Precio</td>
			<td>Fecha de publicación</td>
		</tr>
		<?php
		foreach ($catalogo as $libro) {
			if (empty($libro)) {
				continue;
			}
			echo "<tr>";
			echo "<td>".$libro['titulo']."</td>";
			echo "<td>".$libro['n_paginas']."</td>";
			echo "<td>".$libro['precio']."</td>";
			echo "<td>".$libro['fecha_publicacion']."</td>";
			echo "</tr>";
		}
		 ?>
	</table>
</body>
</html>