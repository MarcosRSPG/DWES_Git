<?php

declare(strict_types=1);

namespace MRS\Librerias;

class Controlador
{
    public function modelo(string $modelo): object
    {
        $fqn = 'MRS\\Modelos\\'.$modelo;

        return new $fqn();
    }

    public function vista(string $vista, array $datos = []): void
    {
        $ruta = RUTA_APP.'/vistas/'.$vista.'.php';
        if (!is_file($ruta)) {
            http_response_code(500);
            exit('La vista no existe: '.$vista);
        }
        require $ruta;
    }

    protected function redirect(string $path): void
    {
        header('Location: '.RUTA_URL.$path);
        exit;
    }

    protected function requireLogin(): void
    {
        if (empty($_SESSION['correo'])) {
            $this->redirect('/Paginas/login');
        }
    }
}
