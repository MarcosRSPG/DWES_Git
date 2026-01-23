<?php

declare(strict_types=1);

namespace MRS\Controladores;

use MRS\Librerias\Controlador;
use MRS\Modelos\LineaCarrito;

class Carrito extends Controlador
{
    private function &carrito(): array
    {
        if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        return $_SESSION['carrito'];
    }

    public function listar(): void
    {
        $this->requireLogin();

        $carrito = $this->carrito();

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $this->vista('paginas/carrito', [
            'titulo' => 'Carrito',
            'carrito' => $carrito,
            'flash' => $flash,
            'correo' => $_SESSION['correo'] ?? '',
        ]);
    }

    public function agregar(): void
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/Carrito/listar');
        }

        $pk = trim((string) ($_POST['pk'] ?? $_POST['CodProd'] ?? ''));
        $unidades = (int) ($_POST['unidades'] ?? $_POST['cantidad'] ?? 0);

        if ($pk === '' || $unidades <= 0) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Datos inválidos.'];
            $this->redirect('/Carrito/listar');
        }

        $prodModel = $this->modelo('ProductoModelo');
        $producto = $prodModel->obtenerProducto($pk);

        if (!$producto) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Producto no encontrado.'];
            $this->redirect('/Carrito/listar');
        }

        $stock = (int) ($producto['Stock'] ?? 0);
        $carrito = &$this->carrito();

        if (isset($carrito[$pk]) && $carrito[$pk] instanceof LineaCarrito) {
            $nueva = $carrito[$pk]->getUnidades() + $unidades;
        } else {
            $nueva = $unidades;
        }

        if ($nueva > $stock) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Stock insuficiente.'];
            $this->redirect('/Categoria/listar/'.urlencode((string) ($producto['Categoria'] ?? '')));
        }

        $carrito[$pk] = new LineaCarrito($producto, $nueva);

        $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Añadido al carrito.'];
        $cat = (string) ($producto['Categoria'] ?? '');
        $this->redirect($cat !== '' ? '/Categoria/listar/'.urlencode($cat) : '/Carrito/listar');
    }

    public function actualizar(): void
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/Carrito/listar');
        }

        $pk = trim((string) ($_POST['pk'] ?? ''));
        $unidades = (int) ($_POST['unidades'] ?? 0);

        $carrito = &$this->carrito();

        if ($pk === '' || !isset($carrito[$pk]) || !($carrito[$pk] instanceof LineaCarrito)) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Línea no encontrada.'];
            $this->redirect('/Carrito/listar');
        }

        if ($unidades <= 0) {
            unset($carrito[$pk]);
            $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Producto eliminado.'];
            $this->redirect('/Carrito/listar');
        }

        // Validar stock actual
        $prodModel = $this->modelo('ProductoModelo');
        $producto = $prodModel->obtenerProducto($pk);
        $stock = (int) ($producto['Stock'] ?? 0);

        if ($unidades > $stock) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Stock insuficiente para actualizar.'];
            $this->redirect('/Carrito/listar');
        }

        $carrito[$pk]->setUnidades($unidades);
        $_SESSION['flash'] = ['type' => 'ok', 'msg' => 'Carrito actualizado.'];
        $this->redirect('/Carrito/listar');
    }
}
