<?php
use Mrs\WebCliente\ClienteAPI;

// Verificar sesión
if (!isset($_SESSION['restaurante'])) {
    header('Location: '.WEB_URL.'/auth/login');
    exit;
}

$cliente = new ClienteAPI();
$mensaje = '';

// Obtener todas las categorías para el selector
$respuestaCategorias = $cliente->get('controladorcategorias/categorias');
$categorias = $respuestaCategorias['success'] ? $respuestaCategorias['data']['categorias'] : [];

// Obtener categoría seleccionada
$categoriaSeleccionada = $_GET['categoria'] ?? 'todas';

// Obtener productos según la categoría seleccionada
if ($categoriaSeleccionada === 'todas') {
    $respuestaProductos = $cliente->get('controladorproductos/productos');
} else {
    $respuestaProductos = $cliente->get('controladorproductos/productoscategoria/'.$categoriaSeleccionada);
}

$productos = $respuestaProductos['success'] ? $respuestaProductos['data']['productos'] : [];

// Agregar al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    $codProd = trim($_POST['codprod'] ?? '');
    $unidades = intval($_POST['unidades'] ?? 1);

    if ($codProd !== '' && $unidades > 0) {
        $respuesta = $cliente->post('controladorpedidos/agregar', [
            'codProd' => $codProd,
            'unidades' => $unidades,
        ]);

        if ($respuesta['success']) {
            $mensaje = '<div class="mensaje-success">Producto agregado al carrito</div>';
        } else {
            $mensaje = '<div class="mensaje-error">Error: '.($respuesta['data']['error'] ?? 'No se pudo agregar').'</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <link rel="stylesheet" href="<?php echo WEB_URL; ?>/css/estilos.css">
</head>
<body>
    <div class="nav">
        <a href="<?php echo WEB_URL; ?>/paginas/index">← Inicio</a>
        <a href="<?php echo WEB_URL; ?>/carrito/index">🛒 Mi Carrito</a>
        <a href="<?php echo WEB_URL; ?>/auth/logout">Cerrar Sesión</a>
    </div>

    <h1>Productos</h1>
    
    <?php echo $mensaje; ?>
    
    <div class="filtro">
        <form method="GET">
            <label for="categoria"><strong>Filtrar por categoría:</strong></label>
            <select name="categoria" id="categoria" onchange="this.form.submit()">
                <option value="todas" <?php echo $categoriaSeleccionada === 'todas' ? 'selected' : ''; ?>>Todas las categorías</option>
                <?php foreach ($categorias as $cat) { ?>
                    <option value="<?php echo htmlspecialchars($cat['CodCat']); ?>" 
                            <?php echo $categoriaSeleccionada === $cat['CodCat'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['Nombre']); ?>
                    </option>
                <?php } ?>
            </select>
        </form>
    </div>

    <?php if (empty($productos)) { ?>
        <p>No hay productos disponibles en esta categoría.</p>
    <?php } else { ?>
        <div class="productos">
            <?php foreach ($productos as $prod) { ?>
                <div class="producto">
                    <h3><?php echo htmlspecialchars($prod['Nombre']); ?></h3>
                    <p><?php echo htmlspecialchars($prod['Descripcion']); ?></p>
                    <p class="precio"><?php echo number_format($prod['Precio'], 2); ?> €</p>
                    <p class="stock">Stock: <?php echo $prod['Stock']; ?> unidades</p>
                    <span class="categoria"><?php echo htmlspecialchars($prod['CategoriaNombre'] ?? 'Sin categoría'); ?></span>
                    
                    <?php if ($prod['Stock'] > 0) { ?>
                        <form method="POST">
                            <input type="hidden" name="accion" value="agregar">
                            <input type="hidden" name="codprod" value="<?php echo htmlspecialchars($prod['CodProd']); ?>">
                            <label>Cantidad: </label>
                            <input type="number" name="unidades" value="1" min="1" max="<?php echo $prod['Stock']; ?>">
                            <button type="submit">Agregar al carrito</button>
                        </form>
                    <?php } else { ?>
                        <p style="color: red;">Sin stock</p>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</body>
</html>
