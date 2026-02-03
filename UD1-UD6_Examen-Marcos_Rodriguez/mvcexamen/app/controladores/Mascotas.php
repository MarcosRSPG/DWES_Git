<?php

namespace Mrs\Webcliente;

class Mascotas extends Controlador
{
    public function index()
    {
        $this->vista('paginas/mascotas');
    }

    public function eliminar($id)
    {
        $cliente = new ClienteAPI();
        $cliente->delete('controladormascotas/eliminar/'.$id);
        header('Location: '.RUTA_URL.'mascotas/index');
        exit;
    }

    public function editar($id)
    {
        $datos = ['id' => $id];
        $this->vista('paginas/editar', $datos);
    }

    public function actualizar($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente = new ClienteAPI();

            $datos = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'tipo' => trim($_POST['tipo'] ?? ''),
                'fechaNac' => trim($_POST['fecha_nacimiento'] ?? ''),
                'fotoUrl' => trim($_POST['foto_url'] ?? ''),
                'idPers' => trim($_POST['id_persona'] ?? ''),
            ];

            $respuesta = $cliente->put('controladormascotas/actualizar/'.$id, $datos);

            if ($respuesta['success']) {
                header('Location: '.RUTA_URL.'mascotas/index');
                exit;
            }
        }
        header('Location: '.RUTA_URL.'mascotas/index');
        exit;
    }

    public function detalles($id)
    {
        $datos = ['id' => $id];
        $this->vista('paginas/detalles', $datos);
    }
}
