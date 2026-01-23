<?php

declare(strict_types=1);

namespace MRS\Modelos;

use MRS\Librerias\Db;
use MRS\Tools\Mailer;
use Ramsey\Uuid\Uuid;

class PedidoModelo
{
    private Db $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    /**
     * @param array<string, LineaCarrito> $carrito
     */
    public function crearPedidoDesdeCarrito(string $codRes, array $carrito, string $correoRestaurante): string
    {
        $pdo = $this->db->pdo();
        $pdo->beginTransaction();

        try {
            $idPedido = Uuid::uuid4()->toString();
            $fecha = date('Y-m-d');

            // Insert pedido (enviado = 1 porque es "pedido final")
            $sqlPed = 'INSERT INTO pedidos (CodPed, Fecha, Enviado, Restaurante) VALUES (:p, :f, 1, :r)';
            $st = $pdo->prepare($sqlPed);
            $st->execute([':p' => $idPedido, ':f' => $fecha, ':r' => $codRes]);

            // Insert líneas + restar stock seguro
            // 👇 IMPORTANTE: añadimos CodPedProd
            $sqlLinea = 'INSERT INTO pedidosproductos (CodPedProd, Pedido, Producto, Unidades)
                         VALUES (:id, :ped, :prod, :u)';
            $stL = $pdo->prepare($sqlLinea);

            foreach ($carrito as $pk => $linea) {
                if (!($linea instanceof LineaCarrito)) {
                    continue;
                }

                $prod = $linea->getPk();
                $u = $linea->getUnidades();

                if ($u <= 0) {
                    continue;
                }

                // Restar stock con condición
                $upd = 'UPDATE productos SET Stock = Stock - :u WHERE CodProd = :p AND Stock >= :u';
                $stU = $pdo->prepare($upd);
                $stU->execute([':u' => $u, ':p' => $prod]);

                if ($stU->rowCount() !== 1) {
                    throw new \RuntimeException('Stock insuficiente para el producto '.$prod);
                }

                // 👇 Genera ID hasheado: ppp_<hash>
                // estable y muy improbable que colisione: pedido + producto + unidades + microtime + random
                $raw = $idPedido.'|'.$prod.'|'.$u.'|'.microtime(true).'|'.bin2hex(random_bytes(8));
                $hash = substr(hash('sha256', $raw), 0, 24); // 24 chars (ajusta si quieres)
                $idLinea = 'ppp_'.$hash;

                $stL->execute([
                    ':id' => $idLinea,
                    ':ped' => $idPedido,
                    ':prod' => $prod,
                    ':u' => $u,
                ]);
            }

            $pdo->commit();

            // Email (si está configurado)
            $this->enviarEmailPedido($correoRestaurante, $idPedido, $fecha, $carrito);

            return $idPedido;
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /**
     * @param array<string, LineaCarrito> $carrito
     */
    private function enviarEmailPedido(string $to, string $idPedido, string $fecha, array $carrito): void
    {
        if ($to === '') {
            return;
        }

        $rows = '';
        $totalU = 0;
        $totalP = 0.0;

        foreach ($carrito as $linea) {
            if (!($linea instanceof LineaCarrito)) {
                continue;
            }
            $totalU += $linea->getUnidades();
            $totalP += $linea->totalPeso();

            $rows .= '<tr>'
                .'<td>'.htmlspecialchars($linea->getNombre()).'</td>'
                .'<td>'.htmlspecialchars($linea->getPk()).'</td>'
                .'<td style="text-align:right">'.(int) $linea->getUnidades().'</td>'
                .'<td style="text-align:right">'.htmlspecialchars((string) $linea->getPeso()).'</td>'
                .'</tr>';
        }

        $html = '<h2>Pedido '.$idPedido.'</h2>'
            .'<p>Fecha: '.htmlspecialchars($fecha).'</p>'
            .'<table border="1" cellpadding="6" cellspacing="0">'
            .'<thead><tr><th>Producto</th><th>Cod</th><th>Unidades</th><th>Peso</th></tr></thead>'
            .'<tbody>'.$rows.'</tbody></table>'
            .'<p><strong>Total unidades:</strong> '.$totalU.'<br>'
            .'<strong>Peso total:</strong> '.htmlspecialchars((string) $totalP).'</p>';

        Mailer::enviarMensaje($to, 'Pedido '.$idPedido, $html, null, 'pedidos@pruebas.com');
    }
}
