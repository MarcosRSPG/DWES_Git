<?php

namespace Mrs\Restaurante;

use Ramsey\Uuid\Uuid;

class Categoria
{
    private string $id;
    private string $nombre;
    private string $descripcion;

    public function __construct(string $nombre, string $descripcion, ?string $id = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }

    public static function fromArray(array $fila): self
    {
        $id = $fila['CodCat'] ?? $fila['id'] ?? null;

        return new self(
            $fila['Nombre'] ?? $fila['nombre'] ?? '',
            $fila['Descripcion'] ?? $fila['descripcion'] ?? '',
            $id
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
        ];
    }
    public function toDbParams(): array
    {
        return [
            'CodCat' => $this->id,
            'Nombre' => $this->nombre,
            'Descripcion' => $this->descripcion,
        ];
    }

    public function __toString(): string
    {
        return $this->id . " " . $this->nombre . " " . $this->descripcion;
    }
}
