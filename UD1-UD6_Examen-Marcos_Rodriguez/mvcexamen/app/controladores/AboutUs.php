<?php

namespace Mrs\Webcliente;

class AboutUs extends Controlador
{
    public function index()
    {
        // Cargar la vista del carrito
        $this->vista('paginas/quienes-somos');
    }
}
