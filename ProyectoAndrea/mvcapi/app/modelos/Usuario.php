<?php
namespace Cls\Mvc2app;
use Cls\Mvc2app\Db;

class Usuario {
    private $db;

    public function __construct() {
        $this->db = new Db();
    }

    public function obtenerPorNombre($nombre) {
        $this->db->query("SELECT * FROM usuarios_api WHERE user = :user");
        $this->db->bind(':user', $nombre);
        return $this->db->registroAssoc(); // Devolvemos el array con el hash
    }
}