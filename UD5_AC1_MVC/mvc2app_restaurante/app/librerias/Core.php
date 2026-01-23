<?php

declare(strict_types=1);

namespace MRS\Librerias;

/**
 * Core: mapea URL a Controlador/Método/Parámetros
 * Formato: /Controlador/metodo/parametro1/parametro2.
 */
class Core
{
    protected string $controladorActual = 'Paginas';
    protected string $metodoActual = 'login';
    protected array $parametros = [];

    public function __construct()
    {
        $url = $this->getUrl();

        // Controlador
        if (!empty($url[0])) {
            $candidato = ucwords((string) $url[0]);
            $fqn = 'MRS\\Controladores\\'.$candidato;
            if (class_exists($fqn)) {
                $this->controladorActual = $candidato;
                array_shift($url);
            }
        }

        $fqnControlador = 'MRS\\Controladores\\'.$this->controladorActual;
        $controlador = new $fqnControlador();

        // Método
        if (!empty($url[0]) && method_exists($controlador, (string) $url[0])) {
            $this->metodoActual = (string) $url[0];
            array_shift($url);
        }

        // Parámetros
        $this->parametros = $url ?: [];

        // Llamada final
        call_user_func_array([$controlador, $this->metodoActual], $this->parametros);
    }

    /**
     * Obtiene URL desde ?url=... (router) o desde REQUEST_URI (fallback).
     */
    public function getUrl(): array
    {
        $raw = null;

        if (isset($_GET['url'])) {
            $raw = (string) $_GET['url'];
        } else {
            $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
            $raw = trim((string) $path, '/');
        }

        $raw = rtrim($raw, '/');
        if ($raw === '') {
            return [];
        }

        $raw = filter_var($raw, FILTER_SANITIZE_URL);
        $parts = explode('/', $raw);

        return array_values(array_filter($parts, fn ($p) => $p !== ''));
    }
}
