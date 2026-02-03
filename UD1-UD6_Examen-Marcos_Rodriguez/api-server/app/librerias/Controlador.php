<?php

namespace Mrs\ApiServer\librerias;

/**
 * Clase Controlador - Clase base para todos los controladores API
 * Proporciona métodos comunes: JSON response, auth, lectura de body, etc.
 */
class Controlador
{
    /**
     * Instancia un modelo.
     *
     * @param string $modelo Nombre del modelo (sin namespace)
     *
     * @return object Instancia del modelo
     */
    protected function modelo($modelo)
    {
        $modeloClase = 'Mrs\\ApiServer\\modelos\\'.ucfirst($modelo);

        if (class_exists($modeloClase)) {
            return new $modeloClase();
        }

        throw new \Exception("Modelo $modelo no encontrado");
    }

    /**
     * Devuelve respuesta JSON con headers apropiados.
     *
     * @param mixed $data   Datos a devolver
     * @param int   $status Código HTTP (200, 404, 500, etc.)
     */
    protected function jsonResponse($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Lee el body de la petición como JSON.
     *
     * @return array|null Array con datos o null si está vacío/inválido
     */
    protected function readJsonBody()
    {
        $raw = file_get_contents('php://input');
        if ($raw === false || trim($raw) === '') {
            return null;
        }
        $data = json_decode($raw, true);

        return is_array($data) ? $data : null;
    }

    /**
     * Requiere autenticación Basic Auth
     * Verifica usuario y contraseña en headers HTTP.
     */
    protected function requireBasicAuth()
    {
        // Obtener header Authorization (desde PHP_AUTH_* o HTTP_AUTHORIZATION)
        $user = $_SERVER['PHP_AUTH_USER'] ?? null;
        $pass = $_SERVER['PHP_AUTH_PW'] ?? null;

        if ($user !== API_BASIC_USER || $pass !== API_BASIC_PASS) {
            header('WWW-Authenticate: Basic realm="API Mascotas"');
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
        }
    }

    /**
     * Requiere que el veterinario esté logueado
     * Verifica sesión activa.
     *
     * @return string Correo del veterinario logueado
     */
    protected function requireAuth(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = $_SESSION['veterinario_email'] ?? null;

        if (!$email) {
            $this->jsonResponse(['error' => 'Authentication required'], 401);
        }

        return $email;
    }
}
