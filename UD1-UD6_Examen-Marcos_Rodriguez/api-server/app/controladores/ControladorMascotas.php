<?php

namespace Mrs\ApiServer\controladores;

use Mrs\ApiServer\librerias\Controlador;
use Mrs\ApiServer\modelos\GestorMascotas;
use Ramsey\Uuid\Uuid;

/**
 * ControladorMascotas - API REST para categorías.
 */
class ControladorMascotas extends Controlador
{
    /**
     * GET /controladormascotas/mascotas
     * Lista todas las categorías.
     */
    public function mascotas(): void
    {
        $this->requireBasicAuth();
        $this->requireAuth();

        try {
            $mascotas = GestorMascotas::getMascotas();

            $this->jsonResponse([
                'success' => true,
                'mascotas' => $mascotas,
            ], 200);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al obtener categorías',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /controladormascotas/mascota/{id}
     * Obtiene una categoría por UUID.
     */
    public function mascota(string $id = ''): void
    {
        $this->requireBasicAuth();
        $this->requireAuth();

        if (empty($id)) {
            $this->jsonResponse(['error' => 'ID requerido'], 400);
        }

        try {
            $mascota = GestorMascotas::getMascota($id);

            if (!$mascota) {
                $this->jsonResponse(['error' => 'Mascota no encontrada'], 404);
            }

            $this->jsonResponse([
                'success' => true,
                'mascota' => $mascota,
            ], 200);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al obtener categoría',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /controladormascotas/crear
     * Crea una nueva categoría
     * Body: {"nombre": "...", "descripcion": "..."}.
     */
    public function crear(): void
    {
        $this->requireBasicAuth();
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }

        $data = $this->readJsonBody();

        if (!$data || empty($data['nombre'])) {
            $this->jsonResponse(['error' => 'Campo nombre requerido'], 400);
        }

        try {
            // Generar UUID
            $id = Uuid::uuid4()->toString();
            $nombre = trim($data['nombre']);
            $tipo = trim($data['tipo'] ?? '');
            $fechaNac = trim($data['fechaNac'] ?? '');
            $fotoUrl = trim($data['fotoUrl'] ?? '');
            $idPers = trim($data['idPers'] ?? '');
            $resultado = GestorMascotas::crearMascota($id, $nombre, $tipo, $fechaNac, $fotoUrl, $idPers);

            if ($resultado) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Mascota creada',
                    'mascota' => [
                        'id' => $id,
                        'nombre' => $nombre,
                        'tipo' => $tipo,
                        'fecha_nacimiento' => $fechaNac,
                        'foto_url' => $fotoUrl,
                        'id_persona' => $idPers,
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
     * PUT /controladormascotas/actualizar/{id}
     * Actualiza una categoría existente
     * Body: {"nombre": "...", "descripcion": "..."}.
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

        if (!$data || empty($data['nombre'])) {
            $this->jsonResponse(['error' => 'Campo nombre requerido'], 400);
        }

        try {
            // Verificar que existe
            $existe = GestorMascotas::getMascota($id);
            if (!$existe) {
                $this->jsonResponse(['error' => 'Mascota no encontrada'], 404);
            }

            $nombre = trim($data['nombre']);
            $tipo = trim(string: $data['tipo'] ?? '');
            $fechaNac = trim(string: $data['fechaNac'] ?? '');
            $fotoUrl = trim(string: $data['fotoUrl'] ?? '');
            $idPers = trim(string: $data['idPers'] ?? '');

            $resultado = GestorMascotas::actualizarMascota($id, $nombre, $tipo, $fechaNac, $fotoUrl, $idPers);

            if ($resultado) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Mascota actualizada',
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
     * DELETE /controladormascotas/eliminar/{id}
     * Elimina una categoría.
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
            $resultado = GestorMascotas::eliminarMascota($id);

            if ($resultado) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Mascota eliminada',
                ], 200);
            } else {
                $this->jsonResponse(['error' => 'Mascota no encontrada'], 404);
            }
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Error al eliminar categoría',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /controladormascotas (método por defecto).
     */
    public function index(): void
    {
        $this->mascotas();
    }
}
