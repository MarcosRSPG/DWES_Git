<?php

namespace Mrs\WebCliente;

class Carrito extends Controlador
{
    public function index()
    {
        // Cargar la vista del carrito
        $this->vista('paginas/carrito');
    }
}
