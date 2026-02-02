<?php

namespace Mrs\ApiServer\controladores;

use Mrs\ApiServer\librerias\Controlador;
use Mrs\ApiServer\modelos\GestorCategorias;
use Ramsey\Uuid\Uuid;

/**
 * ControladorCategorias - API REST para categorías.
 */
class ControladorCategorias extends Controlador
{
    /**
     * GET /controladorcategorias/categorias
     * Lista todas las categorías.
     */
    public function categorias(): void
    {
        $this->requireBasicAuth();

        try {
            $categorias = GestorCategorias::getCategorias();

            $this->jsonResponse([
                'success' => true,
                'categorias' => $categorias,
            ], 200);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al obtener categorías',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /controladorcategorias/categoria/{id}
     * Obtiene una categoría por UUID.
     */
    public function categoria(string $id = ''): void
    {
        $this->requireBasicAuth();

        if (empty($id)) {
            $this->jsonResponse(['error' => 'ID requerido'], 400);
        }

        try {
            $categoria = GestorCategorias::getCategoria($id);

            if (!$categoria) {
                $this->jsonResponse(['error' => 'Categoría no encontrada'], 404);
            }

            $this->jsonResponse([
                'success' => true,
                'categoria' => $categoria,
            ], 200);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al obtener categoría',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /controladorcategorias/crear
     * Crea una nueva categoría
     * Body: {"nombre": "...", "descripcion": "..."}.
     */
    public function crear(): void
    {
        $this->requireBasicAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }

        $data = $this->readJsonBody();

        if (!$data || empty($data['nombre'])) {
            $this->jsonResponse(['error' => 'Campo nombre requerido'], 400);
        }

        try {
            // Generar UUID
            $codCat = Uuid::uuid4()->toString();
            $nombre = trim($data['nombre']);
            $descripcion = trim($data['descripcion'] ?? '');

            $resultado = GestorCategorias::crearCategoria($codCat, $nombre, $descripcion);

            if ($resultado) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Categoría creada',
                    'categoria' => [
                        'CodCat' => $codCat,
                        'Nombre' => $nombre,
                        'Descripcion' => $descripcion,
                    ],
                ], 201);
            } else {
                $this->jsonResponse(['error' => 'Error al crear categoría'], 500);
            }
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al crear categoría',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * PUT /controladorcategorias/actualizar/{id}
     * Actualiza una categoría existente
     * Body: {"nombre": "...", "descripcion": "..."}.
     */
    public function actualizar(string $id = ''): void
    {
        $this->requireBasicAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }

        if (empty($id)) {
            $this->jsonResponse(['error' => 'ID requerido'], 400);
        }

        $data = $this->readJsonBody();

        if (!$data || empty($data['nombre'])) {
            $this->jsonResponse(['error' => 'Campo nombre requerido'], 400);
        }

        try {
            // Verificar que existe
            $existe = GestorCategorias::getCategoria($id);
            if (!$existe) {
                $this->jsonResponse(['error' => 'Categoría no encontrada'], 404);
            }

            $nombre = trim($data['nombre']);
            $descripcion = trim($data['descripcion'] ?? '');

            $resultado = GestorCategorias::actualizarCategoria($id, $nombre, $descripcion);

            if ($resultado) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Categoría actualizada',
                ], 200);
            } else {
                $this->jsonResponse(['error' => 'Error al actualizar categoría'], 500);
            }
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al actualizar categoría',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE /controladorcategorias/eliminar/{id}
     * Elimina una categoría.
     */
    public function eliminar(string $id = ''): void
    {
        $this->requireBasicAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }

        if (empty($id)) {
            $this->jsonResponse(['error' => 'ID requerido'], 400);
        }

        try {
            $resultado = GestorCategorias::eliminarCategoria($id);

            if ($resultado) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Categoría eliminada',
                ], 200);
            } else {
                $this->jsonResponse(['error' => 'Categoría no encontrada'], 404);
            }
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al eliminar categoría',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /controladorcategorias (método por defecto).
     */
    public function index(): void
    {
        $this->categorias();
    }
}
