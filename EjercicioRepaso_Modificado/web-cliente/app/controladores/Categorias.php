<?php

namespace Mrs\WebCliente;

class Categorias extends Controlador
{
    public function index()
    {
        // Cargar la vista de categorías
        $this->vista('paginas/categorias');
    }
}
