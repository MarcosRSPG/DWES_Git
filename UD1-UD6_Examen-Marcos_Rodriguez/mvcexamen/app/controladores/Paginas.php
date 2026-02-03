<?php

namespace Mrs\Webcliente;

class Paginas extends Controlador
{
    public function __construct()
    {
    }

    public function index()
    {
        $datos = [
            'titulo' => NOMBRESITIO,
        ];

        $this->vista('paginas/inicio', $datos);
    }
}
