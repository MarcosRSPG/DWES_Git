<?php

namespace Mrs\Restaurante;

use Ramsey\Uuid\Uuid;

class Producto
{
    private string $id;
    private string $nombre;
    private string $descripcion;
    private float $peso;
    private int $stock;
    private string $categoria;

    public function __construct(
        string $nombre,
        string $descripcion,
        float $peso,
        int $stock,
        string $categoria,
        ?string $id = null
    ) {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->peso = $peso;
        $this->stock = $stock;
        $this->categoria = $categoria;
    }

    public static function fromArray(array $fila): self
    {
        $id = $fila['CodProd'] ?? $fila['id'] ?? null;

        return new self(
            $fila['Nombre'] ?? $fila['nombre'] ?? '',
            $fila['Descripcion'] ?? $fila['descripcion'] ?? '',
            (float) ($fila['Peso'] ?? $fila['peso'] ?? 0),
            (int) ($fila['Stock'] ?? $fila['stock'] ?? 0),
            $fila['Categoria'] ?? $fila['categoria'] ?? '',
            $id
        );
    }

    public function toDbParams(): array
    {
        return [
            'CodProd' => $this->id,
            'Nombre' => $this->nombre,
            'Descripcion' => $this->descripcion,
            'Peso' => $this->peso,
            'Stock' => $this->stock,
            'Categoria' => $this->categoria,
        ];
    }
}
