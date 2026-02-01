<?php

namespace Mrs\WebCliente;

class Productos extends Controlador
{
    public function index()
    {
        // Cargar la vista de productos
        $this->vista('paginas/productos');
    }
}
