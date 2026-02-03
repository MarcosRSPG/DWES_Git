<?php

namespace Mrs\Webcliente;

class Mascotas extends Controlador
{
    public function index()
    {
        // Cargar la vista del carrito
        $this->vista('paginas/mascotas');
    }
}
