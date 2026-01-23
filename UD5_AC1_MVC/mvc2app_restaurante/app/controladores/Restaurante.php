<?php
declare(strict_types=1);

namespace MRS\Controladores;

use MRS\Librerias\Controlador;

class Restaurante extends Controlador
{
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/Paginas/login');
        }

        $user = trim((string)($_POST['user'] ?? $_POST['email'] ?? ''));
        $pass = trim((string)($_POST['password'] ?? $_POST['clave'] ?? ''));

        if ($user === '' || $pass === '') {
            $_SESSION['login_error'] = 'Usuario/clave vacíos.';
            $this->redirect('/Paginas/login');
        }

        $modelo = $this->modelo('RestauranteModelo');
        $rest = $modelo->obtenerPorCredenciales($user, $pass);

        if (!$rest) {
            $_SESSION['login_error'] = 'Credenciales incorrectas.';
            $this->redirect('/Paginas/login');
        }

        $_SESSION['correo'] = $rest['Correo'] ?? $user;
        $_SESSION['codRes'] = $rest['CodRes'] ?? null;

        $this->redirect('/Categoria/categorias');
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'], $params['secure'], $params['httponly']
            );
        }
        session_destroy();

        $this->redirect('/Paginas/login');
    }
}
