<?php

namespace Mrs\ApiServer\modelos;

use Mrs\ApiServer\librerias\Db;
use Ramsey\Uuid\Uuid;

/**
 * GestorPedidos - Gestiona pedidos y carrito de compra.
 */
class GestorPedidos
{
    /**
     * Obtiene el pedido abierto (estado: abierto) de un restaurante.
     *
     * @param string $codRes UUID del restaurante
     *
     * @return array|null Pedido abierto o null
     */
    public static function getPedidoAbierto($codRes)
    {
        $pdo = Db::getConexion();

        $sql = 'SELECT CodPed, FechaPedido, Estado, Restaurante, Total
                FROM pedidos
                WHERE Restaurante = :res AND Estado = "abierto"
                ORDER BY FechaPedido DESC
                LIMIT 1';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['res' => $codRes]);

        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Crea un nuevo pedido.
     *
     * @param string $codPed UUID del pedido
     * @param string $codRes UUID del restaurante
     *
     * @return bool True si se creó
     */
    public static function crearPedido($codPed, $codRes)
    {
        $pdo = Db::getConexion();

        $sql = 'INSERT INTO pedidos (CodPed, FechaPedido, Estado, Restaurante, Total)
                VALUES (:cod, NOW(), "abierto", :res, 0)';

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'cod' => $codPed,
            'res' => $codRes,
        ]);
    }

    /**
     * Obtiene o crea un pedido abierto.
     *
     * @param string $codRes UUID del restaurante
     *
     * @return string UUID del pedido
     */
    public static function getOrCreatePedidoAbierto($codRes)
    {
        $abierto = self::getPedidoAbierto($codRes);

        if ($abierto) {
            return $abierto['CodPed'];
        }

        // Generar UUID para nuevo pedido
        $codPed = Uuid::uuid4()->toString();
        self::crearPedido($codPed, $codRes);

        return $codPed;
    }

    /**
     * Agrega o actualiza un producto en el pedido con precio unitario y subtotal.
     *
     * @param string $codPed      UUID del pedido
     * @param string $codProd     UUID del producto
     * @param int    $unidades    Cantidad a agregar
     * @param float  $precioUnit  Precio unitario del producto
     *
     * @return bool True si se agregó/actualizó
     */
    public static function agregarProductoAlPedido($codPed, $codProd, $unidades, $precioUnit)
    {
        if ($unidades <= 0) {
            return false;
        }

        $pdo = Db::getConexion();

        // Verificar si ya existe la línea
        $sql = 'SELECT CodPedProd, Unidades, PrecioUnitario FROM pedidosproductos
                WHERE Pedido = :ped AND Producto = :prod
                LIMIT 1';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['ped' => $codPed, 'prod' => $codProd]);
        $linea = $stmt->fetch();

        if ($linea) {
            // Actualizar unidades y subtotal
            $nuevoSubtotal = ($linea['Unidades'] + $unidades) * $linea['PrecioUnitario'];
            $sql = 'UPDATE pedidosproductos
                    SET Unidades = Unidades + :u, Subtotal = :sub
                    WHERE CodPedProd = :cod';

            $stmt = $pdo->prepare($sql);

            return $stmt->execute([
                'u' => $unidades,
                'sub' => $nuevoSubtotal,
                'cod' => $linea['CodPedProd'],
            ]);
        } else {
            // Insertar nueva línea
            $codPedProd = Uuid::uuid4()->toString();
            $subtotal = $unidades * $precioUnit;

            $sql = 'INSERT INTO pedidosproductos (CodPedProd, Pedido, Producto, Unidades, PrecioUnitario, Subtotal)
                    VALUES (:cod, :ped, :prod, :u, :precio, :sub)';

            $stmt = $pdo->prepare($sql);

            return $stmt->execute([
                'cod' => $codPedProd,
                'ped' => $codPed,
                'prod' => $codProd,
                'u' => $unidades,
                'precio' => $precioUnit,
                'sub' => $subtotal,
            ]);
        }
    }

    /**
     * Obtiene el carrito (pedido abierto con productos) de un restaurante.
     *
     * @param string $correo Email del restaurante
     *
     * @return array Carrito con lineas de productos
     */
    public static function getCarritoPorCorreo($correo)
    {
        $codRes = GestorRestaurantes::getCodResPorCorreo($correo);

        if (!$codRes) {
            return ['pedido' => null, 'lineas' => []];
        }

        $pedido = self::getPedidoAbierto($codRes);

        if (!$pedido) {
            return ['pedido' => null, 'lineas' => []];
        }

        $pdo = Db::getConexion();

        $sql = 'SELECT pp.CodPedProd, pp.Unidades, pp.PrecioUnitario, pp.Subtotal,
                       p.CodProd, p.Nombre, p.Descripcion, p.Precio, p.Stock
                FROM pedidosproductos pp
                INNER JOIN productos p ON pp.Producto = p.CodProd
                WHERE pp.Pedido = :ped
                ORDER BY p.Nombre';

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['ped' => $pedido['CodPed']]);

        return [
            'pedido' => $pedido,
            'lineas' => $stmt->fetchAll(),
        ];
    }

    /**
     * Actualiza las unidades del carrito.
     *
     * @param string $correo   Email del restaurante
     * @param array  $unidades Array asociativo [codProd => cantidad]
     *
     * @return bool True si se actualizó
     */
    public static function actualizarCarrito($correo, array $unidades)
    {
        $codRes = GestorRestaurantes::getCodResPorCorreo($correo);
        if (!$codRes) {
            return false;
        }

        $pedido = self::getPedidoAbierto($codRes);
        if (!$pedido) {
            return false;
        }

        $pdo = Db::getConexion();

        foreach ($unidades as $codProd => $cantidad) {
            $cantidad = (int) $cantidad;

            if ($cantidad <= 0) {
                // Eliminar línea
                $sql = 'DELETE FROM pedidosproductos
                        WHERE Pedido = :ped AND Producto = :prod';
            } else {
                // Actualizar unidades
                $sql = 'UPDATE pedidosproductos
                        SET Unidades = :u
                        WHERE Pedido = :ped AND Producto = :prod';
            }

            $stmt = $pdo->prepare($sql);
            $params = ['ped' => $pedido['CodPed'], 'prod' => $codProd];

            if ($cantidad > 0) {
                $params['u'] = $cantidad;
            }

            $stmt->execute($params);
        }

        return true;
    }

    /**
     * Elimina un producto del carrito.
     *
     * @param string $correo  Email del restaurante
     * @param string $codProd UUID del producto
     *
     * @return bool True si se eliminó
     */
    public static function eliminarProductoDelCarrito(string $correo, string $codProd): bool
    {
        $codRes = GestorRestaurantes::getCodResPorCorreo($correo);
        if (!$codRes) {
            return false;
        }

        $pedido = self::getPedidoAbierto($codRes);
        if (!$pedido) {
            return false;
        }

        $pdo = Db::getConexion();

        $sql = 'DELETE FROM pedidosproductos
                WHERE Pedido = :ped AND Producto = :prod';

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'ped' => $pedido['CodPed'],
            'prod' => $codProd,
        ]);
    }

    /**
     * Marca el pedido como enviado y calcula el total.
     *
     * @param string $correo Email del restaurante
     *
     * @return bool True si se envió
     */
    public static function enviarPedido($correo)
    {
        $codRes = GestorRestaurantes::getCodResPorCorreo($correo);
        if (!$codRes) {
            return false;
        }

        $pedido = self::getPedidoAbierto($codRes);
        if (!$pedido) {
            return false;
        }

        $pdo = Db::getConexion();

        // Calcular total del pedido
        $sql = 'SELECT SUM(Subtotal) as Total FROM pedidosproductos WHERE Pedido = :cod';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['cod' => $pedido['CodPed']]);
        $resultado = $stmt->fetch();
        $total = $resultado['Total'] ?: 0;

        // Actualizar estado a enviado y guardar total
        $sql = 'UPDATE pedidos SET Estado = "enviado", Total = :total WHERE CodPed = :cod';
        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'total' => $total,
            'cod' => $pedido['CodPed'],
        ]);
    }
}
