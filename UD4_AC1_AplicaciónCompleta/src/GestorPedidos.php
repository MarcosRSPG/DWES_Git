<?php

namespace Mrs\Restaurante;

require_once __DIR__.'/../vendor/autoload.php';

use Mrs\tools\Conexion;
use Mrs\tools\Mailer;
use Ramsey\Uuid\Uuid;

class GestorPedidos
{
    private static ?\PDO $pdo = null;

    private static function pdo(): \PDO
    {
        if (self::$pdo === null) {
            self::$pdo = Conexion::getConexion();
        }

        return self::$pdo;
    }

    private static function isAutoIncrement(string $tabla, string $col): bool
    {
        $stmt = self::pdo()->prepare("SHOW COLUMNS FROM `$tabla` LIKE :c");
        $stmt->execute(['c' => $col]);
        $row = $stmt->fetch();

        return $row && stripos((string) ($row['Extra'] ?? ''), 'auto_increment') !== false;
    }

    public static function getCodResByCorreo(string $correo): ?string
    {
        $sql = 'SELECT CodRes FROM restaurantes WHERE Correo = :c LIMIT 1';
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute(['c' => $correo]);
        $row = $stmt->fetch();

        return $row ? (string) $row['CodRes'] : null;
    }

    public static function getPedidoAbierto(string $codRes): ?array
    {
        $sql = 'SELECT CodPed, Fecha, Enviado, Restaurante
                FROM pedidos
                WHERE Restaurante = :r AND Enviado = 0
                ORDER BY Fecha DESC
                LIMIT 1';
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute(['r' => $codRes]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public static function crearPedido(Pedido $pedido): string
    {
        $auto = self::isAutoIncrement('pedidos', 'CodPed');

        if ($auto) {
            $sql = 'INSERT INTO pedidos (Fecha, Enviado, Restaurante)
                    VALUES (:Fecha, :Enviado, :Restaurante)';
            $stmt = self::pdo()->prepare($sql);
            $p = $pedido->toDbParams();
            $stmt->execute([
                'Fecha' => $p['Fecha'],
                'Enviado' => $p['Enviado'],
                'Restaurante' => $p['Restaurante'],
            ]);

            return (string) self::pdo()->lastInsertId();
        }

        $sql = 'INSERT INTO pedidos (CodPed, Fecha, Enviado, Restaurante)
                VALUES (:CodPed, :Fecha, :Enviado, :Restaurante)';
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($pedido->toDbParams());

        return $pedido->getCodPed();
    }

    public static function getOrCreatePedidoAbierto(string $codRes): string
    {
        $abierto = self::getPedidoAbierto($codRes);
        if ($abierto) {
            return (string) $abierto['CodPed'];
        }

        $pedido = new Pedido($codRes, 0);

        return self::crearPedido($pedido);
    }

    public static function addProductoAlPedido(string $codPed, string $codProd, int $unidades): void
    {
        if ($unidades <= 0) {
            return;
        }

        // ¿Ya existe la línea?
        $sql = 'SELECT Unidades FROM pedidosproductos
                WHERE Pedido = :p AND Producto = :pr
                LIMIT 1';
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute(['p' => $codPed, 'pr' => $codProd]);
        $row = $stmt->fetch();

        if ($row) {
            $upd = 'UPDATE pedidosproductos
                    SET Unidades = Unidades + :u
                    WHERE Pedido = :p AND Producto = :pr';
            $st = self::pdo()->prepare($upd);
            $st->execute(['u' => $unidades, 'p' => $codPed, 'pr' => $codProd]);

            return;
        }

        // Insert nueva línea
        $auto = self::isAutoIncrement('pedidosproductos', 'CodPedProd');

        if ($auto) {
            $ins = 'INSERT INTO pedidosproductos (Pedido, Producto, Unidades)
                    VALUES (:p, :pr, :u)';
            $st = self::pdo()->prepare($ins);
            $st->execute(['p' => $codPed, 'pr' => $codProd, 'u' => $unidades]);

            return;
        }

        $codPedProd = Uuid::uuid4()->toString();
        $ins = 'INSERT INTO pedidosproductos (CodPedProd, Pedido, Producto, Unidades)
                VALUES (:id, :p, :pr, :u)';
        $st = self::pdo()->prepare($ins);
        $st->execute(['id' => $codPedProd, 'p' => $codPed, 'pr' => $codProd, 'u' => $unidades]);
    }

    public static function getCarritoPorCorreo(string $correo): array
    {
        $codRes = self::getCodResByCorreo($correo);
        if (!$codRes) {
            return ['pedido' => null, 'lineas' => []];
        }

        $pedido = self::getPedidoAbierto($codRes);
        if (!$pedido) {
            return ['pedido' => null, 'lineas' => []];
        }

        $sql = 'SELECT
                    pr.CodProd,
                    pr.Nombre,
                    pr.Descripcion,
                    pr.Peso,
                    pr.Stock,
                    pp.Unidades
                FROM pedidosproductos pp
                JOIN productos pr ON pr.CodProd = pp.Producto
                WHERE pp.Pedido = :p
                ORDER BY pr.Nombre';
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute(['p' => $pedido['CodPed']]);
        $lineas = $stmt->fetchAll();

        return ['pedido' => $pedido, 'lineas' => $lineas];
    }

    public static function setUnidades(string $codPed, string $codProd, int $unidades): void
    {
        if ($unidades <= 0) {
            self::deleteLinea($codPed, $codProd);

            return;
        }

        $sql = 'UPDATE pedidosproductos
                SET Unidades = :u
                WHERE Pedido = :p AND Producto = :pr';
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute(['u' => $unidades, 'p' => $codPed, 'pr' => $codProd]);
    }

    public static function deleteLinea(string $codPed, string $codProd): void
    {
        $sql = 'DELETE FROM pedidosproductos
                WHERE Pedido = :p AND Producto = :pr';
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute(['p' => $codPed, 'pr' => $codProd]);
    }

    public static function enviarPedido(string $codPed): void
    {
        $pdo = self::pdo();
        $pdo->beginTransaction();

        try {
            // Traer líneas
            $sql = 'SELECT Producto, Unidades
                    FROM pedidosproductos
                    WHERE Pedido = :p';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['p' => $codPed]);
            $lineas = $stmt->fetchAll();

            if (!$lineas) {
                throw new \RuntimeException('El carrito está vacío.');
            }

            // Restar stock seguro
            foreach ($lineas as $l) {
                $prod = (string) $l['Producto'];
                $u = (int) $l['Unidades'];

                if (!GestorProductos::restarStock($prod, $u, $pdo)) {
                    throw new \RuntimeException("Stock insuficiente para el producto $prod");
                }
            }

            // Marcar enviado
            $upd = 'UPDATE pedidos SET Enviado = 1 WHERE CodPed = :p';
            $st = $pdo->prepare($upd);
            $st->execute(['p' => $codPed]);

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /* ===============================
       Métodos “comodín” por correo
       (para que public esté limpio)
       =============================== */

    public static function comprarPorCorreo(string $correo, string $codProd, int $cantidad): void
    {
        $codRes = self::getCodResByCorreo($correo);
        if (!$codRes) {
            throw new \RuntimeException('Restaurante no encontrado.');
        }

        // Control stock
        $p = GestorProductos::getProducto($codProd);
        if (!$p) {
            throw new \RuntimeException('Producto no encontrado.');
        }

        $stock = (int) ($p['Stock'] ?? 0);
        if ($stock <= 0) {
            throw new \RuntimeException('Sin stock.');
        }

        $codPed = self::getOrCreatePedidoAbierto($codRes);

        // Si ya había unidades, capamos a stock
        $stmt = self::pdo()->prepare('SELECT Unidades FROM pedidosproductos WHERE Pedido = :p AND Producto = :pr LIMIT 1');
        $stmt->execute(['p' => $codPed, 'pr' => $codProd]);
        $row = $stmt->fetch();
        $actual = $row ? (int) $row['Unidades'] : 0;

        $nuevo = $actual + $cantidad;
        if ($nuevo > $stock) {
            $cantidad = max(0, $stock - $actual);
        }

        if ($cantidad <= 0) {
            return;
        }

        self::addProductoAlPedido($codPed, $codProd, $cantidad);
    }

    public static function actualizarCarritoPorCorreo(string $correo, array $unidadesPorProducto): void
    {
        $codRes = self::getCodResByCorreo($correo);
        if (!$codRes) {
            return;
        }

        $pedido = self::getPedidoAbierto($codRes);
        if (!$pedido) {
            return;
        }

        $codPed = (string) $pedido['CodPed'];

        foreach ($unidadesPorProducto as $codProd => $u) {
            $codProd = (string) $codProd;
            $u = (int) $u;
            self::setUnidades($codPed, $codProd, $u);
        }
    }

    public static function eliminarDelCarritoPorCorreo(string $correo, string $codProd): void
    {
        $codRes = self::getCodResByCorreo($correo);
        if (!$codRes) {
            return;
        }

        $pedido = self::getPedidoAbierto($codRes);
        if (!$pedido) {
            return;
        }

        self::deleteLinea((string) $pedido['CodPed'], $codProd);
    }

    public static function enviarCarritoPorCorreo(string $correo): void
    {
        $codRes = self::getCodResByCorreo($correo);
        if (!$codRes) {
            throw new \RuntimeException('Restaurante no encontrado.');
        }

        $pedido = self::getPedidoAbierto($codRes);
        if (!$pedido) {
            throw new \RuntimeException('El carrito está vacío.');
        }

        $idPedido = (string) ($pedido['CodPed'] ?? '');
        if ($idPedido === '') {
            throw new \RuntimeException('No se pudo determinar el ID del pedido.');
        }

        $correoRestaurante = $_SESSION['correo'] ?? $correo;
        $cc = 'pedidos@pruebas.com';

        $fecha = (string) ($pedido['Fecha'] ?? date('Y-m-d'));

        $db = Conexion::getConexion();
        $sql = 'SELECT
                pp.Producto  AS codprod,
                p.Nombre     AS producto,
                pp.Unidades  AS cantidad,
                p.Peso       AS peso
            FROM pedidosproductos pp
            JOIN productos p ON p.CodProd = pp.Producto
            WHERE pp.Pedido = :idPedido';

        $st = $db->prepare($sql);
        $st->execute([':idPedido' => $idPedido]);
        $lineas = $st->fetchAll(\PDO::FETCH_ASSOC) ?: [];

        if (empty($lineas)) {
            throw new \RuntimeException('El pedido existe, pero no tiene líneas en pedidosproductos.');
        }

        self::enviarPedido($idPedido);

        $rowsHtml = '';
        $rowsTxt = '';
        $totalUnidades = 0;
        $totalPeso = 0.0;

        foreach ($lineas as $l) {
            $prod = (string) ($l['producto'] ?? '');
            $cod = (string) ($l['codprod'] ?? '');
            $cant = (int) ($l['cantidad'] ?? 0);
            $peso = (float) ($l['peso'] ?? 0);

            $totalUnidades += $cant;
            $totalPeso += $peso * $cant;

            $rowsHtml .= '<tr>
            <td>'.htmlspecialchars($prod).'</td>
            <td>'.htmlspecialchars($cod)."</td>
            <td style='text-align:right'>".$cant."</td>
            <td style='text-align:right'>".htmlspecialchars((string) $peso).'</td>
        </tr>';

            $rowsTxt .= "- $prod ($cod) x$cant | peso: $peso\n";
        }

        $asunto = "Pedido confirmado (#$idPedido)";

        $html = '
        <h2>Nuevo pedido</h2>
        <p><b>Restaurante:</b> '.htmlspecialchars($correoRestaurante).'</p>
        <p><b>Fecha:</b> '.htmlspecialchars($fecha).'</p>
        <p><b>ID Pedido:</b> '.htmlspecialchars($idPedido)."</p>

        <table border='1' cellpadding='6' cellspacing='0'>
          <thead>
            <tr><th>Producto</th><th>Código</th><th>Unidades</th><th>Peso (kg)</th></tr>
          </thead>
          <tbody>$rowsHtml</tbody>
        </table>

        <p><b>Total unidades:</b> $totalUnidades</p>
        <p><b>Peso total aprox:</b> ".htmlspecialchars((string) $totalPeso).' kg</p>
    ';

        $alt = "Nuevo pedido\n"
            ."Restaurante: $correoRestaurante\n"
            ."Fecha: $fecha\n"
            ."ID Pedido: $idPedido\n\n"
            ."Líneas:\n$rowsTxt\n"
            ."Total unidades: $totalUnidades\n"
            ."Peso total aprox: $totalPeso kg\n";

        Mailer::enviarMensaje($correoRestaurante, $asunto, $html, $alt, $cc);
    }
}
