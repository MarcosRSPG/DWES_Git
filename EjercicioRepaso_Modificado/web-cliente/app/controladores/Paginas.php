<?php

namespace Mrs\WebCliente;

class Paginas extends Controlador
{
    public function index()
    {
        // Si no hay sesión, redirigir al login
        if (!isset($_SESSION['restaurante'])) {
            header('Location: '.WEB_URL.'/auth/login');
            exit;
        }

        $this->vista('paginas/inicio');
    }
}
