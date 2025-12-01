<?php


class Logs
{
    private $id;
    private $accion;
    private $fecha;

    public function __construct($id, $accion, $fecha){

        $this->id = $id;
        $this->accion = $accion;
        $this->fecha = $fecha;
    }

    public static function fromArray(array $fila)
    {
        return new self(
            $fila['id'] ?? null,
            $fila['accion'] ?? null,
            $fila['fecha'] ?? null,
        );
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'accion' => $this->accion,
            'fecha' => $this->fecha,
        ];
    }

    public function getAccion()
    {
        return $this->accion;
    }

    public function setAccion($accion)
    {
        $this->accion = $accion;
    }

    public function __toString()
    {
        return $this->nombre;
    }
    public function getFecha()
    {
        return $this->fecha;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
}