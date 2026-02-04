<?php

declare(strict_types=1);

namespace Mrs\Mvcapuntes\Modelos;

use Mrs\Mvcapuntes\Librerias\Db;

/**
 * MascotaModelo - Gestiona operaciones CRUD de mascotas.
 */
class MascotaModelo
{
    private Db $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    /**
     * Obtiene todas las mascotas con información del dueño.
     *
     * @return array Lista de mascotas
     */
    public function obtenerTodas(): array
    {
        $this->db->query('
            SELECT m.id, m.nombre, m.tipo, m.fecha_nacimiento, m.foto_url, m.id_persona,
                   p.nombre as persona_nombre, p.apellidos as persona_apellidos, p.telefono, p.email
            FROM mascotas m
            LEFT JOIN personas p ON m.id_persona = p.id
            ORDER BY m.nombre
        ');

        return $this->db->registros();
    }

    /**
     * Obtiene una mascota por ID.
     *
     * @param string $id ID de la mascota
     *
     * @return array|null Mascota o null si no existe
     */
    public function obtenerPorId(string $id): ?array
    {
        $this->db->query('
            SELECT m.id, m.nombre, m.tipo, m.fecha_nacimiento, m.foto_url, m.id_persona,
                   p.nombre as persona_nombre, p.apellidos as persona_apellidos, p.telefono, p.email
            FROM mascotas m
            LEFT JOIN personas p ON m.id_persona = p.id
            WHERE m.id = :id
            LIMIT 1
        ');
        $this->db->bind(':id', $id);

        $row = $this->db->registro();

        return $row ?: null;
    }

    /**
     * Obtiene mascotas por dueño (persona).
     *
     * @param string $idPersona ID de la persona
     *
     * @return array Lista de mascotas
     */
    public function obtenerPorPersona(string $idPersona): array
    {
        $this->db->query('
            SELECT m.id, m.nombre, m.tipo, m.fecha_nacimiento, m.foto_url, m.id_persona
            FROM mascotas m
            WHERE m.id_persona = :id_persona
            ORDER BY m.nombre
        ');
        $this->db->bind(':id_persona', $idPersona);

        return $this->db->registros();
    }

    /**
     * Crea una nueva mascota.
     *
     * @param string      $id        UUID de la mascota
     * @param string      $nombre    Nombre
     * @param string      $tipo      Tipo de mascota
     * @param string|null $fechaNac  Fecha de nacimiento
     * @param string|null $fotoUrl   URL de la foto
     * @param string      $idPersona ID del dueño
     *
     * @return bool True si se creó correctamente
     */
    public function crear(string $id, string $nombre, string $tipo, ?string $fechaNac, ?string $fotoUrl, string $idPersona): bool
    {
        $this->db->query('
            INSERT INTO mascotas (id, nombre, tipo, fecha_nacimiento, foto_url, id_persona)
            VALUES (:id, :nombre, :tipo, :fecha_nac, :foto_url, :id_persona)
        ');
        $this->db->bind(':id', $id);
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':tipo', $tipo);
        $this->db->bind(':fecha_nac', $fechaNac);
        $this->db->bind(':foto_url', $fotoUrl);
        $this->db->bind(':id_persona', $idPersona);

        return $this->db->execute();
    }

    /**
     * Actualiza una mascota existente.
     *
     * @param string      $id        UUID de la mascota
     * @param string      $nombre    Nuevo nombre
     * @param string      $tipo      Nuevo tipo
     * @param string|null $fechaNac  Nueva fecha de nacimiento
     * @param string|null $fotoUrl   Nueva URL de foto
     * @param string      $idPersona Nuevo ID del dueño
     *
     * @return bool True si se actualizó
     */
    public function actualizar(string $id, string $nombre, string $tipo, ?string $fechaNac, ?string $fotoUrl, string $idPersona): bool
    {
        $this->db->query('
            UPDATE mascotas
            SET nombre = :nombre, tipo = :tipo, fecha_nacimiento = :fecha_nac, 
                foto_url = :foto_url, id_persona = :id_persona
            WHERE id = :id
        ');
        $this->db->bind(':id', $id);
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':tipo', $tipo);
        $this->db->bind(':fecha_nac', $fechaNac);
        $this->db->bind(':foto_url', $fotoUrl);
        $this->db->bind(':id_persona', $idPersona);

        return $this->db->execute();
    }

    /**
     * Elimina una mascota.
     *
     * @param string $id UUID de la mascota
     *
     * @return bool True si se eliminó
     */
    public function eliminar(string $id): bool
    {
        $this->db->query('DELETE FROM mascotas WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }
}
