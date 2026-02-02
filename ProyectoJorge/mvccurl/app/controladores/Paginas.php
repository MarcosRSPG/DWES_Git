<?php
namespace Cls\Mvc2app;

use Cls\Mvc2app\Controlador;

    class Paginas extends Controlador{

        public function __construct(){

        }

        public function index(){

            $datos = [
                'titulo' => NOMBRESITIO,
            ];

            //$this->vista('paginas/inicio', $datos);
            $this->vista('cars/index', $datos);
        }

        public function contacto(){

            $this->vista('paginas/contacto', ['titulo' => 'Página de Contacto']);
        }

        public function cars_form()
        {
            $datos = [
                'titulo' => 'Alta de coches (vía API REST)',
            ];

            $this->vista('paginas/cars_form', $datos);
        }

        public function articulos_form()
        {
            $datos = [
                'titulo' => 'Alta de articulos (vía API REST)',
            ];

            $this->vista('paginas/articulos_form', $datos);
        }

        public function usuarios_form()
        {
            $datos = [
                'titulo' => 'Alta de usuarios (vía API REST)',
            ];

            $this->vista('paginas/usuarios_form', $datos);
        }

    }