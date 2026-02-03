<?php

namespace Mrs\WebCliente;

class AboutUs extends Controlador
{
    public function index()
    {
        // Cargar la vista del carrito
        $this->vista('paginas/quienes-somos');
    }
}
