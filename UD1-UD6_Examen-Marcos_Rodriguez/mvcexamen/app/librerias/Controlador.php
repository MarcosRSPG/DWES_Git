<?php

namespace Mrs\Webcliente;

class Controlador
{
    public function vista($vista, $datos = [])
    {
        // Extraer array de datos para que estén disponibles como variables
        extract($datos);

        // Construir ruta al archivo de vista
        $rutaVista = __DIR__.'/../vistas/'.$vista.'.php';

        // Verificar que existe el archivo
        if (file_exists($rutaVista)) {
            include $rutaVista;
        } else {
            exit('No existe la vista: '.$vista);
        }
    }
}
