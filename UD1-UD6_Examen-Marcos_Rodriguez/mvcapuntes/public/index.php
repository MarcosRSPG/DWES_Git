<?php

declare(strict_types=1);

require_once dirname(__DIR__).'/vendor/autoload.php';

session_start();

require_once dirname(__DIR__).'/app/iniciador.php';

new Mrs\Mvcapuntes\Librerias\Core();
