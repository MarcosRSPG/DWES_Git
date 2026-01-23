<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use MRS\Modelos\LineaCarrito;

final class LineaCarritoTest extends TestCase
{
    public function testCreaLineaYCalculaPeso(): void
    {
        $prod = [
            'CodProd' => 'P1',
            'Nombre' => 'Pan',
            'Descripcion' => 'Pan rico',
            'Peso' => 0.5,
            'Stock' => 10,
            'Categoria' => 'C1',
        ];

        $l = new LineaCarrito($prod, 3);
        $this->assertSame('P1', $l->getPk());
        $this->assertSame(3, $l->getUnidades());
        $this->assertSame(1.5, $l->totalPeso());
    }

    public function testUnidadesNuncaNegativas(): void
    {
        $prod = ['CodProd' => 'P1', 'Nombre' => 'X', 'Descripcion' => 'Y', 'Peso' => 1];
        $l = new LineaCarrito($prod, -10);
        $this->assertSame(0, $l->getUnidades());

        $l->setUnidades(-2);
        $this->assertSame(0, $l->getUnidades());
    }
}
