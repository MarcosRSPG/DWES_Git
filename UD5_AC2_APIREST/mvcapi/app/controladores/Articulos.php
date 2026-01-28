<?php
namespace Cls\Mvc2app;

use Cls\Mvc2app\Controlador;

    class Articulos extends Controlador{

        public function __construct(){
            $this->modelo = $this->modelo('articulo');
        }

        public function index(){
            $articulos = $this->modelo->obtenerArticulos();
            $datos = [
                'titulo' => 'Articulos',
                'articulos' => $articulos,
            ];

            $this->vista('paginas/articulo', $datos);
        }

        public function articulo(){
            $this->vista('paginas/articulo');
        }

        public function actualizar($num_registro){
            echo $num_registro;
        }
    }