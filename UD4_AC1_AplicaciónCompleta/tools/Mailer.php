<?php

namespace Mrs\tools;

require_once __DIR__.'/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Mrs\tools\Config;

class Mailer
{
    public static function enviarMensaje(string $to, string $subject, string $htmlBody, ?string $altBody = null, ?string $cc = null): bool
    {
        // Carga config.ini (usa tu ruta real si cambia)
        $config = Config::getInstance();

        // Normaliza emails tipo "marcosrs.softwaredevelopergmail.com" -> "...@gmail.com"
        $normalize = function (string $email): string {
            $email = trim($email);
            if (strpos($email, '@') === false && str_ends_with($email, 'gmail.com')) {
                $email = str_replace('gmail.com', '@gmail.com', $email);
            }
            return $email;
        };

        $to = $normalize($to);
        $cc = $cc ? $normalize($cc) : null;

        // Valores desde config.ini
        $host = (string)($config->get('smtp', 'host') ?? 'smtp.gmail.com');
        $port = (int)($config->get('smtp','port') ?? 587);
        $secure = strtolower((string)($config->get('smtp','secure') ?? 'tls')); // tls | ssl
        $user = (string)($config->get('smtp','user') ?? '');
        $pass = (string)($config->get('smtp','pass') ?? '');
        $from = $normalize((string)($config->get('smtp','from') ?? $user));
        $fromName = (string)($config->get('smtp','from_name') ?? 'Gestor Restaurantes');

        // Si no pasas AltBody, lo generamos básico
        if ($altBody === null || $altBody === '') {
            $altBody = trim(html_entity_decode(strip_tags($htmlBody), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            $mail->CharSet = 'UTF-8';

            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $user;
            $mail->Password = $pass;
            $mail->Port = $port;
            $mail->SMTPSecure = ($secure === 'ssl')
                ? \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS
                : \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;

            $mail->setFrom($from, $fromName);
            $mail->addAddress($to);

            if ($cc) {
                $mail->addCC($cc);
            }

            $mail->Subject = $subject;
            $mail->isHTML(true);
            $mail->Body = $htmlBody;
            $mail->AltBody = $altBody;

            return $mail->send();
        } catch (\Throwable $e) {
            // Si tú ya logueas en mislogs.txt en tu Mailer, pon aquí tu logger
            error_log("Mailer::enviarMensaje ERROR: " . $e->getMessage());
            return false;
        }
    }

}
