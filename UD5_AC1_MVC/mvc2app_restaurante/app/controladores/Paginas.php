<?php
declare(strict_types=1);

namespace MRS\Controladores;

use MRS\Librerias\Controlador;

class Paginas extends Controlador
{
    public function __construct()
    {
        // nada
    }

    // Ruta obligatoria: /Paginas/login
    public function login(): void
    {
        // Si ya está logueado, vamos a categorías
        if (!empty($_SESSION['correo'])) {
            $this->redirect('/Categoria/categorias');
        }

        $this->vista('paginas/login', [
            'titulo' => 'Login',
        ]);
    }

    // Página de ejemplo (si quieres mantenerla)
    public function index(): void
    {
        $this->redirect('/Paginas/login');
    }
}
