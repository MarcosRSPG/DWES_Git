<?php
declare(strict_types=1);

namespace MRS\Modelos;

use MRS\Librerias\Db;

class RestauranteModelo
{
    private Db $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    public function obtenerPorCredenciales(string $correo, string $clave): array|false
    {
        $sql = 'SELECT CodRes, Correo, Clave FROM restaurantes WHERE Correo = :c AND Clave = :p LIMIT 1';
        return $this->db->query($sql)
            ->bind(':c', $correo)
            ->bind(':p', $clave)
            ->registro();
    }
}
