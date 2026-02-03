<?php

namespace Mrs\ApiServer\controladores;

use Mrs\ApiServer\librerias\Controlador;
use Mrs\ApiServer\modelos\GestorVeterinarios;

/**
 * ControladorAuth - Controlador de autenticación
 * Maneja login y logout de veterinarios.
 */
class ControladorAuth extends Controlador
{
    /**
     * POST /controladorauth/login
     * Login de veterinario
     * Body JSON: {"email": "email", "clave": "password"}.
     */
    public function login(): void
    {
        // Verificar Basic Auth (nivel API)
        $this->requireBasicAuth();

        // Solo aceptar POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }

        // Leer datos JSON del body
        $data = $this->readJsonBody();

        if (!$data || !isset($data['email']) || !isset($data['clave'])) {
            $this->jsonResponse([
                'error' => 'Campos requeridos: email, clave',
            ], 400);
        }

        $email = trim($data['email']);
        $clave = trim($data['clave']);

        // Validar credenciales
        $veterinario = GestorVeterinarios::validarCredenciales($email, $clave);

        if (!$veterinario) {
            $this->jsonResponse([
                'error' => 'Credenciales inválidas',
            ], 401);
        }

        // Guardar en sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['veterinario_email'] = $veterinario['email'];
        $_SESSION['veterinario_id'] = $veterinario['id'];

        // Respuesta exitosa
        $this->jsonResponse([
            'success' => true,
            'message' => 'Login exitoso',
            'veterinario' => [
                'id' => $veterinario['id'],
                'email' => $veterinario['email'],
                'nombre' => $veterinario['nombre'],
            ],
        ], 200);
    }

    /**
     * POST /controladorauth/logout
     * Cerrar sesión.
     */
    public function logout(): void
    {
        // Verificar Basic Auth
        $this->requireBasicAuth();

        // Solo aceptar POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Method not allowed'], 405);
        }

        // Destruir sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

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

        $this->jsonResponse([
            'success' => true,
            'message' => 'Sesión cerrada',
        ], 200);
    }

    /**
     * GET /controladorauth/verificar
     * Verifica si hay sesión activa.
     */
    public function verificar(): void
    {
        // Verificar Basic Auth
        $this->requireBasicAuth();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_SESSION['veterinario_email'] ?? null;

        if ($email) {
            $veterinario = GestorVeterinarios::getVeterinarioPorCorreo($email);

            $this->jsonResponse([
                'autenticado' => true,
                'veterinario' => $veterinario,
            ], 200);
        } else {
            $this->jsonResponse([
                'autenticado' => false,
            ], 200);
        }
    }
}
