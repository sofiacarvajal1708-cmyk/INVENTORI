<?php
/**
 * Modelo de Salas de Cómputo e Infraestructura Física
 */
class Sala extends Model {

    /**
     * Listar todas las salas
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM salas ORDER BY nombre_sala ASC");
        return $stmt->fetchAll();
    }

    /**
     * Obtener sala por ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM salas WHERE id_sala = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Crear una nueva sala
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO salas (nombre_sala, tiene_polo_a_tierra, tiene_estabilizador, tiene_red_structured, ultima_revision_infraestructura)
            VALUES (:nombre, :polo, :estabilizador, :red, :fecha)
        ");
        return $stmt->execute([
            'nombre' => $data['nombre_sala'],
            'polo' => $data['tiene_polo_a_tierra'] ? 1 : 0,
            'estabilizador' => $data['tiene_estabilizador'] ? 1 : 0,
            'red' => $data['tiene_red_structured'] ? 1 : 0,
            'fecha' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Actualizar estado de infraestructura de sala
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE salas 
            SET nombre_sala = :nombre, 
                tiene_polo_a_tierra = :polo, 
                tiene_estabilizador = :estabilizador, 
                tiene_red_structured = :red,
                ultima_revision_infraestructura = :fecha
            WHERE id_sala = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'nombre' => $data['nombre_sala'],
            'polo' => $data['tiene_polo_a_tierra'] ? 1 : 0,
            'estabilizador' => $data['tiene_estabilizador'] ? 1 : 0,
            'red' => $data['tiene_red_structured'] ? 1 : 0,
            'fecha' => date('Y-m-d H:i:s')
        ]);
    }
}
