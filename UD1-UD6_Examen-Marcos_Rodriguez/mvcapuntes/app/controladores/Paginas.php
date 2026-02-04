<?php

declare(strict_types=1);

namespace Mrs\Mvcapuntes\Controladores;

use Mrs\Mvcapuntes\Librerias\Controlador;

/**
 * Controlador Paginas - Gestiona páginas generales y login.
 */
class Paginas extends Controlador
{
    public function __construct()
    {
        // Inicialización si es necesaria
    }

    /**
     * Página de login.
     */
    public function login(): void
    {
        // Si ya está logueado, redirigir a inicio
        if (!empty($_SESSION['veterinario_email'])) {
            $this->redirect('/Paginas/inicio');
        }

        // Procesar formulario si es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $clave = trim($_POST['clave'] ?? '');

            if (empty($email) || empty($clave)) {
                $this->vista('paginas/login', [
                    'titulo' => 'Login',
                    'error' => 'Email y contraseña son requeridos',
                ]);

                return;
            }

            $vetModel = $this->modelo('VeterinarioModelo');
            $veterinario = $vetModel->validarCredenciales($email, $clave);

            if ($veterinario) {
                // Login exitoso
                $_SESSION['veterinario_email'] = $veterinario['email'];
                $_SESSION['veterinario_id'] = $veterinario['id'];
                $_SESSION['veterinario_nombre'] = $veterinario['nombre'];

                $this->redirect('/paginas/inicio');
            } else {
                $this->vista('paginas/login', [
                    'titulo' => 'Login',
                    'error' => 'Credenciales inválidas',
                ]);
            }
        } else {
            // Mostrar formulario
            $this->vista('paginas/login', [
                'titulo' => 'Login',
            ]);
        }
    }

    /**
     * Logout - Cerrar sesión.
     */
    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        $this->redirect('/paginas/login');
    }

    /**
     * Página de inicio después del login.
     */
    public function inicio(): void
    {
        $this->requireLogin();

        $this->vista('paginas/inicio', [
            'titulo' => 'Inicio',
        ]);
    }

    /**
     * Página quienes somos.
     */
    public function quienes(): void
    {
        $this->requireLogin();

        $this->vista('paginas/quienes-somos', [
            'titulo' => 'Quiénes Somos',
        ]);
    }

    /**
     * Página por defecto - redirige a login.
     */
    public function index(): void
    {
        $this->redirect('/paginas/login');
    }
}
