<?php

namespace Tools;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    public function sendMail(
        string $from,
        string $to,
        string $subject,
        string $cc,
        string $filePath
    ): bool {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'marcosrs.softwaredeveloper@gmail.com';
            $mail->Password = $_ENV['CONTRASENA_MAILER'] ?? '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom($from);
            $mail->addAddress($to);

            if (!empty($cc)) {
                $mail->addCC($cc);
            }

            $mail->Subject = $subject;
            $mail->Body = 'Este es el cuerpo del mensaje.';

            if (!empty($filePath) && file_exists($filePath)) {
                $mail->addAttachment($filePath);
            }

            return $mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}
