<?php

namespace Mrs\ApiServer\librerias;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Mailer - Gestor de envío de correos electrónicos con PHPMailer.
 */
class Mailer
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        try {
            // Configuración SMTP desde constantes del config.php
            $this->mail->isSMTP();
            $this->mail->Host = SMTP_HOST;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = SMTP_USER;
            $this->mail->Password = SMTP_PASS;
            $this->mail->SMTPSecure = SMTP_SECURE === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
            $this->mail->Port = SMTP_PORT;

            // Configuración general
            $this->mail->setFrom(SMTP_USER, 'Gestor Restaurantes');
            $this->mail->CharSet = 'UTF-8';
            $this->mail->isHTML(true);
        } catch (Exception $e) {
            throw new Exception('Error al configurar Mailer: '.$e->getMessage());
        }
    }

    /**
     * Envía un correo de confirmación de pedido.
     *
     * @param string $destinatario      Email del restaurante
     * @param string $nombreRestaurante Nombre del restaurante
     * @param string $codPedido         Código del pedido
     * @param array  $lineas            Líneas del pedido
     * @param float  $total             Total del pedido
     *
     * @return bool True si se envió correctamente
     */
    public function enviarConfirmacionPedido($destinatario, $nombreRestaurante, $codPedido, $lineas, $total)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($destinatario);

            $this->mail->Subject = 'Confirmación de Pedido - '.$codPedido;

            // Construir tabla de productos
            $tablaProductos = '';
            foreach ($lineas as $linea) {
                $tablaProductos .= '<tr>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd;">'.htmlspecialchars($linea['Nombre']).'</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd; text-align: center;">'.$linea['Unidades'].'</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd; text-align: right;">'.number_format($linea['PrecioUnitario'], 2).' €</td>
                    <td style="padding: 8px; border-bottom: 1px solid #ddd; text-align: right; font-weight: bold;">'.number_format($linea['Subtotal'], 2).' €</td>
                </tr>';
            }

            $html = '
            <html>
            <head>
                <meta charset="UTF-8">
                <style>
                    body { font-family: Arial, sans-serif; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #3498db; color: white; padding: 20px; text-align: center; border-radius: 5px; }
                    .content { padding: 20px; background: #f9f9f9; margin-top: 20px; border-radius: 5px; }
                    table { width: 100%; margin-top: 20px; border-collapse: collapse; }
                    th { background: #3498db; color: white; padding: 12px; text-align: left; }
                    .total-row { font-weight: bold; font-size: 1.2em; background: #e8f4f8; }
                    .footer { margin-top: 20px; text-align: center; color: #666; font-size: 0.9em; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1>✓ Pedido Confirmado</h1>
                    </div>

                    <div class="content">
                        <p>Hola <strong>'.htmlspecialchars($nombreRestaurante).'</strong>,</p>

                        <p>Tu pedido ha sido registrado correctamente. Aquí están los detalles:</p>

                        <p><strong>Número de Pedido:</strong> '.htmlspecialchars($codPedido).'</p>
                        <p><strong>Fecha:</strong> '.date('d/m/Y H:i').'</p>

                        <table>
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unit.</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                '.$tablaProductos.'
                                <tr class="total-row">
                                    <td colspan="3" style="padding: 12px; text-align: right;">TOTAL:</td>
                                    <td style="padding: 12px; text-align: right;">'.number_format($total, 2).' €</td>
                                </tr>
                            </tbody>
                        </table>

                        <p style="margin-top: 20px;">Gracias por tu pedido. Nos pondremos en contacto pronto.</p>
                    </div>

                    <div class="footer">
                        <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
                    </div>
                </div>
            </body>
            </html>';

            $this->mail->Body = $html;
            $this->mail->AltBody = 'Confirmación de pedido '.$codPedido;

            return $this->mail->send();
        } catch (Exception $e) {
            error_log('Error al enviar correo de confirmación: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Envía un correo genérico.
     *
     * @param string $destinatario Email del destinatario
     * @param string $asunto       Asunto del correo
     * @param string $html         Cuerpo en HTML
     *
     * @return bool True si se envió correctamente
     */
    public function enviarCorreo($destinatario, $asunto, $html)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($destinatario);
            $this->mail->Subject = $asunto;
            $this->mail->Body = $html;

            return $this->mail->send();
        } catch (Exception $e) {
            error_log('Error al enviar correo: '.$e->getMessage());

            return false;
        }
    }
}
