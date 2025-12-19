<?php
namespace Mrs\Restaurante;

use Ramsey\Uuid\Uuid;

class Pedido
{
    private string $codPed;
    private string $fecha;
    private int $enviado;
    private string $restaurante;

    public function __construct(string $restaurante, int $enviado = 0, ?string $codPed = null, ?string $fecha = null)
    {
        $this->codPed = $codPed ?? Uuid::uuid4()->toString();
        $this->fecha = $fecha ?? date('Y-m-d');
        $this->enviado = $enviado;
        $this->restaurante = $restaurante;
    }

    public function getCodPed(): string { return $this->codPed; }
    public function getRestaurante(): string { return $this->restaurante; }

    public function toDbParams(): array
    {
        return [
            'CodPed'      => $this->codPed,
            'Fecha'       => $this->fecha,
            'Enviado'     => $this->enviado,
            'Restaurante' => $this->restaurante,
        ];
    }
}

