<?php
namespace Cls\Mvc2app;

class ApiTools {
    /**
     * Este es el método que centraliza la seguridad y la conexión
     */
    public static function apiCall(string $method, string $path, $data = null): array {
        // 1. Configuramos la URL
        $url = rtrim(API_BASE_URL, '/') . '/' . ltrim($path, '/');

        // 2. Seguridad: Sacamos las credenciales de la SESIÓN
        // (que el Login.php guardó tras mirar la Base de Datos)
        if (session_status() === PHP_SESSION_NONE) session_start();
        $user = $_SESSION['api_user'] ?? '';
        $pass = $_SESSION['api_pass'] ?? '';

        $ch = curl_init($url);

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_USERPWD        => "$user:$pass", // <--- Conexión con BD vía Sesión
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT        => 30,
        ];

        if ($data !== null) {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($ch, $options);
        $raw = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'ok'   => ($code >= 200 && $code < 300),
            'code' => $code,
            'data' => json_decode($raw, true) ?? ['raw' => $raw]
        ];
    }
}