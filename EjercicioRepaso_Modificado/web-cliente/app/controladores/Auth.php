<?php

namespace Mrs\WebCliente;

class Auth extends Controlador
{
    public function login()
    {
        $this->vista('paginas/login');
    }

    public function logout()
    {
        session_destroy();
        header('Location: '.WEB_URL.'/auth/login');
        exit;
    }
}
