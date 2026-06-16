<?php
/**
 * Modelo de Bajas RAEE (Residuos de Aparatos Eléctricos y Electrónicos)
 */
class Baja extends Model {

    /**
     * Listar todas las bajas registradas
     */
    public function getAll() {
        $stmt = $this->db->query("
            SELECT b.*, c.numero_serial, c.modelo, m.nombre_marca, c.placa_sed
            FROM bajas_raee b
            JOIN computadores c ON b.id_computador = c.id_computador
            JOIN marcas m ON c.id_marca = m.id_marca
            ORDER BY b.fecha_baja DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Registrar una nueva baja ambiental RAEE
     */
    public function create($data) {
        // Iniciar transacción para asegurar consistencia
        $this->db->beginTransaction();

        try {
            // 1. Insertar en bajas_raee
            $stmt = $this->db->prepare("
                INSERT INTO bajas_raee 
                (id_computador, numero_acta_consejo, fecha_baja, entidad_retoma, numero_certificado_raee, observaciones)
                VALUES 
                (:id_computador, :acta, :fecha, :entidad, :certificado, :observaciones)
            ");
            
            $stmt->execute([
                'id_computador' => $data['id_computador'],
                'acta' => $data['numero_acta_consejo'],
                'fecha' => $data['fecha_baja'],
                'entidad' => $data['entidad_retoma'],
                'certificado' => $data['numero_certificado_raee'],
                'observaciones' => $data['observaciones'] ?? ''
            ]);

            // 2. Modificar estado del computador a 'Dado de Baja'
            $stmtComp = $this->db->prepare("
                UPDATE computadores 
                SET estado_activo = 'Dado de Baja' 
                WHERE id_computador = :id
            ");
            $stmtComp->execute(['id' => $data['id_computador']]);

            // Confirmar transacción
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Deshacer cambios en caso de error
            $this->db->rollBack();
            error_log("Error al registrar baja RAEE: " . $e->getMessage());
            return false;
        }
    }
}
