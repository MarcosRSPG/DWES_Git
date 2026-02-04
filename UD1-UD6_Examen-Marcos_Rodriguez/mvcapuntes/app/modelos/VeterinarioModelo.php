<?php

declare(strict_types=1);

namespace Mrs\Mvcapuntes\Modelos;

use Mrs\Mvcapuntes\Librerias\Db;

/**
 * VeterinarioModelo - Gestiona operaciones CRUD de veterinarios.
 */
class VeterinarioModelo
{
    private Db $db;

    public function __construct()
    {
        $this->db = new Db();
    }

    /**
     * Valida credenciales de un veterinario.
     *
     * @param string $email Email del veterinario
     * @param string $clave Contraseña en texto plano
     *
     * @return array|null Datos del veterinario o null si no válido
     */
    public function validarCredenciales(string $email, string $clave): ?array
    {
        $this->db->query('SELECT id, email, clave, nombre FROM veterinarios WHERE email = :email LIMIT 1');
        $this->db->bind(':email', $email);

        $veterinario = $this->db->registro();

        if (!$veterinario) {
            return null;
        }

        // Verificar contraseña (soporta texto plano y hash)
        $claveValida = false;
        if (password_verify($clave, $veterinario['clave'])) {
            $claveValida = true;
        } elseif ($clave === $veterinario['clave']) {
            // Soporte para contraseñas en texto plano (legacy)
            $claveValida = true;
        }

        if ($claveValida) {
            unset($veterinario['clave']);

            return $veterinario;
        }

        return null;
    }

    /**
     * Obtiene un veterinario por email.
     *
     * @param string $email Email del veterinario
     *
     * @return array|null Datos del veterinario (sin contraseña)
     */
    public function obtenerPorEmail(string $email): ?array
    {
        $this->db->query('SELECT id, email, nombre FROM veterinarios WHERE email = :email LIMIT 1');
        $this->db->bind(':email', $email);

        $row = $this->db->registro();

        return $row ?: null;
    }

    /**
     * Obtiene todos los veterinarios.
     *
     * @return array Lista de veterinarios
     */
    public function obtenerTodos(): array
    {
        $this->db->query('SELECT id, email, nombre FROM veterinarios ORDER BY nombre');

        return $this->db->registros();
    }

    /**
     * Crea un nuevo veterinario.
     *
     * @param string $id     UUID del veterinario
     * @param string $email  Email
     * @param string $clave  Contraseña en texto plano (se hasheará)
     * @param string $nombre Nombre
     *
     * @return bool True si se creó correctamente
     */
    public function crear(string $id, string $email, string $clave, string $nombre): bool
    {
        // Hashear la contraseña
        $claveHash = password_hash($clave, PASSWORD_DEFAULT);

        $this->db->query('INSERT INTO veterinarios (id, email, clave, nombre) VALUES (:id, :email, :clave, :nombre)');
        $this->db->bind(':id', $id);
        $this->db->bind(':email', $email);
        $this->db->bind(':clave', $claveHash);
        $this->db->bind(':nombre', $nombre);

        return $this->db->execute();
    }
}
