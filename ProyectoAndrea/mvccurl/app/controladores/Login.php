<?php

namespace Cls\Mvc2app;

class Login extends Controlador
{
    public function index()
    {
        $this->vista('login/index');
    }

    public function entrar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Guardamos las credenciales en la SESIÓN
            session_start();
            $_SESSION['api_user'] = $_POST['usuario'];
            $_SESSION['api_pass'] = $_POST['pass'];

            // Intentamos ir a artículos para ver si la API nos deja entrar
            header('Location: '.RUTA_URL.'/articulos/index');
        }
    }

    public function salir()
    {
        session_start();
        session_destroy();
        header('Location: '.RUTA_URL.'/login');
    }
}
