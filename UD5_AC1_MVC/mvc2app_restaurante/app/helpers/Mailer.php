<?php

declare(strict_types=1);

namespace MRS\Tools;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Mailer reutilizable (MRS\Tools).
 * - No carga vendor/autoload.php aquí: eso se hace UNA vez en el entrypoint (public/index.php).
 * - Lee SMTP de app/config/config.ini si existe (si no, usa defaults).
 */
class Mailer
{
    /**
     * Envía un email HTML (y altBody opcional).
     */
    public static function enviarMensaje(
        string $to,
        string $subject,
        string $htmlBody,
        ?string $altBody = null,
        ?string $cc = null
    ): bool {
        $smtp = self::leerSmtp();

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = (string) ($smtp['host'] ?? 'smtp.gmail.com');
            $mail->Port = (int) ($smtp['port'] ?? 587);

            $secure = strtolower((string) ($smtp['secure'] ?? 'tls'));
            if ($secure === 'ssl' || $secure === 'smtps') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $user = (string) ($smtp['user'] ?? '');
            $pass = (string) ($smtp['pass'] ?? '');
            $from = (string) ($smtp['from'] ?? $user);
            $fromName = (string) ($smtp['from_name'] ?? 'Restaurante');

            $mail->SMTPAuth = ($user !== '' && $pass !== '');
            $mail->Username = $user;
            $mail->Password = $pass;

            $mail->setFrom($from, $fromName);
            $mail->addAddress(trim($to));
            if ($cc) {
                $mail->addCC(trim($cc));
            }

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;
            $mail->AltBody = $altBody ?? strip_tags($htmlBody);

            return (bool) $mail->send();
        } catch (Exception $e) {
            // En práctica, registra el error si quieres (error_log($e->getMessage());)
            return false;
        }
    }

    /**
     * Lee configuración SMTP desde app/config/config.ini si existe.
     * Espera sección [smtp] con host, port, secure, user, pass, from, from_name.
     */
    private static function leerSmtp(): array
    {
        // Intento 1: constante RUTA_APP (si estás en el MVC)
        if (defined('RUTA_APP')) {
            $ini = rtrim((string) RUTA_APP, '/\\').DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.ini';
            if (is_file($ini)) {
                $data = parse_ini_file($ini, true, INI_SCANNER_TYPED);
                if (is_array($data) && isset($data['smtp']) && is_array($data['smtp'])) {
                    return $data['smtp'];
                }
            }
        }

        // Intento 2: entorno (por si lo reutilizas en otro proyecto)
        return [
            'host' => getenv('SMTP_HOST') ?: null,
            'port' => getenv('SMTP_PORT') ?: null,
            'secure' => getenv('SMTP_SECURE') ?: null,
            'user' => getenv('SMTP_USER') ?: null,
            'pass' => getenv('SMTP_PASS') ?: null,
            'from' => getenv('SMTP_FROM') ?: null,
            'from_name' => getenv('SMTP_FROM_NAME') ?: null,
        ];
    }
}
