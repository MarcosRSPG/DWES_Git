<?php


class Mascota
{
    private $id;
    private $nombre;
    private $tipo;
    private $fecha_nacimiento;
    private $foto_url;
    private $id_persona;

    public function __construct($id, $nombre, $tipo, $fecha_nacimiento, $foto_url, $id_persona){

        $this->id = $id;
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->foto_url = $foto_url;
        $this->id_persona = $id_persona;
    }

    public static function fromArray(array $fila)
    {
        return new self(
            $fila['id'] ?? null,
            $fila['nombre'] ?? null,
            $fila['tipo'] ?? null,
            $fila['fecha_nacimiento'] ?? null,
            $fila['foto_url'] ?? null,
            $fila['id_persona'] ?? null
        );
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'tipo' => $this->tipo,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'foto_url' => $this->foto_url,
            'id_persona' => $this->id_persona,
        ];
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function getFecha_nacimiento()
    {
        return $this->fecha_nacimiento;
    }

    public function setFecha_nacimiento($fecha_nacimiento)
    {
        $this->fecha_nacimiento = $fecha_nacimiento;
    }

    public function getFoto_url()
    {
        return $this->foto_url;
    }

    public function setFoto_url($foto_url)
    {
        $this->foto_url = $foto_url;
    }

    public function getId_persona()
    {
        return $this->id_persona;
    }

    public function setId_persona($id_persona)
    {
        $this->id_persona = $id_persona;
    }
}