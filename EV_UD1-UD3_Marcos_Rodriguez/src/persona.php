<?php


class Persona
{
    private $id;
    private $nombre;
    private $apellido;
    private $email;
    private $fecha_registro;

    public function __construct($id, $nombre, $apellido, $email, $fecha_registro){

        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->fecha_registro = $fecha_registro;
    }

    public static function fromArray(array $fila)
    {
        return new self(
            $fila['id'] ?? null,
            $fila['nombre'] ?? null,
            $fila['apellido'] ?? null,
            $fila['email'] ?? null,
            $fila['fecha_registro'] ?? null
        );
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'email' => $this->email,
            'fecha_registro' => $this->fecha_registro
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

    public function getApellido()
    {
        return $this->apellido;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getFecha_registro()
    {
        return $this->fecha_registro;
    }

    public function setFecha_registro($fecha_registro)
    {
        $this->fecha_registro = $fecha_registro;
    }
}