<?php
declare(strict_types=1);

namespace MRS\Modelos;

use MRS\Librerias\Db;

class ProductoModelo
{
    private Db $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    public function obtenerPorCategoria(string $codCat): array
    {
        return $this->db->query('SELECT CodProd, Nombre, Descripcion, Peso, Stock, Categoria FROM productos WHERE Categoria = :c ORDER BY Nombre')
            ->bind(':c', $codCat)
            ->registros();
    }

    public function obtenerProducto(string $codProd): array|false
    {
        return $this->db->query('SELECT CodProd, Nombre, Descripcion, Peso, Stock, Categoria FROM productos WHERE CodProd = :id LIMIT 1')
            ->bind(':id', $codProd)
            ->registro();
    }
}
