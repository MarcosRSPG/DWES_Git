<?php

namespace Mrs\Webcliente;

class Register extends Controlador
{
    public function index()
    {
        // Cargar la vista del carrito
        $this->vista('paginas/registro');
    }
}
