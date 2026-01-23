<?php
declare(strict_types=1);

namespace MRS\Controladores;

use MRS\Librerias\Controlador;

class Categoria extends Controlador
{
    public function categorias(): void
    {
        $this->requireLogin();

        $catModel = $this->modelo('CategoriaModelo');
        $categorias = $catModel->obtenerCategorias();

        $this->vista('paginas/categorias', [
            'titulo' => 'Categorías',
            'categorias' => $categorias,
            'correo' => $_SESSION['correo'] ?? '',
        ]);
    }

    public function listar(string $codCat = ''): void
    {
        $this->requireLogin();

        if ($codCat === '') {
            $this->redirect('/Categoria/categorias');
        }

        $catModel = $this->modelo('CategoriaModelo');
        $prodModel = $this->modelo('ProductoModelo');

        $categoria = $catModel->obtenerCategoria($codCat);
        $productos = $prodModel->obtenerPorCategoria($codCat);

        $this->vista('paginas/productos', [
            'titulo' => $categoria['Nombre'] ?? $codCat,
            'categoria' => $categoria,
            'productos' => $productos,
            'codCat' => $codCat,
            'correo' => $_SESSION['correo'] ?? '',
        ]);
    }
}
