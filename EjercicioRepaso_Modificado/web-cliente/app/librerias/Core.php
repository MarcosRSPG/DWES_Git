<?php

namespace Mrs\WebCliente;

class Core
{
    protected $controladorActual = 'Paginas';
    protected $metodoActual = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->getUrl();

        if (isset($url[0])) {
            $controlador = ucfirst($url[0]);
            $ruta = __DIR__.'/../controladores/'.$controlador.'.php';

            if (file_exists($ruta)) {
                $this->controladorActual = $controlador;
                unset($url[0]);
            }
        }

        $nombreClase = '\\Mrs\\WebCliente\\'.$this->controladorActual;
        $this->controladorActual = new $nombreClase();

        if (isset($url[1])) {
            if (method_exists($this->controladorActual, $url[1])) {
                $this->metodoActual = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controladorActual, $this->metodoActual], $this->params);
    }

    public function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);

            return explode('/', $url);
        }

        return [];
    }
}
