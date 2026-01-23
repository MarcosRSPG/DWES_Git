<?php
declare(strict_types=1);

namespace MRS\Modelos;

/**
 * Objeto de línea de carrito (POO) para guardar en $_SESSION['carrito'].
 */
class LineaCarrito
{
    private string $pk;
    private string $nombre;
    private string $descripcion;
    private float $peso;
    private int $unidades;

    public function __construct(array $producto, int $unidades)
    {
        $this->pk = (string)($producto['CodProd'] ?? '');
        $this->nombre = (string)($producto['Nombre'] ?? '');
        $this->descripcion = (string)($producto['Descripcion'] ?? '');
        $this->peso = (float)($producto['Peso'] ?? 0);
        $this->unidades = max(0, $unidades);
    }

    public function getPk(): string { return $this->pk; }
    public function getNombre(): string { return $this->nombre; }
    public function getDescripcion(): string { return $this->descripcion; }
    public function getPeso(): float { return $this->peso; }
    public function getUnidades(): int { return $this->unidades; }

    public function setUnidades(int $u): void { $this->unidades = max(0, $u); }

    public function totalPeso(): float { return $this->peso * $this->unidades; }
}
