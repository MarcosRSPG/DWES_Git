<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/tools/Mailer.php';

use Dotenv\Dotenv;
use Tools\Mailer;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$mailer = new Mailer();

$ok1 = $mailer->sendMail(
    'marcosrs.softwaredeveloper@gmail.com',
    'marcosrs5775@gmail.com',
    'Prueba 1 - Sin CC ni adjunto',
    '',
    ''
);
echo $ok1 ? "Prueba 1 OK\n" : "Prueba 1 ERROR\n";

$ok2 = $mailer->sendMail(
    'marcosrs.softwaredeveloper@gmail.com',
    'marcosrs5775@gmail.com',
    'Prueba 2 - Con CC',
    'cc@ejemplo.com',
    ''
);
echo $ok2 ? "Prueba 2 OK\n" : "Prueba 2 ERROR\n";

$ok3 = $mailer->sendMail(
    'marcosrs.softwaredeveloper@gmail.com',
    'marcosrs5775@gmail.com',
    'Prueba 3 - Con adjunto',
    '',
    __DIR__.'./UD3_AC_EnviarCorreo.pdf'
);
echo $ok3 ? "Prueba 3 OK\n" : "Prueba 3 ERROR\n";

$ok4 = $mailer->sendMail(
    'marcosrs.softwaredeveloper@gmail.com',
    'marcosrs5775@gmail.com',
    'Prueba 4 - CC + adjunto',
    'cc2@ejemplo.com',
    __DIR__.'./UD3_AC_EnviarCorreo.pdf'
);
echo $ok4 ? "Prueba 4 OK\n" : "Prueba 4 ERROR\n";
