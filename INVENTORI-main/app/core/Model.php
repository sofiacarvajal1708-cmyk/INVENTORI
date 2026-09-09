<?php
/**
 * Clase base para todos los modelos
 */
class Model {
    protected $db;

    public function __construct() {
        // Conexión segura reutilizada de Database
        $this->db = Database::connect();
    }
}
