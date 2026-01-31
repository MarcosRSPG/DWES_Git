<?php

namespace Mrs\ApiServer\controladores;

use Mrs\ApiServer\librerias\Controlador;
use Mrs\ApiServer\modelos\GestorProductos;
use Ramsey\Uuid\Uuid;

/**
 * ControladorProductos - API REST para productos.
 */
class ControladorProductos extends Controlador
{
    /**
     * GET /controladorproductos/productos
     * Lista todos los productos.
     */
    public function productos(): void
    {
        $this->requireBasicAuth();
        $this->requireAuth();

        try {
            $productos = GestorProductos::getProductos();

            $this->jsonResponse([
                'success' => true,
                'productos' => $productos,
            ], 200);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al obtener productos',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /controladorproductos/productoscategoria/{codCat}
     * Lista productos de una categoría.
     */
    public function productoscategoria(string $codCat = ''): void
    {
        $this->requireBasicAuth();
        $this->requireAuth();

        if (empty($codCat)) {
            $this->jsonResponse(['error' => 'Código de categoría requerido'], 400);
        }

        try {
            $productos = GestorProductos::getProductosPorCategoria($codCat);

            $this->jsonResponse([
                'success' => true,
                'productos' => $productos,
            ], 200);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al obtener productos',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /controladorproductos/producto/{id}
     * Obtiene un producto por UUID.
     */
    public function producto(string $id = ''): void
    {
        $this->requireBasicAuth();
        $this->requireAuth();

        if (empty($id)) {
            $this->jsonResponse(['error' => 'ID requerido'], 400);
        }

        try {
            $producto = GestorProductos::getProducto($id);

            if (!$producto) {
                $this->jsonResponse(['error' => 'Producto no encontrado'], 404);
            }

            $this->jsonResponse([
                'success' => true,
                'producto' => $producto,
            ], 200);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al obtener producto',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /controladorproductos/crear
     * Crea un nuevo producto
     * Body: {"nombre": "...", "descripcion": "...", "precio": 10.50, "stock": 10, "categoria": "uuid"}.
     */
    public function crear(): void
    {
        $this->requireBasicAuth();
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }

        $data = $this->readJsonBody();

        if (!$data || empty($data['nombre']) || empty($data['categoria'])) {
            $this->jsonResponse(['error' => 'Campos requeridos: nombre, categoria'], 400);
        }

        try {
            $codProd = Uuid::uuid4()->toString();
            $nombre = trim($data['nombre']);
            $descripcion = trim($data['descripcion'] ?? '');
            $precio = (float) ($data['precio'] ?? 0);
            $stock = (int) ($data['stock'] ?? 0);
            $categoria = trim($data['categoria']);

            $resultado = GestorProductos::crearProducto(
                $codProd,
                $nombre,
                $descripcion,
                $precio,
                $stock,
                $categoria
            );

            if ($resultado) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Producto creado',
                    'producto' => [
                        'CodProd' => $codProd,
                        'Nombre' => $nombre,
                    ],
                ], 201);
            } else {
                $this->jsonResponse(['error' => 'Error al crear producto'], 500);
            }
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al crear producto',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * PUT /controladorproductos/actualizar/{id}
     * Actualiza un producto existente.
     */
    public function actualizar(string $id = ''): void
    {
        $this->requireBasicAuth();
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }

        if (empty($id)) {
            $this->jsonResponse(['error' => 'ID requerido'], 400);
        }

        $data = $this->readJsonBody();

        if (!$data || empty($data['nombre']) || empty($data['categoria'])) {
            $this->jsonResponse(['error' => 'Campos requeridos: nombre, categoria'], 400);
        }

        try {
            $existe = GestorProductos::getProducto($id);
            if (!$existe) {
                $this->jsonResponse(['error' => 'Producto no encontrado'], 404);
            }

            $nombre = trim($data['nombre']);
            $descripcion = trim($data['descripcion'] ?? '');
            $precio = (float) ($data['precio'] ?? 0);
            $stock = (int) ($data['stock'] ?? 0);
            $categoria = trim($data['categoria']);

            $resultado = GestorProductos::actualizarProducto(
                $id,
                $nombre,
                $descripcion,
                $precio,
                $stock,
                $categoria
            );

            if ($resultado) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Producto actualizado',
                ], 200);
            } else {
                $this->jsonResponse(['error' => 'Error al actualizar producto'], 500);
            }
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al actualizar producto',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE /controladorproductos/eliminar/{id}
     * Elimina un producto.
     */
    public function eliminar(string $id = ''): void
    {
        $this->requireBasicAuth();
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }

        if (empty($id)) {
            $this->jsonResponse(['error' => 'ID requerido'], 400);
        }

        try {
            $resultado = GestorProductos::eliminarProducto($id);

            if ($resultado) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Producto eliminado',
                ], 200);
            } else {
                $this->jsonResponse(['error' => 'Producto no encontrado'], 404);
            }
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al eliminar producto',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /controladorproductos (método por defecto).
     */
    public function index(): void
    {
        $this->productos();
    }
}
