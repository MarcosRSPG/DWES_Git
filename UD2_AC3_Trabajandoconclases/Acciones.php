<?php

interface Acciones
{
    public function iniciar($tiempo);

    public function detener();

    public function actualizar(array $a);
}
