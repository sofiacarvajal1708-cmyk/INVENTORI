<?php
/**
 * Modelo de Traslados de Equipos entre Salas
 */
class Traslado extends Model {

    /**
     * Listar todos los traslados
     */
    public function getAll() {
        $stmt = $this->db->query("
            SELECT t.*, c.numero_serial, c.modelo, m.nombre_marca, c.placa_sed,
                   so.nombre_sala as sala_origen_nombre, sd.nombre_sala as sala_destino_nombre,
                   us.nombres as solicita_nombres, us.apellidos as solicita_apellidos,
                   ua.nombres as autoriza_nombres, ua.apellidos as autoriza_apellidos
            FROM traslados t
            JOIN computadores c ON t.id_computador = c.id_computador
            JOIN marcas m ON c.id_marca = m.id_marca
            JOIN salas so ON t.id_sala_origen = so.id_sala
            JOIN salas sd ON t.id_sala_destino = sd.id_sala
            JOIN usuarios us ON t.id_usuario_solicita = us.id_usuario
            LEFT JOIN usuarios ua ON t.id_usuario_autoriza = ua.id_usuario
            ORDER BY t.fecha_solicitud DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Obtener traslado por ID
     */
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT t.*, c.numero_serial, c.modelo, m.nombre_marca
            FROM traslados t
            JOIN computadores c ON t.id_computador = c.id_computador
            JOIN marcas m ON c.id_marca = m.id_marca
            WHERE t.id_traslado = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Crear una solicitud de traslado
     */
    public function create($data) {
        // Consultar la sala de origen del computador
        $stmtComp = $this->db->prepare("SELECT id_sala_actual FROM computadores WHERE id_computador = :id LIMIT 1");
        $stmtComp->execute(['id' => $data['id_computador']]);
        $salaOrigen = $stmtComp->fetchColumn();

        if (!$salaOrigen) return false;

        // Generar un código QR simulador único
        $qrCode = 'QR-TR-' . strtoupper(bin2hex(random_bytes(4))) . '-' . $data['id_computador'];

        $stmt = $this->db->prepare("
            INSERT INTO traslados 
            (id_computador, id_sala_origen, id_sala_destino, id_usuario_solicita, id_usuario_autoriza, codigo_qr_generado, estado_traslado)
            VALUES 
            (:id_computador, :origen, :destino, :solicita, :solicita, :qr, 'Pendiente')
        ");

        return $stmt->execute([
            'id_computador' => $data['id_computador'],
            'origen' => $salaOrigen,
            'destino' => $data['id_sala_destino'],
            'solicita' => $data['id_usuario_solicita'],
            'qr' => $qrCode
        ]);
    }

    /**
     * Autorizar o rechazar traslado
     */
    public function authorize($id, $autorizaUserId, $action) {
        $estado = ($action === 'aprobar') ? 'Aprobado' : 'Rechazado';
        
        $stmt = $this->db->prepare("
            UPDATE traslados 
            SET estado_traslado = :estado,
                id_usuario_autoriza = :autoriza,
                fecha_autorizacion = NOW()
            WHERE id_traslado = :id
        ");
        
        $success = $stmt->execute([
            'id' => $id,
            'estado' => $estado,
            'autoriza' => $autorizaUserId
        ]);

        // Si es aprobado, trasladar físicamente el computador actualizando su sala_actual
        if ($success && $estado === 'Aprobado') {
            $stmtGet = $this->db->prepare("SELECT id_computador, id_sala_destino FROM traslados WHERE id_traslado = :id LIMIT 1");
            $stmtGet->execute(['id' => $id]);
            $trans = $stmtGet->fetch();
            
            if ($trans) {
                $stmtComp = $this->db->prepare("
                    UPDATE computadores 
                    SET id_sala_actual = :destino 
                    WHERE id_computador = :id
                ");
                $stmtComp->execute([
                    'id' => $trans['id_computador'],
                    'destino' => $trans['id_sala_destino']
                ]);
            }
        }

        return $success;
    }
}
