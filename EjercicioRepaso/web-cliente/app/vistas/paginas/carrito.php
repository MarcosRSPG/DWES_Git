<?php
use Mrs\WebCliente\ClienteAPI;

// Verificar sesión
if (!isset($_SESSION['restaurante'])) {
    header('Location: '.WEB_URL.'/auth/login');
    exit;
}

$cliente = new ClienteAPI();
$mensaje = '';

// Procesar acciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {
        case 'actualizar':
            // Actualizar unidades de un producto
            $codProd = $_POST['codprod'] ?? '';
            $unidades = intval($_POST['unidades'] ?? 0);

            if ($codProd && $unidades > 0) {
                $respuesta = $cliente->put('controladorpedidos/actualizar', [
                    'unidades' => [$codProd => $unidades],
                ]);

                $mensaje = $respuesta['success']
                    ? '<div class="mensaje-success">Cantidad actualizada</div>'
                    : '<div class="mensaje-error">Error al actualizar</div>';
            }
            break;

        case 'eliminar':
            // Eliminar un producto del carrito
            $codProd = $_POST['codprod'] ?? '';

            if ($codProd) {
                $respuesta = $cliente->delete('controladorpedidos/eliminar/'.$codProd);

                $mensaje = $respuesta['success']
                    ? '<div class="mensaje-success">Producto eliminado</div>'
                    : '<div class="mensaje-error">Error al eliminar</div>';
            }
            break;

        case 'enviar':
            // Enviar el pedido
            $respuesta = $cliente->post('controladorpedidos/enviar', []);

            if ($respuesta['success']) {
                $mensaje = '<div class="mensaje-success" style="font-size: 1.2em;">✓ Pedido enviado correctamente</div>';
            } else {
                $mensaje = '<div class="mensaje-error">Error al enviar el pedido</div>';
            }
            break;
    }
}

// Obtener carrito actual
$respuestaCarrito = $cliente->get('controladorpedidos/carrito');
$pedido = null;
$lineas = [];
$total = 0;

if ($respuestaCarrito['success'] && isset($respuestaCarrito['data']['carrito'])) {
    $carrito = $respuestaCarrito['data']['carrito'];
    $pedido = $carrito['pedido'] ?? null;
    $lineas = $carrito['lineas'] ?? [];
    $total = $pedido['Total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito</title>
    <link rel="stylesheet" href="<?php echo WEB_URL; ?>/css/estilos.css">
</head>
<body>
    <div class="nav">
        <a href="<?php echo WEB_URL; ?>/paginas/index">← Inicio</a>
        <a href="<?php echo WEB_URL; ?>/productos/index">Ver Productos</a>
        <a href="<?php echo WEB_URL; ?>/auth/logout">Cerrar Sesión</a>
    </div>

    <h1>🛒 Mi Carrito</h1>
    
    <?php echo $mensaje; ?>
    
    <?php if (empty($lineas)) { ?>
        <div class="carrito-vacio">
            <h2>Tu carrito está vacío</h2>
            <p>Agrega productos desde la <a href="<?php echo WEB_URL; ?>/productos/index">tienda</a></p>
        </div>
    <?php } else { ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio Unit.</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lineas as $linea) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($linea['Nombre'] ?? $linea['CodProd']); ?></td>
                        <td class="precio"><?php echo number_format($linea['PrecioUnitario'], 2); ?> €</td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="accion" value="actualizar">
                                <input type="hidden" name="codprod" value="<?php echo htmlspecialchars($linea['CodProd']); ?>">
                                <input type="number" name="unidades" value="<?php echo $linea['Unidades']; ?>" min="1">
                                <button type="submit" class="btn btn-actualizar">Actualizar</button>
                            </form>
                        </td>
                        <td class="precio"><?php echo number_format($linea['Subtotal'], 2); ?> €</td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="codprod" value="<?php echo htmlspecialchars($linea['CodProd']); ?>">
                                <button type="submit" class="btn btn-eliminar" onclick="return confirm('¿Eliminar este producto?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <div class="total">
            <strong>TOTAL: <?php echo number_format($total, 2); ?> €</strong>
        </div>
        
        <form method="POST">
            <input type="hidden" name="accion" value="enviar">
            <button type="submit" class="btn btn-enviar" onclick="return confirm('¿Confirmar y enviar el pedido?')">
                ✓ Enviar Pedido
            </button>
        </form>
    <?php } ?>
</body>
</html>
