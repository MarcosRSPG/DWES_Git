<?php

namespace Mrs\ApiServer\controladores;

use Mrs\ApiServer\librerias\Controlador;
use Mrs\ApiServer\modelos\GestorRestaurantes;

/**
 * ControladorAuth - Controlador de autenticación
 * Maneja login y logout de restaurantes.
 */
class ControladorAuth extends Controlador
{
    /**
     * POST /controladorauth/login
     * Login de restaurante
     * Body JSON: {"correo": "email", "clave": "password"}.
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

        if (!$data || !isset($data['correo']) || !isset($data['clave'])) {
            $this->jsonResponse([
                'error' => 'Campos requeridos: correo, clave',
            ], 400);
        }

        $correo = trim($data['correo']);
        $clave = trim($data['clave']);

        // Validar credenciales
        $restaurante = GestorRestaurantes::validarCredenciales($correo, $clave);

        if (!$restaurante) {
            $this->jsonResponse([
                'error' => 'Credenciales inválidas',
            ], 401);
        }

        // Guardar en sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['restaurante_correo'] = $restaurante['Correo'];
        $_SESSION['restaurante_codres'] = $restaurante['CodRes'];

        // Respuesta exitosa
        $this->jsonResponse([
            'success' => true,
            'message' => 'Login exitoso',
            'restaurante' => [
                'CodRes' => $restaurante['CodRes'],
                'Correo' => $restaurante['Correo'],
                'Nombre' => $restaurante['Nombre'],
                'Telefono' => $restaurante['Telefono'],
                'Direccion' => $restaurante['Direccion'],
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

        $correo = $_SESSION['restaurante_correo'] ?? null;

        if ($correo) {
            $restaurante = GestorRestaurantes::getRestaurantePorCorreo($correo);

            $this->jsonResponse([
                'autenticado' => true,
                'restaurante' => $restaurante,
            ], 200);
        } else {
            $this->jsonResponse([
                'autenticado' => false,
            ], 200);
        }
    }
}
