<?php
/**
 * Modelo de Salas de Cómputo e Infraestructura Física
 */
class Sala extends Model {

    /**
     * Listar todas las salas
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM salas ORDER BY sede ASC, categoria ASC, nombre_sala ASC");
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
            INSERT INTO salas (nombre_sala, sede, categoria, tiene_polo_a_tierra, tiene_estabilizador, tiene_red_structured, ultima_revision_infraestructura)
            VALUES (:nombre, :sede, :categoria, :polo, :estabilizador, :red, :fecha)
        ");
        return $stmt->execute([
            'nombre' => $data['nombre_sala'],
            'sede' => !empty($data['sede']) ? $data['sede'] : 'Santa Margarita',
            'categoria' => !empty($data['categoria']) ? $data['categoria'] : 'Salas de Sistemas',
            'polo' => !empty($data['tiene_polo_a_tierra']) ? 1 : 0,
            'estabilizador' => !empty($data['tiene_estabilizador']) ? 1 : 0,
            'red' => !empty($data['tiene_red_structured']) ? 1 : 0,
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
                sede = :sede,
                categoria = :categoria,
                tiene_polo_a_tierra = :polo, 
                tiene_estabilizador = :estabilizador, 
                tiene_red_structured = :red,
                ultima_revision_infraestructura = :fecha
            WHERE id_sala = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'nombre' => $data['nombre_sala'],
            'sede' => !empty($data['sede']) ? $data['sede'] : 'Santa Margarita',
            'categoria' => !empty($data['categoria']) ? $data['categoria'] : 'Salas de Sistemas',
            'polo' => !empty($data['tiene_polo_a_tierra']) ? 1 : 0,
            'estabilizador' => !empty($data['tiene_estabilizador']) ? 1 : 0,
            'red' => !empty($data['tiene_red_structured']) ? 1 : 0,
            'fecha' => date('Y-m-d H:i:s')
        ]);
    }
}
