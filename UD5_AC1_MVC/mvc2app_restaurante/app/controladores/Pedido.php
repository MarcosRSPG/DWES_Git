<?php

declare(strict_types=1);

namespace MRS\Controladores;

use MRS\Librerias\Controlador;

class Pedido extends Controlador
{
    public function crear(): void
    {
        $this->requireLogin();

        $carrito = $_SESSION['carrito'] ?? [];
        if (!is_array($carrito) || empty($carrito)) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'El carrito está vacío.'];
            $this->redirect('/Carrito/listar');
        }

        $codRes = (string) ($_SESSION['codRes'] ?? '');
        if ($codRes === '') {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Restaurante no identificado.'];
            $this->redirect('/Carrito/listar');
        }

        $modelo = $this->modelo('PedidoModelo');

        try {
            $idPedido = $modelo->crearPedidoDesdeCarrito($codRes, $carrito, (string) ($_SESSION['correo'] ?? ''));

            $_SESSION['carrito'] = [];

            $this->vista('paginas/mensaje', [
                'titulo' => 'Pedido creado',
                'mensaje' => 'Pedido generado correctamente: '.$idPedido,
                'linkTexto' => 'Volver a categorías',
                'linkHref' => '/Categoria/categorias',
            ]);
        } catch (\Throwable $e) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'No se pudo crear el pedido: '.$e->getMessage()];
            $this->redirect('/Carrito/listar');
        }
    }
}
