<?php

declare(strict_types=1);

namespace Mrs\Mvcapuntes\Controladores;

use Mrs\Mvcapuntes\Librerias\Controlador;

/**
 * Controlador Mascota - Gestiona CRUD de mascotas.
 */
class Mascota extends Controlador
{
    public function __construct()
    {
        // Inicialización si es necesaria
    }

    /**
     * Lista todas las mascotas.
     */
    public function mascotas(): void
    {
        $this->requireLogin();

        $mascotaModel = $this->modelo('MascotaModelo');
        $mascotas = $mascotaModel->obtenerTodas();

        $this->vista('paginas/mascotas', [
            'titulo' => 'Mascotas',
            'mascotas' => $mascotas,
            'veterinario' => $_SESSION['veterinario_nombre'] ?? '',
        ]);
    }

    /**
     * Muestra formulario para crear una mascota.
     */
    public function crear(): void
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $tipo = trim($_POST['tipo'] ?? '');
            $fechaNac = trim($_POST['fecha_nacimiento'] ?? '') ?: null;
            $fotoUrl = trim($_POST['foto_url'] ?? '') ?: null;
            $idPersona = trim($_POST['id_persona'] ?? '');

            if (empty($nombre) || empty($tipo) || empty($idPersona)) {
                $this->vista('paginas/registro', [
                    'titulo' => 'Crear Mascota',
                    'error' => 'Nombre, tipo y dueño son requeridos',
                ]);

                return;
            }

            $mascotaModel = $this->modelo('MascotaModelo');
            $id = uniqid('masc_', true);

            if ($mascotaModel->crear($id, $nombre, $tipo, $fechaNac, $fotoUrl, $idPersona)) {
                $this->redirect('/Mascota/mascotas');
            } else {
                $this->vista('paginas/registro', [
                    'titulo' => 'Crear Mascota',
                    'error' => 'Error al crear la mascota',
                ]);
            }
        } else {
            $this->vista('paginas/registro', [
                'titulo' => 'Crear Mascota',
            ]);
        }
    }

    /**
     * Muestra formulario para editar una mascota.
     */
    public function editar(string $id = ''): void
    {
        $this->requireLogin();

        if (empty($id)) {
            $this->redirect('/Mascota/mascotas');
        }

        $mascotaModel = $this->modelo('MascotaModelo');
        $mascota = $mascotaModel->obtenerPorId($id);

        if (!$mascota) {
            $this->redirect('/Mascota/mascotas');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $tipo = trim($_POST['tipo'] ?? '');
            $fechaNac = trim($_POST['fecha_nacimiento'] ?? '') ?: null;
            $fotoUrl = trim($_POST['foto_url'] ?? '') ?: null;
            $idPersona = trim($_POST['id_persona'] ?? '');

            if (empty($nombre) || empty($tipo) || empty($idPersona)) {
                $this->vista('paginas/editar', [
                    'titulo' => 'Editar Mascota',
                    'mascota' => $mascota,
                    'error' => 'Nombre, tipo y dueño son requeridos',
                ]);

                return;
            }

            if ($mascotaModel->actualizar($id, $nombre, $tipo, $fechaNac, $fotoUrl, $idPersona)) {
                $this->redirect('/Mascota/mascotas');
            } else {
                $this->vista('paginas/editar', [
                    'titulo' => 'Editar Mascota',
                    'mascota' => $mascota,
                    'error' => 'Error al actualizar la mascota',
                ]);
            }
        } else {
            $this->vista('paginas/editar', [
                'titulo' => 'Editar Mascota',
                'mascota' => $mascota,
            ]);
        }
    }

    /**
     * Elimina una mascota.
     */
    public function eliminar(string $id = ''): void
    {
        $this->requireLogin();

        if (empty($id)) {
            $this->redirect('/Mascota/mascotas');
        }

        $mascotaModel = $this->modelo('MascotaModelo');
        $mascotaModel->eliminar($id);

        $this->redirect('/Mascota/mascotas');
    }

    /**
     * Ver detalle de una mascota.
     */
    public function ver(string $id = ''): void
    {
        $this->requireLogin();

        if (empty($id)) {
            $this->redirect('/Mascota/mascotas');
        }

        $mascotaModel = $this->modelo('MascotaModelo');
        $mascota = $mascotaModel->obtenerPorId($id);

        if (!$mascota) {
            $this->redirect('/Mascota/mascotas');
        }

        $this->vista('paginas/detalles', [
            'titulo' => 'Detalle de Mascota',
            'mascota' => $mascota,
        ]);
    }
}
