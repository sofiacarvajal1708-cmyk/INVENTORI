<?php
/**
 * Modelo de Logs de Seguridad e Historial de Auditoría
 */
class Log extends Model {

    /**
     * Escribe un evento en la bitácora de seguridad con detalles encriptados
     */
    public function write($userId, $action, $table = null, $recordId = null, $details = '') {
        try {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            
            // Cifrar los detalles con AES-256
            $encryptedDetails = Security::encrypt($details);
            
            $stmt = $this->db->prepare("
                INSERT INTO logs_seguridad 
                (id_usuario, accion_ejecutada, tabla_afectada, id_registro_afectado, direccion_ip, detalles_encriptados) 
                VALUES 
                (:userId, :action, :table, :recordId, :ip, :details)
            ");
            
            $stmt->execute([
                'userId' => $userId,
                'action' => $action,
                'table' => $table,
                'recordId' => $recordId,
                'ip' => $ip,
                'details' => $encryptedDetails
            ]);
            return true;
        } catch (Exception $e) {
            // Silencioso o log local del servidor
            error_log("Error al escribir log de seguridad: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener listado de logs recientes (desencriptando detalles en tiempo de ejecución)
     */
    public function getRecientes($limit = 50) {
        $stmt = $this->db->prepare("
            SELECT l.*, u.nombres, u.apellidos, u.email 
            FROM logs_seguridad l
            LEFT JOIN usuarios u ON l.id_usuario = u.id_usuario
            ORDER BY l.fecha_evento DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $logs = $stmt->fetchAll();

        // Desencriptar los detalles en cada registro antes de enviarlos a la vista
        foreach ($logs as &$log) {
            if (!empty($log['detalles_encriptados'])) {
                $log['detalles_desencriptados'] = Security::decrypt($log['detalles_encriptados']);
            } else {
                $log['detalles_desencriptados'] = 'Sin detalles.';
            }
        }
        return $logs;
    }
}
