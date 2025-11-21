<?php

require_once __DIR__.'/Validadores.php';

class Login
{
    public function autenticar($name, $password)
    {
        return Validadores::validarUsuario($name, $password);
    }
}
