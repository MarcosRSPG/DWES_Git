<?php

namespace Mrs\tools;

require_once __DIR__.'/../vendor/autoload.php';

use Mrs\tools\Validadores;

class Login
{
    public function autenticar($correo, $clave)
    {
        return Validadores::validarUsuario($correo, $clave);
    }
}
