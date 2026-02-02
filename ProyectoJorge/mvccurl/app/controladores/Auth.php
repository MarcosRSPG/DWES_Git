<?php
namespace Cls\Mvc2app;

use Cls\Mvc2app\Controlador;

class Auth extends Controlador
{
    public function login()
    {
        $datos = [
            'titulo' => 'Login',
        ];

        $this->vista('auth/login', $datos);
    }

    public function setSession()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['ok' => false, 'error' => 'Método no permitido']);
            return;
        }

        $raw = file_get_contents('php://input');
        $data = json_decode($raw ?? '', true);
        $usuario = trim((string)($data['usuario'] ?? ''));

        if ($usuario === '') {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Usuario requerido']);
            return;
        }

        $_SESSION['usuario'] = $usuario;
        echo json_encode(['ok' => true]);
    }

    public function logout()
    {
        if (session_status() !== PHP_SESSION_NONE) {
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
        }

        $this->vista('auth/logout', ['titulo' => 'Logout']);
    }
}
