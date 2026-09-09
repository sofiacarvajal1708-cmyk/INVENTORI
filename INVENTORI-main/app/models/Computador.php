<?php
/**
 * Modelo de Computadores y Fichas Técnicas
 */
class Computador extends Model {

    /**
     * Listar todos los computadores con su sala y marca
     */
    public function getAll($filters = []) {
        $sql = "
            SELECT c.*, m.nombre_marca, s.nombre_sala, s.sede as sala_sede, s.tiene_polo_a_tierra, s.tiene_estabilizador
            FROM computadores c
            JOIN marcas m ON c.id_marca = m.id_marca
            JOIN salas s ON c.id_sala_actual = s.id_sala
            WHERE 1=1
        ";
        
        $params = [];
        if (!empty($filters['sala'])) {
            $sql .= " AND c.id_sala_actual = :sala";
            $params['sala'] = $filters['sala'];
        }
        if (!empty($filters['laboratorio'])) {
            $sql .= " AND c.id_sala_actual = :laboratorio";
            $params['laboratorio'] = $filters['laboratorio'];
        }
        if (!empty($filters['sede'])) {
            $sql .= " AND (c.sede = :sede OR s.sede = :sede)";
            $params['sede'] = $filters['sede'];
        }
        if (!empty($filters['estado'])) {
            $sql .= " AND c.estado_activo = :estado";
            $params['estado'] = $filters['estado'];
        }

        $sql .= " ORDER BY c.id_computador DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener un computador por ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT c.*, m.nombre_marca, s.nombre_sala, s.sede as sala_sede, s.tiene_polo_a_tierra, s.tiene_estabilizador
            FROM computadores c
            JOIN marcas m ON c.id_marca = m.id_marca
            JOIN salas s ON c.id_sala_actual = s.id_sala
            WHERE c.id_computador = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Obtener componentes internos de un computador
     */
    public function getComponents($id) {
        $stmt = $this->db->prepare("
            SELECT * FROM componentes_internos 
            WHERE id_computador = :id 
            ORDER BY tipo_componente ASC
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener listado de marcas
     */
    public function getBrands() {
        $stmt = $this->db->query("SELECT * FROM marcas ORDER BY nombre_marca ASC");
        return $stmt->fetchAll();
    }

    /**
     * Crear un computador / equipo
     */
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO computadores 
            (placa_sed, numero_serial, id_marca, modelo, procesador, licenciamiento, fecha_adquisicion, id_sala_actual, estado_activo, sede, descripcion_adicional) 
            VALUES 
            (:placa_sed, :numero_serial, :id_marca, :modelo, :procesador, :licenciamiento, :fecha_adquisicion, :id_sala_actual, :estado_activo, :sede, :descripcion_adicional)
        ");
        
        $stmt->execute([
            'placa_sed' => !empty($data['placa_sed']) ? $data['placa_sed'] : null,
            'numero_serial' => $data['numero_serial'],
            'id_marca' => $data['id_marca'],
            'modelo' => $data['modelo'],
            'procesador' => $data['procesador'] ?? null,
            'licenciamiento' => $data['licenciamiento'] ?? null,
            'fecha_adquisicion' => !empty($data['fecha_adquisicion']) ? $data['fecha_adquisicion'] : date('Y-m-d'),
            'id_sala_actual' => $data['id_sala_actual'],
            'estado_activo' => $data['estado_activo'] ?? 'Operativo',
            'sede' => !empty($data['sede']) ? $data['sede'] : 'Santa Margarita',
            'descripcion_adicional' => $data['descripcion_adicional'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Actualizar datos del computador / equipo
     */
    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE computadores 
            SET placa_sed = :placa_sed, 
                numero_serial = :numero_serial, 
                id_marca = :id_marca, 
                modelo = :modelo, 
                procesador = :procesador, 
                licenciamiento = :licenciamiento, 
                fecha_adquisicion = :fecha_adquisicion, 
                id_sala_actual = :id_sala_actual, 
                estado_activo = :estado_activo,
                sede = :sede,
                descripcion_adicional = :descripcion_adicional
            WHERE id_computador = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'placa_sed' => !empty($data['placa_sed']) ? $data['placa_sed'] : null,
            'numero_serial' => $data['numero_serial'],
            'id_marca' => $data['id_marca'],
            'modelo' => $data['modelo'],
            'procesador' => $data['procesador'] ?? null,
            'licenciamiento' => $data['licenciamiento'] ?? null,
            'fecha_adquisicion' => $data['fecha_adquisicion'],
            'id_sala_actual' => $data['id_sala_actual'],
            'estado_activo' => $data['estado_activo'],
            'sede' => !empty($data['sede']) ? $data['sede'] : 'Santa Margarita',
            'descripcion_adicional' => $data['descripcion_adicional'] ?? null
        ]);
    }

    /**
     * Agregar un componente interno
     */
    public function addComponent($data) {
        $stmt = $this->db->prepare("
            INSERT INTO componentes_internos (id_computador, tipo_componente, serial_componente, especificaciones_tecnicas, estado_componente)
            VALUES (:id_computador, :tipo, :serial, :specs, 'Instalado')
        ");
        return $stmt->execute([
            'id_computador' => $data['id_computador'],
            'tipo' => $data['tipo_componente'],
            'serial' => $data['serial_componente'],
            'specs' => $data['especificaciones_tecnicas']
        ]);
    }

    /**
     * Eliminar o cambiar estado de componente (Evitar pérdida hormiga)
     */
    public function updateComponentStatus($id, $status) {
        $stmt = $this->db->prepare("
            UPDATE componentes_internos 
            SET estado_componente = :status 
            WHERE id_componente = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'status' => $status
        ]);
    }
}
