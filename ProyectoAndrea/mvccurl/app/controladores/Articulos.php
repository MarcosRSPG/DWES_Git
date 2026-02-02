<?php

namespace Cls\Mvc2app;

use Cls\Mvc2app\Controlador;

class Articulos extends Controlador
{
    private string $apiBase;

    public function __construct()
    {
        $this->apiBase = rtrim(API_BASE_URL, '/');
    }

    private function apiUrl(string $path): string
    {
        return $this->apiBase . '/' . ltrim($path, '/');
    }

    /**
     * Función genérica para llamadas cURL (Sustituye a apiGet)
     * Implementa los 6 pasos: init, setopt, exec, getinfo, close, decode
     */
    private function apiCall(string $method, string $path, $data = null): array
    {

        // Iniciamos sesión para leer el usuario/pass desde formulario
        if (session_status() === PHP_SESSION_NONE) session_start();

        $user = $_SESSION['api_user'] ?? '';
        $pass = $_SESSION['api_pass'] ?? '';

        $ch = curl_init($this->apiUrl($path));
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_USERPWD        => "$user:$pass", // <--- CREDENCIALES DINÁMICAS
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_CONNECTTIMEOUT => API_CONNECT_TIMEOUT,
            CURLOPT_TIMEOUT        => API_TIMEOUT,
        ];

        /*
        // Credenciales directas en config.php
        $ch = curl_init($this->apiUrl($path));

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method, // GET, POST, DELETE
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json'
            ],
            CURLOPT_USERPWD => API_BASIC_USER . ':' . API_BASIC_PASS,
            CURLOPT_CONNECTTIMEOUT => API_CONNECT_TIMEOUT,
            CURLOPT_TIMEOUT => API_TIMEOUT,
        ];*/

        if ($data !== null) {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($ch, $options);
        $raw = curl_exec($ch);
        $err = curl_error($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decoded = json_decode($raw, true);

        return [
            'ok' => ($code >= 200 && $code < 300),
            'code' => $code,
            'data' => is_array($decoded) ? $decoded : ['raw' => $raw],
            'raw' => $raw
        ];
    }

    public function index(): void
    {
        $resp = $this->apiCall('GET', API_ART_LIST);
        $datos = [
            'titulo' => 'Listado de Artículos',
            'http' => $resp['code'],
            'articulos' => $resp['ok'] ? $resp['data'] : [],
            'error' => $resp['ok'] ? null : $resp['data'],
        ];
        $this->vista('articulos/index', $datos);
    }

    public function show(int $id): void
    {
        $resp = $this->apiCall('GET', sprintf(API_ART_ITEM, $id));
        $datos = [
            'titulo' => "Ficha Artículo #$id",
            'http' => $resp['code'],
            'articulo' => $resp['ok'] ? $resp['data'] : null,
            'error' => $resp['ok'] ? null : $resp['data'],
        ];
        $this->vista('articulos/show', $datos);
    }

    /**
     * Método para CREAR un nuevo artículo
     */

    public function nuevo(): void
    {
        $resp = ['ok' => true, 'code' => 200]; // Valores por defecto para mostrar el formulario

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Ejecutar llamada (Igual que en index pero con POST y datos)
            $resp = $this->apiCall('POST', API_ART_LIST, ['titulo' => $_POST['titulo'] ?? '']);

            if ($resp['ok']) {
                $this->index();
                //header('Location: ' . RUTA_URL . '/articulos/index');
                exit;
            }
        }

        // 2. Preparar datos (Estructura idéntica a tu método index)
        $datos = [
            'titulo' => 'Nuevo Artículo',
            'http' => $resp['code'],
            'error' => $resp['ok'] ? null : $resp['data'],
        ];

        // 3. Cargar vista
        $this->vista('articulos/nuevo', $datos);
    }

    /**
     * Método para BORRAR un artículo
     */
    public function borrar(int $id): void
    {
        // El controlador ejecuta la petición de borrado y redirige
        $this->apiCall('DELETE', sprintf(API_ART_ITEM, $id));
        $this->index();
        //header('Location: ' . RUTA_URL . '/articulos/index');
    }

    public function editar(int $id): void
    {
        $resp = ['ok' => true, 'code' => 200];

        // 1. Si el usuario pulsa "Guardar cambios"
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $payload = ['titulo' => $_POST['titulo'] ?? ''];
            // Enviamos con PUT a la ruta del item individual
            $resp = $this->apiCall('PUT', sprintf(API_ART_ITEM, $id), $payload);

            if ($resp['ok']) {
                $this->index();
                //header('Location: ' . RUTA_URL . '/articulos/index');
                exit;
            }
        } else {
            // 2. Si solo entra a la página, cargamos los datos actuales de la API
            $resp = $this->apiCall('GET', sprintf(API_ART_ITEM, $id));
        }

        $datos = [
            'titulo'   => "Editar Artículo #$id",
            'http'     => $resp['code'],
            'articulo' => $resp['ok'] ? ($resp['data'] ?? null) : null,
            'error'    => $resp['ok'] ? null : $resp['data'],
        ];

        $this->vista('articulos/editar', $datos);
    }
}