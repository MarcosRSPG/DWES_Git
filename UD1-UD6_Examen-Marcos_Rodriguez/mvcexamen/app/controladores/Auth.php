<?php

namespace Mrs\Webcliente;

class Auth extends Controlador
{
    public function login()
    {
        $this->vista('paginas/login');
    }

    public function logout()
    {
        session_destroy();
        header('Location: '.RUTA_URL.'auth/login');
        exit;
    }
}
