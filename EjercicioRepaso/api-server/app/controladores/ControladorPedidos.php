<?php

namespace Mrs\ApiServer\controladores;

use Mrs\ApiServer\librerias\Controlador;
use Mrs\ApiServer\modelos\GestorPedidos;

/**
 * ControladorPedidos - API REST para carrito y pedidos.
 */
class ControladorPedidos extends Controlador
{
    /**
     * GET /controladorpedidos/carrito
     * Obtiene el carrito actual del restaurante logueado.
     */
    public function carrito(): void
    {
        $this->requireBasicAuth();
        $correo = $this->requireAuth();

        try {
            $carrito = GestorPedidos::getCarritoPorCorreo($correo);

            $this->jsonResponse([
                'success' => true,
                'carrito' => $carrito,
            ], 200);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al obtener carrito',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /controladorpedidos/agregar
     * Agrega un producto al carrito
     * Body: {"codProd": "uuid", "unidades": 2}.
     */
    public function agregar(): void
    {
        $this->requireBasicAuth();
        $correo = $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }

        $data = $this->readJsonBody();

        if (!$data || empty($data['codProd']) || !isset($data['unidades'])) {
            $this->jsonResponse(['error' => 'Campos requeridos: codProd, unidades'], 400);
        }

        try {
            $codProd = trim($data['codProd']);
            $unidades = (int) $data['unidades'];

            if ($unidades <= 0) {
                $this->jsonResponse(['error' => 'Unidades debe ser mayor a 0'], 400);
            }

            // Obtener precio del producto
            $producto = \Mrs\ApiServer\modelos\GestorProductos::getProducto($codProd);
            if (!$producto) {
                $this->jsonResponse(['error' => 'Producto no encontrado'], 404);
            }

            // Obtener o crear pedido abierto
            $codRes = \Mrs\ApiServer\modelos\GestorRestaurantes::getCodResPorCorreo($correo);
            if (!$codRes) {
                $this->jsonResponse(['error' => 'Restaurante no encontrado'], 404);
            }

            $codPed = GestorPedidos::getOrCreatePedidoAbierto($codRes);

            // Agregar producto con precio
            $resultado = GestorPedidos::agregarProductoAlPedido($codPed, $codProd, $unidades, $producto['Precio']);

            if ($resultado) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Producto agregado al carrito',
                ], 200);
            } else {
                $this->jsonResponse(['error' => 'Error al agregar producto'], 500);
            }
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al agregar producto',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * PUT /controladorpedidos/actualizar
     * Actualiza las cantidades del carrito
     * Body: {"unidades": {"uuid1": 3, "uuid2": 1}}.
     */
    public function actualizar(): void
    {
        $this->requireBasicAuth();
        $correo = $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }

        $data = $this->readJsonBody();

        if (!$data || !isset($data['unidades']) || !is_array($data['unidades'])) {
            $this->jsonResponse(['error' => 'Campo unidades requerido (objeto)'], 400);
        }

        try {
            $resultado = GestorPedidos::actualizarCarrito($correo, $data['unidades']);

            if ($resultado) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Carrito actualizado',
                ], 200);
            } else {
                $this->jsonResponse(['error' => 'Error al actualizar carrito'], 500);
            }
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al actualizar carrito',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE /controladorpedidos/eliminar/{codProd}
     * Elimina un producto del carrito.
     */
    public function eliminar(string $codProd = ''): void
    {
        $this->requireBasicAuth();
        $correo = $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }

        if (empty($codProd)) {
            $this->jsonResponse(['error' => 'Código de producto requerido'], 400);
        }

        try {
            $resultado = GestorPedidos::eliminarProductoDelCarrito($correo, $codProd);

            if ($resultado) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Producto eliminado del carrito',
                ], 200);
            } else {
                $this->jsonResponse(['error' => 'Producto no encontrado en el carrito'], 404);
            }
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al eliminar producto',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /controladorpedidos/enviar
     * Envía el pedido (marca como enviado).
     */
    public function enviar(): void
    {
        $this->requireBasicAuth();
        $correo = $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }

        try {
            $resultado = GestorPedidos::enviarPedido($correo);

            if ($resultado) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Pedido enviado correctamente',
                ], 200);
            } else {
                $this->jsonResponse(['error' => 'No hay pedido para enviar'], 404);
            }
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al enviar pedido',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /controladorpedidos (método por defecto).
     */
    public function index(): void
    {
        $this->carrito();
    }
}
