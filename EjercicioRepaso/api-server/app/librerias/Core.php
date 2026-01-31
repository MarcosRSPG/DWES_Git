<?php

namespace Mrs\ApiServer\librerias;

/**
 * Core - Enrutador principal de la API
 * Mapea URLs a controladores y métodos
 * Formato URL: /controlador/metodo/parametro.
 */
class Core
{
    protected $controladorActual;
    protected $metodoActual = 'index';
    protected $parametros = [];

    public function __construct()
    {
        $url = $this->getUrl() ?? [];

        // 1) Obtener controlador desde URL
        $controlador = $url[0] ?? '';
        $controlador = strtok($controlador, '?');
        $controlador = trim($controlador);

        $controladorClase = 'ControladorCategorias'; // default

        if ($controlador !== '') {
            $controladorClase = ucfirst(strtolower($controlador));
            $fqcn = 'Mrs\\ApiServer\\controladores\\'.$controladorClase;

            if (class_exists($fqcn)) {
                unset($url[0]);
            } else {
                $controladorClase = 'ControladorCategorias';
            }
        }

        // 2) Instanciar controlador
        $fqcnControlador = 'Mrs\\ApiServer\\controladores\\'.$controladorClase;
        $this->controladorActual = new $fqcnControlador();

        // 3) Obtener método
        $metodo = $url[1] ?? $this->metodoActual;
        if (method_exists($this->controladorActual, $metodo)) {
            $this->metodoActual = $metodo;
            unset($url[1]);
        }

        // 4) Obtener parámetros
        $this->parametros = $url ? array_values($url) : [];

        // 5) Ejecutar controlador->método(parámetros)
        call_user_func_array(
            [$this->controladorActual, $this->metodoActual],
            $this->parametros
        );
    }

    private function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);

            return explode('/', $url);
        }

        return null;
    }
}
